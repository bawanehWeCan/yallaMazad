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
use App\Models\Advertisement;

class FavoriteController extends ApiController
{
    public function __construct()
    {
        $this->resource = FavoriteResource::class;
        $this->model = app(Favorite::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){

        $model = Favorite::where('advertisement_id',$request->advertisement_id)->where('user_id',$request->user_id)->first();
        if($model){
            return $this->returnError(__('Sorry! Advertisement already exist !'));
        }
        return $this->store( $request->all() );
    }

    public function deletebyID( $advertisement_id, $user_id ){


        $model = Favorite::where('advertisement_id',$advertisement_id)->where('user_id',$user_id)->first();

        if (!$model) {
            return $this->returnError(__('Sorry! Failed to get !'));
        }

        $model->delete();



        return $this->returnSuccessMessage(__('Delete succesfully!'));


    }




    public function getFavoritesAdv($user_id)
    {

        // $favorites = User::find($user_id)->favorites;
        $favorites = Favorite::where('user_id',$user_id)->paginate(10) ;
        return $this->returnData('data',  AdvertisementResource::collection( $favorites ), __('Get  succesfully'));

    }

}
