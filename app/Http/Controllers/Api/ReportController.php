<?php

namespace App\Http\Controllers\Api;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\ReportRequest;
use App\Http\Resources\ReportResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class ReportController extends ApiController
{

    public function __construct()
    {
        $this->resource = ReportResource::class;
        $this->model = app(Report::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){

        $adv = Report::where('advertisement_id',$request->advertisement_id)->where('sender_id',$request->sender_id)->first();
        $user = Report::where('user_id',$request->user_id)->where('sender_id',$request->sender_id)->first();
        if($adv || $user){
            return $this->returnError(__('Sorry! You cannot report again !'));
        }

          // $advertisment=Advertisement::find($request->advertisement_id);
        // $reciever=User::find($request->user_id);

            return $this->store( $request->all() );


    }


    public function edit($id,Request $request){


        return $this->update($id,$request->all());

    }

}
