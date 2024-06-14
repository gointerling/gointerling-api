<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'review_message',
        'order_id',
        'merchant_user_id',
        'reviewer_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function merchantUser()
    {
        return $this->belongsTo(User::class, 'merchant_user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
