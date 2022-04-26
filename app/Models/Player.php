<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $hidden = [
         'api_pid',
    ];

    public function playerposition(){
        return $this->belongsTo('App\Models\Playerposition');
    }

    public function country(){
        return $this->belongsTo('App\Models\Country');
    }

    public function scorecards(){
        return $this->hasMany('App\Models\Scorecard');
    }
}
