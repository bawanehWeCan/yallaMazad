<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use App\Models\Subscription;
use App\Models\EnterpriseCopone;
use App\Models\User;
use App\Models\Plan;
use App\Models\PromoCode;
use App\Models\UserCode;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\PromocodeResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class TransactionController extends ApiController
{
    public function __construct()
    {
        $this->resource = TransactionResource::class;
        $this->model = app(Transaction::class);
        $this->repositry =  new Repository($this->model);
    }




    public function save( Request $request )
    {

        $request['transaction_id'] = $request->id;
        // $trans=Transaction::create( $request->except('id'));


        $order_number=$request->order_number;
        $array=explode("_",$order_number);
        // echo $array[4];

        if($request->status=="success" && $request->type=="sale")
        {


                                    $sub=new Subscription();
                                    $sub->start_date = $array[2];
                                    $sub->end_date = $array[3];
                                    $sub->user_id = $array[0];
                                    $sub->plan_id = $array[1];
                                    $sub->order_number=$order_number;
                                    $sub->save();




                                    return $this->store( $request->except('id') );

                                }




               return $this->returnError('Soory ! The Operation failed!');


    }



    public function edit($id,Request $request){


        return $this->update($id,$request->all());

    }

    public function viewTrans($order_number)
    {
        $model = Transaction::where('order_number',$order_number)->first();

        if ($model) {
            return $this->returnData('data', new $this->resource( $model ), __('Get  succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get !'));
    }

    public function deleteTrans($order_number)
    {
        $model = Transaction::where('order_number',$order_number)->first();

        if (!$model) {
            return $this->returnError(__('Sorry! Failed to get !'));
        }


        $model->delete();
        return $this->returnSuccessMessage(__('Delete succesfully!'));
    }




}
