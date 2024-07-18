<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Socialite\Contracts\User as ProviderUser;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'id',
        'fullname',
        'email',
        'phone',
        'password',
        'photo',
        'address',
        'credential_id',
        'is_admin',
        'status',
        'personal_description',
        'main_skills',
        'additional_skills',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = [
        'password',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function createOrGetUser(ProviderUser $providerUser, $isCreateMerchant = false)
    {
        $user = User::where('email', $providerUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'fullname' => $providerUser->getName(),
                'email' => $providerUser->getEmail(),
                'password' => bcrypt('gointerlingX123'),
                // get hd profile picture
                'photo' => $providerUser->getAvatar(),
            ]);

            if ($isCreateMerchant) {
                $user->merchants()->create([
                    // 'type' => 'translator',
                    'bank_id' => NULL,
                    'bank' => NULL,
                    'bank_account' => NULL,
                    'cv_url' => NULL,
                    'portfolios' => NULL,
                    'certificates' => NULL,
                    'rating' => 0,
                    'recomended_count' => 0,
                    'status' => 'pending',
                ]);
            }
        }

        // return with all relation
        return $user->load('merchants');
    }

    // Define relationships
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function merchantReviews(): HasMany
    {
        return $this->hasMany(Review::class, 'merchant_user_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function merchantOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'merchant_user_id');
    }

    public function merchants(): BelongsToMany
    {
        return $this->belongsToMany(Merchant::class, 'rel_merchant_user');
    }
}
