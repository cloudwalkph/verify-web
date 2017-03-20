<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HitAnswer extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function hit()
    {
        return $this->belongsTo(Hit::class);
    }
}
