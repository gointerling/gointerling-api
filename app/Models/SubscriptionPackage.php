<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SubscriptionPackage extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'desc',
        'duration',
        'is_reviewed',
        'is_advertised',
        'is_free_shipped',
    ];

    protected $casts = [
        'is_reviewed' => 'boolean',
        'is_advertised' => 'boolean',
        'is_free_shipped' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid(); // Generate UUID for 'id' when creating
        });
    }

    public function merchants()
    {
        return $this->belongsToMany(Merchant::class, 'rel_merchant_subscription', 'package_id', 'merchant_id')
                ->withPivot('subscribe_at', 'valid_until', 'is_trial', 'payment_file_url', 'is_valid')
                ->withTimestamps();
    }
}
