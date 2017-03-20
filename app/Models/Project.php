<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function polls()
    {
        return $this->belongsToMany(Poll::class);
    }

    public function locations()
    {
        return $this->hasMany(ProjectLocation::class);
    }
}
