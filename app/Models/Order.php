<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'service_id',
        'merchant_id',
        'merchant_user_id',
        'estimated_date',
        'user_id',
        'user_file_url',
        'comment_json',
        'meet_url',
        'order_status'
    ];

    protected $casts = [
        'comment_json' => 'array',
    ];

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
