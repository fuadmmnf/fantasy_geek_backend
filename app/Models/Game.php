<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public $timestamps = false;

    public function playerpositions(){
        return $this->hasMany('App\Models\Playerposition');
    }

    public function pointdistributions(){
        return $this->hasMany('App\Models\Pointdistribution');
    }


    public function matches(){
        return $this->hasMany('App\Models\Match');
    }
}
