<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'prescreen',
        'decline_response',
        'stripe_customer',
        'instagram_url',
        'tiktok_url',
        'twitter_url',
        'facebook_url',
        'subscription_active',
        'terms',
    ];

    protected $attributes = [
        'decline_response' => "Hey! Thanks for reaching out for an appointment. Unfortunately, I won't be scheduling you at this point in time. I appreciate your interest in my work and hope you'll submit again in the future!"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeTags() {
        return $this->hasMany(Tag::class)->where('user_id', auth()->id());
    }

    public function scopeAvailDates() {
        $dates = [];
        return $dates;
    }
}
