<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationForecast extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['location_id', 'date', 'min_temperature', 'max_temperature', 'condition', 'icon'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
