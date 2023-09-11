<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscription;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\SubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\MySubscriptionResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Auth;

class SubscriptionController extends ApiController
{
    public function __construct()
    {
        $this->resource = SubscriptionResource::class;
        $this->model = app(Subscription::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){


        $plan=Plan::find($request->plan_id)->number_of_auction;


        $user=User::find($request->user_id);

        $user->number_of_advs = $plan ;
        $user->save();

        return $this->store( $request->all() );
    }

    public function edit($id,Request $request){


        return $this->update($id,$request->all());

    }

    public function mySubscription($user_id){


       // $user_id = Auth::user()->id;
        // $user=User::find($user_id)->first();
        $subscriptions = Subscription::where('user_id',$user_id)->paginate(10) ;
        return $this->returnData('data',  MySubscriptionResource::collection( $subscriptions ), __('Get  succesfully'));

    }


    public function viewSubs($order_number)
    {
        $model = Subscription::where('order_number',$order_number)->first();

        if ($model) {
            return $this->returnData('data', new $this->resource( $model ), __('Get  succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get !'));
    }


}
