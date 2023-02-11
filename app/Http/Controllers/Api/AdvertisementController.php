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

    public function save(Request $request)
    {
        try {
            $request['user_id'] = Auth::user()->id; // cuze mobile
            $advertisement = $this->repositry->save($request->except('images'));

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

    public function edit($id, Request $request)
    {


        return $this->update($id, $request->all());
    }

    public function view($id)
    {
        $model = $this->repositry->getByID($id);

        $views = (int)$model->views + 1;

        $model->update([
            'views' => $views
        ]);

        if ($model) {
            return $this->returnData('data', new $this->resource($model), __('Get  succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get !'));
    }

    public function lookfor(Request $request)
    {
        $advertisements =  $this->model->where('name','like',"%$request->value%")->where('status','approve')->paginate(10);
        $this->returnData('data', AdvertisementResource::collection($advertisement), __('Succesfully'));
    }


    public function getPopularAdvertisings()
    {

        return $this->listWithOrder('views', 'DESC');
    }

    public function pagination($length = 10)
    {
        $advertisements =  $this->model->where('status','approve')->paginate($length);
        $this->returnData('data', AdvertisementResource::collection($advertisement), __('Succesfully'));
    }
}
