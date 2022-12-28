<?php

namespace App\Http\Controllers\Api;

use App\Models\Tip;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\TipRequest;
use App\Http\Resources\TipResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class TipController extends ApiController
{
    public function __construct()
    {
        $this->resource = TipResource::class;
        $this->model = app(Tip::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){
        return $this->store( $request->all() );
    }

    public function edit($id,Request $request){


        return $this->update($id,$request->all());

    }
}
