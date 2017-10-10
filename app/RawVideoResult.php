<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RawVideoResult extends Model
{
    protected $guarded = ['id'];

    public function rawVideo()
    {
        return $this->belongsTo(RawVideo::class);
    }
}
