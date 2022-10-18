<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $casts = [
        'prize_list' => 'array',
        'user_standings' => 'array',
    ];

    public function fixture(){
        return $this->belongsTo(Fixture::class);
    }

    public function usercontests(){
        return $this->hasMany(Usercontest::class);
    }
}
