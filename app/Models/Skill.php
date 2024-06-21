<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Skill extends Model
{
    use HasFactory;

    protected $primaryKey = 'id'; // Ensure the primary key is 'id'

    protected $keyType = 'string'; // Specify the key type as 'string'

    public $incrementing = false; // Disable auto-incrementing for UUIDs

    protected $fillable = [
        'id', 'name', 'description', 'merchant_type', 'skill_type',
    ];

    // Override boot method to set UUID on creating new model instance
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid(); // Generate UUID for 'id' when creating
        });
    }

    // Your other relationships or methods
}
