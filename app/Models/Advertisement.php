<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Advertisement extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'tagline',
        'description',
        'package_id',
        'image_url',
        'cta_link',
        'valid_until_date',
        'payment_file_url',
        'status',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function package()
    {
        return $this->belongsTo(AdvertisementPackage::class, 'package_id');
    }
}
