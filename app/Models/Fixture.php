<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use HasFactory;
    public $timestamps = false;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [ 'api_fixtureid', ];

    public function team1(){
        return $this->belongsTo(Team::class);
    }
    public function team2(){
        return $this->belongsTo(Team::class);
    }

    public function contests(){
        return $this->hasMany(Contest::class);
    }

    public function scorecards(){
        return $this->hasMany(Scorecard::class);
    }

    public function pointdistribution(){
        return $this->belongsTo(Pointdistribution::class);
    }
}
