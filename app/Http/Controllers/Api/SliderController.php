<?php

namespace App\Http\Controllers\Api;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\SliderRequest;
use App\Http\Resources\SliderResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class SliderController extends ApiController
{
    public function __construct()
    {
        $this->resource = SliderResource::class;
        $this->model = app(Slider::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){
        return $this->store( $request->all() );
    }

    public function edit($id,Request $request){


        return $this->update($id,$request->all());

    }
}
