<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RawVideo extends Model
{
    protected $guarded = ['id'];

    public function results()
    {
        return $this->hasMany(RawVideoResult::class);
    }
}
