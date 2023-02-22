<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Hash;
// use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
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
        'type',
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
            $file->move(public_path('img/profiles/'), $filename);
            $this->attributes['image'] =  'img/profiles/'.$filename;
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

    public function bids(){
        return $this->hasMany(Bid::class);
    }

    public function favorites(){

        return $this->belongsToMany(Advertisement::class,'favorites','user_id','advertisement_id')->orderBy('id', 'DESC')->paginate(10);
    }

    public function hascategories(){
        return $this->belongsToMany(Category::class,'user_categories','user_id','category_id');
    }

    public function notifications(){
        return $this->hasMany(Notification::class);
    }
    public function reportSender(){
        return $this->hasMany(Report::class,'sender_id');
    }
    public function reports(){
        return $this->hasMany(Report::class,'user_id');
    }

    protected static function booted()
    {
        static::deleted(function ($user) {
            if($user->subscriptions) $user->subscriptions()->delete();
            if($user->advertisements) $user->advertisements()->delete();
            if($user->badges) $user->badges()->delete();
            if($user->notifications) $user->notifications()->delete();
            if($user->bids) $user->bids()->delete();
            if($user->reportSender) $user->reportSender()->delete();
            if($user->reports) $user->reports()->delete();
            if($user->favorites()->count()>0) $user->favorites()->detach();
            if($user->hascategories()->count()>0) $user->hascategories()->detach();
            if ($user->image&&\Illuminate\Support\Facades\File::exists($user->image)) {
                unlink($user->image);
            }
        });
    }

}
