<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;


    protected $guarded=[];

    public function setImageAttribute($value){
        if ($value){
            $file = $value;
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename =time().mt_rand(1000,9999).'.'.$extension;
            $file->move(public_path('img/badges/'), $filename);
            $this->attributes['image'] =  'img/badges/'.$filename;
        }
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    protected static function booted()
    {
        static::deleted(function ($badge) {
            if ($badge->image&&\Illuminate\Support\Facades\File::exists($badge->image)) {
                unlink($badge->image);
            }
        });
    }
}
