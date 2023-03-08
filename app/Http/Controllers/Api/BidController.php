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

            $ads = Advertisement::find($request->advertisement_id);

            if( $ads->status == 'complete' ){
                return $this->returnError(__('sorry !! this ads has been end'));
            }

            if( \Carbon\Carbon::now()->gt( new \Carbon\Carbon( $ads->end_date ) ) ){
                return $this->returnError(__('sorry !! this ads has been end'));
            }


            $counts=Adv_User::where('user_id',$request->user_id)->where('advertisement_id',"!=",$request->advertisement_id)->get()->count();
            $user=User::find($request->user_id);
            if( $request->price > $ads->high_price ){

                $all =( $user->subscriptions?->count()== 0)?1: $user->subscriptions?->last()->plan?->number_of_auction;
                // echo 'count:' . $counts .'\n';
                // echo 'all:' . $all .'\n';
                // return;
                if($counts==0 || $counts < $all){


                    $user = User::find( $request->user_id );

                    $num = (int)$ads->number_of_bids + 1;
                    $ads->number_of_bids = $num;
                    // if(\Carbon\Carbon::create(Advertisement::find(1)->end_date)->diffInMinutes(\Carbon\Carbon::now())<=10){
                    //     $ads->end_date = \Carbon\Carbon::create($ads->end_date)->addMinutes(10);
                    // }
                    $ads->save();

                    $model = $this->repositry->save($request->all());



                    if ($model) {

                        $bid = app('firebase.firestore')->database()->collection('auctions')->document($request->advertisement_id)->collection('biddings')->document($model->id); // we will replace this value with auction id

                        // insert for decument
                        $bid->set([
                            'amount' => $request->price,
                            'image' => (string)$user->image,
                            'name' => (string)$user->name,
                            'user_id' => (integer)$user->id,
                            'device_token' => $user->device_token,
                        ]);

                        $fav = Favorite::where('advertisement_id',$request->advertisement_id)->where('user_id',$request->user_id)->first();

                        if(!$fav)
                        {
                        $fav = new Favorite();
                        $fav->user_id = $request->user_id;
                        $fav->advertisement_id = $request->advertisement_id;
                        $fav->save();
                        }

                        $adv_user = new Adv_User();
                        $adv_user->user_id = $request->user_id;
                        $adv_user->advertisement_id = $request->advertisement_id;
                        $adv_user->save();

                        $ads->update(['high_price' => $request->price ]);

               // اشعار لصاحب المزاد
                    //     $user = User::find($ads->user_id);

                    //     $token = $user->device_token;
                    //     if (!empty($token))
                    //    {
                    //         $this->send('تم إضافة مزايدة جديدة على إعلانك', 'مرحبا ', $token);

                    //         $note= new Notification();
                    //         $note->content = 'تم إضافة مزايدة جديدة على إعلانك';
                    //         $note->user_id = $ads->user_id;
                    //         $note->save();

                    //    }
                         //   اشعار لجميع المشاركين في المزاد عدا المزايد

                        //     $user_ids = Bid::where('advertisement_id',$request->advertisement_id)->where('user_id',"!=",$request->user_id)->pluck('user_id')->all();
                        //     $FcmToken = User::whereIn('id',$user_ids)->whereNotNull('device_token')
                        //     ->pluck('device_token')->all();

                        //  $this->send('تم إضافة مزايدة جديدة', 'مرحبا ', $FcmToken, true);


                            return $this->returnData('data', new $this->resource($model), __('Succesfully'));


                    }

                }

                else{

                    return $this->returnError(__('Please, sub plan first'));
                }
            }else{
                //hight price response
                return $this->returnSuccessMessage(__('The price has already been entered. Please enter a higher price'));
            }

            // return $this->returnError(__('Sorry! Failed to create !'));

        } catch (\Exception $ex) {
            // return $th;
            // dd($ex);
            return $this->returnError(__('Sorry! Failed to create !'));
        }
    }


    public function directSale(Request $request){

        try {

            $ads = Advertisement::find($request->advertisement_id);

            if( $ads->status == 'complete' ){
                return $this->returnError(__('sorry !! this ads has been end'));
            }

            if( \Carbon\Carbon::now()->gt( new \Carbon\Carbon( $ads->end_date ) ) ){
                return $this->returnError(__('sorry !! this ads has been end'));
            }

            $user = User::find( $request->user_id );

            $num = (int)$ads->number_of_bids + 1;
            $ads->number_of_bids = $num;
            // if(\Carbon\Carbon::create($ads->end_date)->diffInMinutes(\Carbon\Carbon::now())<=10){
            //     $ads->end_date = \Carbon\Carbon::create($ads->end_date)->addMinutes(10);
            // }
            $ads->save();

            $model = $this->repositry->save($request->all());

            if ($model) {

                $bid = app('firebase.firestore')->database()->collection('auctions')->document($request->advertisement_id)->collection('biddings')->document($model->id); // we will replace this value with auction id

                // insert for decument
                $bid->set([
                    'amount' => $request->price,
                    'image' => (string)$user->image,
                    'name' => (string)$user->name,
                    'user_id' => (integer)$user->id

                ]);

                $ads->update(['status' => "complete"]);

                $fav = new Favorite();
                $fav->user_id = $request->user_id;
                $fav->advertisement_id = $request->advertisement_id;
                $fav->save();

                 //  اشعار لصاحب المزاد
                        // $user = User::find($ads->user_id);

                        // $token = $user->device_token;

                        //     $this->send('تم شراء المنتج بشكل مباشر', 'مرحبا ', $token);

                        //     $note= new Notification();
                        //     $note->content = 'تم شراء المنتج بشكل مباشر';
                        //     $note->user_id = $ads->user_id;
                        //     $note->save();

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
