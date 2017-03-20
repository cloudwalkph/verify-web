<?php

namespace App;

use App\Models\Project;
use App\Models\UserGroup;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use phpDocumentor\Reflection\Location;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function group()
    {
        return $this->belongsTo(UserGroup::class);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }
}
