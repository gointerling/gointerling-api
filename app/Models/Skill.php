<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'merchant_type',
        'skill_type'
    ];

    public function merchants()
    {
        return $this->belongsToMany(Merchant::class, 'rel_merchant_skill');
    }
}
