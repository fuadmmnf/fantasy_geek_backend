<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Userpayment;
use App\Models\Temppayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PaymentRepository
{
    public function tempPayment(array $request)
    {
        $user = User::findOrFail($request['user_id']);
        
        if($user) {
            $temppayment = new Temppayment;
            $temppayment->user_id = $user->id;
            $temppayment->trx_id = $request['trx_id'];
            $temppayment->amount = $request['amount'];
            $temppayment->coin = $request['coin'];
            $temppayment->save();

            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false
            ], 200);
        }
    }

    public function paymentConfirm(Request $request)
    {
        if($request->get('pay_status') == 'Failed') {
            Session::flash('info', 'পেমেন্ট সম্পন্ন হয়নি, আবার চেষ্টা করুন!');
            return redirect()->route('index.index');
        }
        
        $amount_request = $request->get('opt_b');
        $amount_paid = $request->get('amount');

        if($request->pay_status == "Successful" && $amount_paid == $amount_request) {
            // OLD VERIFICATION METHOD
            
            $temppayment = Temppayment::where('trx_id', $request->mer_txnid)->first();
            // dd($request->all());
            $payment = new Userpayment;
            $payment->user_id = $temppayment->user_id;
            $payment->card_type = $request->card_type;
            $payment->trx_id = $request->mer_txnid;
            $payment->amount = $request->amount;
            $payment->coin = $temppayment->coin;
            $payment->store_amount = $request->store_amount;
            $payment->save();

            $user = User::findOrFail($temppayment->user_id);
            // $current_package_date = Carbon::parse($user->package_expiry_date);
            // $package = Package::findOrFail($temppayment->package_id);
            // if($current_package_date->greaterThanOrEqualTo(Carbon::now())) {
            //     $package_expiry_date = $current_package_date->addDays($package->numeric_duration)->format('Y-m-d') . ' 23:59:59';
            // } else {
            //     $package_expiry_date = Carbon::now()->addDays($package->numeric_duration)->format('Y-m-d') . ' 23:59:59';
            // }
            $user->total_coin = $user->total_coin + $temppayment->coin;
            $user->save();
            // ARO KAAJ THAKTE PARE, JODI FIREBASE EO UPDATE KORA LAAGE
            // dd($payment);

            $temppayment->delete();

            Session::flash('swalsuccess', 'পেমেন্ট সফল হয়েছে। অ্যাপটি ব্যবহার করুন। ধন্যবাদ!');
            // return redirect()->route('index.index');
        } else {
            // dd($request->all());
            // $paymentdata = json_encode($request->all());
            // Session::flash('swalsuccess', $paymentdata);
            // Session::flash('info', 'পেমেন্ট সম্পন্ন হয়নি, অনুগ্রহ করে Contact ফর্ম এর মাধ্যমে আমাদের জানান।');
            // return redirect()->route('index.index');
            return response()->json(['message' => 'পেমেন্ট সম্পন্ন হয়নি, অনুগ্রহ করে Contact ফর্ম এর মাধ্যমে আমাদের জানান।'], 200);
        }
    }

    public function paymentList($user_id)
    {
        $payments = Userpayment::where('user_id', $user_id)->get();

        if($payments) {
            return [
                'success' => true,
                'payments' => $payments,
                'message' => 'পাওয়া গেছে',
            ];
        } else {
            return [
                'success' => false
            ];
        }
    }
}
