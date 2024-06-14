<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelMerchantSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'skill_id'
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}
