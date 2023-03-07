<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Repositories\Repository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Http\Requests\AdvertisementRequest;
use App\Http\Resources\AdvertisementResource;

class AdvertisementController extends ApiController
{
    public function __construct()
    {
        $this->resource = AdvertisementResource::class;
        $this->model = app(Advertisement::class);
        $this->repositry =  new Repository($this->model);
    }


    public function advs(){


        $date = today()->format('Y-m-d H:i:s');
        $ads = Advertisement::where('status','approve')->paginate(10);
        foreach ($ads as $ad) {


            if($ad->start_date <= $date && $ad->end_date >= $date){

                $ad->status = 'current';
                $ad->save();

            }

            }

        return $this->returnData('data', $this->resource::collection($ads), __('Get  succesfully'));
    }

    public function save(Request $request)
    {
        try {
            $request['user_id'] = Auth::user()->id; // cuze mobile
            $advertisement = $this->repositry->save($request->except('images'));


            if ($advertisement) {

                $bid = app('firebase.firestore')->database()->collection('auctions')->document($advertisement->id)->collection('info')->document($advertisement->id); // we will replace this value with auction id

                // insert for decument
                $bid->set([
                    'status' => $request->status,
                    'start_date' => $request->start_date,
                    'start_date' => $request->start_date

                ]);
            }

            // // $ads    = Advertisement::find($advertisement->id);
            // $request['start_date']   =  $advertisement->created_at;
            // $request['end_date']    =  $advertisement->updated_at;
            // $request['price_one']    =  50;
            // $request['price_two']    =  70;
            // $request['price_three']    =  90;




            // $this->repositry->edit($advertisement->id, $request->except('images', 'id'));

            if (isset($request->images)) {
                foreach ($request->images as $image) {

                    $im = new Image();
                    $im->image = $image;
                    $im->advertisement_id = $advertisement->id;

                    $im->save();
                }
            }


            return $this->returnData('data', new AdvertisementResource($advertisement), __('Succesfully'));
        } catch (Exception $ex) {
            //throw $th;

            dd($ex);
        }
    }


    public function edit($id,Request $request){

        $model = $this->repositry->getByID($id);
        if ($model) {
            $model = $this->repositry->edit( $id,$request->all() );

            $bid = app('firebase.firestore')->database()->collection('auctions')->document($id)->collection('info')->document($model->id); // we will replace this value with auction id

            // insert for decument
            $bid->set([
                'status' => $request->status,
                'start_date' => $request->start_date,
                'start_date' => $request->start_date

            ]);
            return $this->returnData('data', new $this->resource( $model ), __('Updated succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get !'));

    }




    // public function edit($id, Request $request)
    // {


    //     return $this->update($id, $request->all());
    // }

    public function view($id)
    {
        $model = $this->repositry->getByID($id);

        $views = (int)$model->views + 1;

        $model->update([
            'views' => $views
        ]);

        $date = today()->format('Y-m-d H:i:s');
        if($model->status == 'approve' && $model->start_date <= $date && $model->end_date >= $date){

            $model->update([
                'status' => "current"
            ]);

        }

        if ($model) {
            return $this->returnData('data', new $this->resource($model), __('Get  succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get !'));
    }

    public function lookfor(Request $request)
    {
        $advertisements =  $this->model->where('name','like',"%$request->value%")->where('status','approve')->paginate(10);
        return $this->returnData('data', AdvertisementResource::collection($advertisements), __('Succesfully'));
    }


    public function getPopularAdvertisings()
    {

        $advertisements =  $this->model->orderByDesc('views')->where('status','approve')->paginate(10);
        return $this->returnData('data', AdvertisementResource::collection($advertisements), __('Succesfully'));

    }

//it is same the previous but without pagination
    public function popularAdvertisings()
    {

        $advertisements =  $this->model->orderByDesc('views')->where('status','approve')->get();
        return $this->returnData('data', AdvertisementResource::collection($advertisements), __('Succesfully'));

    }

    public function pagination($length = 10)
    {
        $advertisements =  $this->model->where('status','approve')->paginate($length);
        return $this->returnData('data', AdvertisementResource::collection($advertisements), __('Succesfully'));
    }
}
