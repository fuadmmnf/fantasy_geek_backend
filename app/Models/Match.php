<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    use HasFactory;
    public $timestamps = false;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [ 'api_matchid', ];

    public function team1(){
        return $this->belongsTo('App\Models\Team');
    }
    public function team2(){
        return $this->belongsTo('App\Models\Team');
    }

    public function contests(){
        return $this->hasMany('App\Models\Contest');
    }

    public function scorecards(){
        return $this->hasMany('App\Models\Scorecard');
    }

    public function pointdistribution(){
        return $this->belongsTo('App\Models\Pointdistribution');
    }
}
