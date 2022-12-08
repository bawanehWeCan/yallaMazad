<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
// use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    // use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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


    public function subscriptions(){
        return $this->hasMany(Subscription::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    public function advertisements(){
        return $this->hasMany(Advertisement::class);
    }

    public function badges(){
        return $this->hasMany(badges::class);
    }

    public function favorites(){
        return $this->belongsToMany(Advertisement::class,'favorites','user_id','advertisement_id');
    }
}
