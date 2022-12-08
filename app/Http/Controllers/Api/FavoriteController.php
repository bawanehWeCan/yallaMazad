<?php

namespace App\Http\Controllers\Api;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\FavoriteRequest;
use App\Http\Resources\FavoriteResource;
use App\Http\Resources\myFavoriteResource;
use App\Http\Resources\AdvertisementResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class FavoriteController extends ApiController
{
    public function __construct()
    {
        $this->resource = FavoriteResource::class;
        $this->model = app(Favorite::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){
        return $this->store( $request->all() );
    }

    public function edit($id,Request $request){


        return $this->update($id,$request->all());

    }

    public function getFavoritesAdv($user_id)
    {

        $favorites = User::find($user_id)->favorites;
        return $this->returnData('data',  AdvertisementResource::collection( $favorites ), __('Get  succesfully'));

    }

}
