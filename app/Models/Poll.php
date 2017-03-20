<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Poll extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
