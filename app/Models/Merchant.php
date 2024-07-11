<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Merchant extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'type',
        'bank_id',
        'bank',
        'bank_account',
        'cv_url',
        'portfolios',
        'certificates',
        'status',
        'rating',
        'recomended_count',
        'is_first_time',
    ];

    protected $casts = [
        'portfolios' => 'array',
        'certificates' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'rel_merchant_user');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'rel_merchant_skill');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'rel_merchant_service');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function subscriptionPackages()
    {
        return $this->belongsToMany(SubscriptionPackage::class, 'rel_merchant_subscription', 'merchant_id', 'package_id')
                    ->withPivot('subscribe_at', 'valid_until', 'is_trial', 'payment_file_url', 'is_valid')
                    ->withTimestamps();
    }

}
