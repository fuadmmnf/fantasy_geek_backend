<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usercontest extends Model
{
    use HasFactory;
    public $timestamps = false;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'transaction_id',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function contest(){
        return $this->belongsTo('App\Models\Contest');
    }

    public function team(){
        return $this->belongsTo('App\Models\Team');
    }
}
