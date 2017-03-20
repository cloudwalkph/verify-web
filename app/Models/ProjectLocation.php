<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectLocation extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function hits()
    {
        return $this->hasMany(Hit::class);
    }
}
