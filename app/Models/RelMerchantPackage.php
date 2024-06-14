<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelMerchantPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'package_id',
        'subscribe_at',
        'valid_until'
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
