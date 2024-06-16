<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelMerchantService extends Model
{
    use HasFactory;
    public $timestamps = true; // Ensure timestamps are enabled

    protected $fillable = [
        'merchant_id',
        'service_id'
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
