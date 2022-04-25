<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usermatchteam extends Model
{
    public $timestamps = false;
    public function teams(){
        return $this->hasMany('App\Models\Team');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function match(){
        return $this->belongsTo('App\Models\Match');
    }
}
