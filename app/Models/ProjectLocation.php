<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectLocation extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_location_user');
    }

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

    public function scopeOnGoing($query)
    {
        return $query->where('status', 'on-going');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeTotal($query)
    {
        return $query->count();
    }
}
