<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'title',
        'logo',
        'bank',
        'bank_account',
        'bank_account_name',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid(); // Generate UUID for 'id' when creating
        });
    }
}
