<?php

namespace App\Http\Controllers\Api;

use App\Models\Image;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\ImageRequest;
use App\Http\Resources\ImageResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class ImageController extends ApiController
{
    public function __construct()
    {
        $this->resource = ImageResource::class;
        $this->model = app(Image::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){
        return $this->store( $request->all() );
    }

    public function edit($id,Request $request){


        return $this->update($id,$request->all());

    }

    public function addImageToAdvertising( Request $request, $advertisement_id ){


        foreach ($request->images as $image) {

            $im = new Image();
            $im->image = $image;
            $im->advertisement_id = $advertisement_id;

            $im->save();

        }

        $advertisement = Advertisement::find( $advertisement_id );

        $advertisement->images()->save( $im);


        return $this->returnSuccessMessage(__('Added succesfully!'));
    }
}
