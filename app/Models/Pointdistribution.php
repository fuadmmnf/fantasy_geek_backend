<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pointdistribution extends Model
{
    public $timestamps = false;
    protected $fillable = ['*'];

    public function fixtures(){
        return $this->hasMany(Fixture::class);
    }
}
