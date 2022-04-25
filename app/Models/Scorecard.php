<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scorecard extends Model
{
    public $timestamps = false;
    public function player(){
        return $this->belongsTo('App\Models\Player');
    }
    public function match(){
        return $this->belongsTo('App\Models\Match');
    }
}
