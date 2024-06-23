<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'price',
        'service_id',
        'merchant_id',
        'merchant_user_id',
        'estimated_date',
        'language_source',
        'language_destination',
        'user_id',
        'user_file_url',
        'comment_json',
        'meet_url',
        'order_status',
        'result_file_url',
        'payment_file_url'
    ];

    protected $casts = [
        'comment_json' => 'array',
        'language_source' => 'array',
        'language_destination' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid(); // Generate UUID for 'id' when creating
        });

    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function merchantUser()
    {
        return $this->belongsTo(User::class, 'merchant_user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
