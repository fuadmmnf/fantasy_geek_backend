<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\PaymentRepository;
use App\Http\Requests\Payment\TempPaymentRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function tempPayment(TempPaymentRequest $request)
    {
        $payment = $this->paymentRepository->tempPayment($request->validated());

        if ($payment) {
            return response()->json($payment, 200);
        } else {
            return response()->json(['message' => 'Server Error'], 401);
        }
    }

    public function paymentFailed(Request $request)
    {
        // return response()->json(['message' => 'পেমেন্টটি ব্যর্থ হয়েছে! অনুগ্রহ করে যোগাযোগ করুন।'], 200);
    } 

    public function paymentCancel(Request $request)
    {
        // return response()->json(['message' => 'পেমেন্টটি ক্যানসেল করা হয়েছে!'], 200);
    }

    public function paymentConfirm(Request $request)
    {
        $payment = $this->paymentRepository->paymentConfirm($request);
        
        if ($payment) {
            return response()->json($payment, 200);
        } else {
            return response()->json(['message' => 'Server Error'], 401);
        }
    }

    public function paymentList($user_id)
    {
        $payment = $this->paymentRepository->paymentList($user_id);
        
        if ($payment) {
            return response()->json($payment, 200);
        } else {
            return response()->json(['message' => 'Server Error'], 401);
        }
    }
}
