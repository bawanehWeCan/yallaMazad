<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Introduction extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded=[];
    public $translatable = ['title','body'];


}
