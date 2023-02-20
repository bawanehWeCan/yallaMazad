<?php

namespace App\Http\Controllers\Api;

use App\Models\Bid;
use App\Models\Adv_User;
use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\BidRequest;
use App\Http\Resources\BidResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Models\Advertisement;
use App\Models\User;
use Carbon\Carbon;

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

            $counts=Adv_User::where('user_id',$request->user_id)->where('advertisement_id',$request->advertisement_id)->get()->count();
            if($counts==0){

                $ads = Advertisement::find($request->advertisement_id);

                $user = User::find( $request->user_id );

                $num = (int)$ads->number_of_bids + 1;
                $ads->number_of_bids = $num;
                if(\Carbon\Carbon::create(Advertisement::find(1)->end_date)->diffInMinutes(\Carbon\Carbon::now())<=10){
                    $ads->end_date = \Carbon\Carbon::create($ads->end_date)->addMinutes(10);
                }
                $ads->save();

                $model = $this->repositry->save($request->all());

                if ($model) {

                    $bid = app('firebase.firestore')->database()->collection('auctions')->document($request->advertisement_id)->collection('biddings')->document($model->id); // we will replace this value with auction id

                    // insert for decument
                    $bid->set([
                        'amount' => $request->price,
                        'image' => (string)$user->image,
                        'name' => (string)$user->name

                    ]);

                 $fav = new Favorite();
                 $fav->user_id = $request->user_id;
                 $fav->advertisement_id = $request->advertisement_id;
                 $fav->save();

                 $adv_user = new Adv_User();
                 $adv_user->user_id = $request->user_id;
                 $adv_user->advertisement_id = $request->advertisement_id;
                 $adv_user->save();

                    return $this->returnData('data', new $this->resource($model), __('Succesfully'));


            }

            }

            elseif($counts>0){

                return $this->returnSuccessMessage(__('Please, sub plan first'));
            }

            // return $this->returnError(__('Sorry! Failed to create !'));

        } catch (\Throwable $th) {
            // return $th;
            return $this->returnError(__('Sorry! Failed to create !'));
        }
    }


    public function directSale(Request $request){

        try {

            $ads = Advertisement::find($request->advertisement_id);

            $user = User::find( $request->user_id );

            $num = (int)$ads->number_of_bids + 1;
            $ads->number_of_bids = $num;
            if(\Carbon\Carbon::create($ads->end_date)->diffInMinutes(\Carbon\Carbon::now())<=10){
                $ads->end_date = \Carbon\Carbon::create($ads->end_date)->addMinutes(10);
            }
            $ads->save();

            $model = $this->repositry->save($request->all());

            if ($model) {

                $bid = app('firebase.firestore')->database()->collection('auctions')->document($request->advertisement_id)->collection('biddings')->document($model->id); // we will replace this value with auction id

                // insert for decument
                $bid->set([
                    'amount' => $request->price,
                    'image' => (string)$user->image,
                    'name' => (string)$user->name

                ]);

                $ads->update(['status' => "complete"]);

                $fav = new Favorite();
                $fav->user_id = $request->user_id;
                $fav->advertisement_id = $request->advertisement_id;
                $fav->save();

                return $this->returnData('data', new $this->resource($model), __('Succesfully'));

            }

            // return $this->returnError(__('Sorry! Failed to create !'));

        } catch (\Throwable $th) {
             return $th;
            return $this->returnError(__('Sorry! Failed to create !'));
        }

    }


    public function edit($id, Request $request)
    {


        return $this->update($id, $request->all());
    }


    public function getTime()
{
    $mytime =Carbon::now();
     return $this->returnSuccessMessage($mytime->toDateTimeString());


}


}
