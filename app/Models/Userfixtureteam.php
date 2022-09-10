<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userfixtureteam extends Model
{
    public $timestamps = false;
    public function teams(){
        return $this->hasMany(Team::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function fixture(){
        return $this->belongsTo(Fixture::class);
    }
}
