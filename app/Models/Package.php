<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Package extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'benefits',
        'rule'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid(); // Generate UUID for 'id' when creating
        });
    }

    protected $casts = [
        'benefits' => 'array',
        'rule' => 'array',
    ];

    public function merchants()
    {
        return $this->belongsToMany(Merchant::class, 'rel_merchant_package')
            ->withPivot('subscribe_at', 'valid_until');
    }
}
