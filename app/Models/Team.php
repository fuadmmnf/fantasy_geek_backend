<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $casts = [
        'team_members' => 'array',
        'key_members' => 'array',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
//    protected $hidden = [
//        'gameapi_id',
//    ];


    public function usercontests(){
        return $this->hasMany(Usercontest::class);
    }
}
