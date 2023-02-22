<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    protected $guarded=['Images'];

    public function setImageAttribute($value){
        if ($value){
            $file = $value;
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename =time().mt_rand(1000,9999).'.'.$extension;
            $file->move(public_path('img/ads/'), $filename);
            $this->attributes['image'] =  'img/ads/'.$filename;
        }
    }

    public function advertisement(){
        return $this->belongsTo(Advertisement::class);
    }

    protected static function booted()
    {
        static::deleted(function ($image) {

            if ($image->image&&\Illuminate\Support\Facades\File::exists($image->image)) {
                unlink($image->image);
            }
        });
    }
}
