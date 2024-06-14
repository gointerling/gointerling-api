<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'benefits',
        'rule'
    ];

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
