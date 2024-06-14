<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

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
        'recomended_count'
    ];

    protected $casts = [
        'portfolios' => 'array',
        'certificates' => 'array',
    ];

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

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'rel_merchant_package')
            ->withPivot('subscribe_at', 'valid_until');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
