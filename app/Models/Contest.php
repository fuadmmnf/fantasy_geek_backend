<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function match(){
        return $this->belongsTo('App\Models\Match');
    }

    public function usercontests(){
        return $this->hasMany('App\Models\Usercontest');
    }
}
