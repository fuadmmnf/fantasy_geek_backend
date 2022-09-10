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
        return $this->belongsTo(User::class);
    }

    public function contest(){
        return $this->belongsTo(Contest::class);
    }

    public function team(){
        return $this->belongsTo(Team::class);
    }
}
