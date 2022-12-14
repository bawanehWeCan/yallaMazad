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
        'image',
        'phone'
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

    public function setImageAttribute($value){
        if ($value){
            $file = $value;
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename =time().mt_rand(1000,9999).'.'.$extension;
            $file->move(public_path('img/'), $filename);
            $this->attributes['image'] =  'img/'.$filename;
        }
    }


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
        return $this->hasMany(Badge::class);
    }

    public function favorites(){
        return $this->belongsToMany(Advertisement::class,'favorites','user_id','advertisement_id')->paginate(10);
    }

    public function hascategories(){
        return $this->belongsToMany(Category::class,'user_categories','user_id','category_id');
    }

    public function notifications(){
        return $this->hasMany(Notification::class);
    }
}
