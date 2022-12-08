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

class BidController extends ApiController
{
    public function __construct()
    {
        $this->resource = BidResource::class;
        $this->model = app(Bid::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){

        $ads = Advertisement::find( $request->advertisement_id );

        $num = (int)$ads->number_of_bids + 1 ;
        $ads->number_of_bids = $num;
        $ads->save();

        return $this->store( $request->all() );
    }

    public function edit($id,Request $request){


        return $this->update($id,$request->all());

    }
}
