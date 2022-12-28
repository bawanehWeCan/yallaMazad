<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model
{
    use HasFactory;
    protected $guarded=[];

    protected $table = 'category_user_pivot';


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
