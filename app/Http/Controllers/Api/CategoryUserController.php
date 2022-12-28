<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Resources\CategoryUserResource;
use App\Models\UserCategory;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class CategoryUserController extends ApiController
{

    public function __construct()
    {
        $this->resource = CategoryUserResource::class;
        $this->model = app(UserCategory::class);
        $this->repositry = new Repository($this->model);
    }

    public function addCategoriesToUser(Request $request)
    {

        foreach ($request->cats as $cat) {

            // dd($request->cats);
            $user_category = new UserCategory();
            $user_category->category_id = $cat;
            $user_category->user_id = $request->user_id;
            $user_category->save();
        }

        return $this->returnSuccessMessage(__('Added succesfully!'));

    }

}
