<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    protected $guarded=[];



    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function images(){
        return $this->hasMany(Image::class);
    }
    public function getImageAttribute(){
        return (!empty( $this?->images->first()?->image))? $this->images->first()->image :'';
    }

    public function buyer(){
        return $this->hasOne(Adv_User::class);
    }
    public function bids(){
        return $this->hasMany(Bid::class);
    }

    protected static function booted()
    {
        static::deleted(function ($user) {
            if($user->bids) $user->bids()->delete();
            if($user->buyer) $user->buyer()->delete();
            if($user->images) $user->images()->delete();
        });
    }
}
