<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\AdvertisementResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class CategoryController extends ApiController
{
    public function __construct()
    {
        $this->resource = CategoryResource::class;
        $this->model = app(Category::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){
        return $this->store( $request->all() );
    }

    public function edit($id,Request $request){


        return $this->update($id,$request->all());

    }


    public function getAdvByCategory($category_id){

        // $category = Category::find( $category_id );

        $advertisements = Advertisement::where('category_id',$category_id)->paginate(10) ;
        return $this->returnData('data',  AdvertisementResource::collection( $advertisements ), __('Get  succesfully'));

    }
}
