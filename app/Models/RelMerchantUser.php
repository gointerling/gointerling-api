<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelMerchantUser extends Model
{
    use HasFactory;
    public $timestamps = true; // Ensure timestamps are enabled

    protected $fillable = [
        'merchant_id',
        'user_id'
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
