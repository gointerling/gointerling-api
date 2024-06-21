<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Service extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'price',
        'type',
        'time_estimated',
        'time_estimated_unit',
        'desc',
        'language_sources',
        'language_destinations',
        'working_hours',
    ];

    protected $casts = [
        'language_sources' => 'array',
        'language_destinations' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid(); // Generate UUID for 'id' when creating
        });

        static::deleting(function ($model) {
            $model->merchants()->detach();
        });
    }

    public function merchants()
    {
        return $this->belongsToMany(Merchant::class, 'rel_merchant_service');
    }
}
