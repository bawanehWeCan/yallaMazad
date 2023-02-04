<?php

namespace App\Http\Controllers\Api;

use App\Models\Bid;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\BidRequest;
use App\Http\Resources\BidResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Models\Advertisement;
use App\Models\User;

class BidController extends ApiController
{
    public function __construct()
    {
        $this->resource = BidResource::class;
        $this->model = app(Bid::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save(Request $request)
    {
        try {

            $ads = Advertisement::find($request->advertisement_id);

            $user = User::find( $request->user_id );

            $num = (int)$ads->number_of_bids + 1;
            $ads->number_of_bids = $num;
            $ads->save();

            $model = $this->repositry->save($request->all());

            if ($model) {
// app('firebase.firestore') => firebase class
//->database()->collection('auctions') اسم التيبل الي بدنا نشتغل عليه
// ->document($request->advertisement_id) ريكورد بايدي الاعلان
// )->collection('biddings') تيبل جوا الريكورد باسم مزايدات
//->document($model->id) ريكورد بايدي المزايده

                $bid = app('firebase.firestore')->database()->collection('auctions')->document($request->advertisement_id)->collection('biddings')->document($model->id); // we will replace this value with auction id

                // insert for decument
                $bid->set([
                    'amount' => (int)$request->price,
                    'image' => (string)$user->image,
                    'name' => (string)$user->name

                ]);
                return $this->returnData('data', new $this->resource($model), __('Succesfully'));
            }

            return $this->returnError(__('Sorry! Failed to create !'));

        } catch (\Throwable $th) {
            return $this->returnError(__('Sorry! Failed to create !'));
        }
    }

    public function edit($id, Request $request)
    {


        return $this->update($id, $request->all());
    }
}
