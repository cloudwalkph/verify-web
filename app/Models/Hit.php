<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hit extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function location()
    {
        return $this->belongsTo(ProjectLocation::class);
    }

    public function answers()
    {
        return $this->hasMany(HitAnswer::class);
    }

    public function scopeLastUpdated()
    {
        return $this->orderBy('created_at', 'DESC')
            ->first();
    }

    public function getBrandsAttribute($value) {
        $brands = json_decode($value);

        if (! $brands) {
            return [];
        }

        $result = [];
        foreach ($brands as $brand) {
            $result[] = [
                'name' => $brand
            ];
        }

        return $result;
    }
}
