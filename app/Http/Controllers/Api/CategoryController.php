<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Resources\AdvertisementResource;
use App\Http\Resources\CategoryResource;
use App\Models\Advertisement;
use App\Models\Category;
use App\Repositories\Repository;
use Illuminate\Http\Request;
use DB;

class CategoryController extends ApiController
{
    public function __construct()
    {
        $this->resource = CategoryResource::class;
        $this->model = app(Category::class);
        $this->repositry = new Repository($this->model);
    }

    public function save(Request $request)
    {
        return $this->store($request->all());
    }

    public function edit($id, Request $request)
    {

        return $this->update($id, $request->all());

    }

    // 'pending','approve','rejected','complete','current'

    public function getAdvByCategory($category_id)
    {

        // $category = Category::find( $category_id );

          $advertisements = Advertisement::where('category_id',$category_id)->
          where('status','!=',"Rejected")
          ->where('status','!=',"pending")
           ->orderByRaw(DB::raw("
            CASE status WHEN 'current' THEN 1
                   WHEN 'approve' THEN 2
                   WHEN 'complete' THEN 3
                   END ASC"))
                   ->paginate(10) ;

        return $this->returnData('data', AdvertisementResource::collection($advertisements), __('Get  succesfully'));

    }

    // public function getAdvByCategory($category_id)
    // {

    //     // $category = Category::find( $category_id );

    //     $advertisements = Advertisement::where('category_id', $category_id)
    //         ->where('status', 'approve')
    //         ->orWhere('status', 'complete')
    //         ->orderBy('status', 'asc')
    //         ->orderBy('start_date', 'asc')

    //         ->paginate(10);

    //     return $this->returnData('data', AdvertisementResource::collection($advertisements), __('Get  succesfully'));

    // }

    // public function getAdvByCategory($category_id){

    //     // $category = Category::find( $category_id );

    //     $advertisements = Advertisement::where('category_id',$category_id)->
    //     orderByRaw(DB::raw("
    //         CASE status WHEN 'now' THEN 1
    //                WHEN 'next' THEN 2
    //                WHEN 'finished' THEN 3
    //                ELSE 4 END ASC"))->paginate(10) ;
    //     return $this->returnData('data',  AdvertisementResource::collection( $advertisements ), __('Get  succesfully'));

    // }

    // public function getAdvByCategory($category_id){

    //     $date = today()->format('Y-m-d H:i:s');
    //     $data = array();

    //     $data['now']=AdvertisementResource::collection(Advertisement::where('category_id',$category_id)->where('status','approve')->where('start_date', '>=', $date)->where('end_date', '<=', $date)->paginate(10) );
    //     $data['next']=AdvertisementResource::collection(Advertisement::where('category_id',$category_id)->where('status','approve')->where('start_date', '>', $date)->paginate(10) );;
    //     $data[ 'finished']= AdvertisementResource::collection(Advertisement::where('category_id',$category_id)->where('status','approve')->where('end_date', '<', $date)->paginate(10) );
    //     return $this->returnData( 'data' ,  $data , __('Succesfully'));

    //    }

}
