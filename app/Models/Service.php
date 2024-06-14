<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'type',
        'time_estimated',
        'time_estimated_unit',
        'desc',
        'language_sources',
        'language_destinations'
    ];

    protected $casts = [
        'language_sources' => 'array',
        'language_destinations' => 'array',
    ];

    public function merchants()
    {
        return $this->belongsToMany(Merchant::class, 'rel_merchant_service');
    }
}
