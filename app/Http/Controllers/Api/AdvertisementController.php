<?php

namespace App\Http\Controllers\Api;

use App\Models\Advertisement;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\AdvertisementRequest;
use App\Http\Resources\AdvertisementResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class AdvertisementController extends ApiController
{
    public function __construct()
    {
        $this->resource = AdvertisementResource::class;
        $this->model = app(Advertisement::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){

        $advertisement = $this->repositry->save($request->except('images'));


        foreach ($request->images as $image) {

            $im = new Image();
            $im->image = $image;
            $im->advertisement_id = $advertisement->id;

            $im->save();

        }

        return $this->returnData('data', new AdvertisementResource($advertisement), __('Succesfully'));

    }

    public function edit($id,Request $request){


        return $this->update($id,$request->all());

    }

    public function lookfor(Request $request){

        return $this->search('name',$request->value);

    }


    public function getPopularAdvertisings(){

        return $this->listWithOrder('views','DESC');
     }


}
