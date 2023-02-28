<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Notification;
use App\Models\Advertisement;
use App\Repositories\Repository;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use App\Http\Resources\BadgeResource;
use App\Http\Resources\NotificationResource;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\AdvertisementResource;
use App\Models\Badge;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->resource = UserResource::class;
        $this->model = app(User::class);
        $this->repositry =  new UserRepository($this->model);
    }

    public function save( UserRequest $request ){
        try {
            DB::beginTransaction();
            $user = $this->repositry->save($request);
            // if ($request->has('image')) {
            //     $this->repositry->insertImage($request->image,$user);
            // }
            DB::commit();
            return $this->returnData('user', new $this->resource($user),'User created Successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
            return $this->returnError('Sorry! Failed in creating user');
        }
    }
    public function edit( $id,ProfileUpdateRequest $request ){
        try {
            DB::beginTransaction();
            $user = $this->model->find($id);
            // check unique email except this user
            if (isset($request->email)) {
                $check = User::where('email', $request->email)
                    ->first();

                if ($check) {

                    return $this->returnError('The email address is already used!');
                }
            }




            $this->repositry->edit($request,$user);

            // if ($request->has('image') && $user->has('image')) {
            //     $image = $this->repositry->insertImage($request->image,$user,true);
            // }elseif ($request->has('image')) {
            //     $image = $this->repositry->insertImage($request->image,$user);
            // }

            DB::commit();
            // unset($user->image);
            return $this->returnData('user', new $this->resource($user), 'User updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('Sorry! Failed in updating user');
        }
    }


    public function myFavorites()
    {

        $favorites = Auth::user()->favorites();
        return $this->returnData('data',  AdvertisementResource::collection( $favorites ), __('Get  succesfully'));

    }


    public function myAdvertisement()
    {

        // $advertisements = Auth::user()->advertisements;
        $advertisements = Advertisement::where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->paginate(10) ;
        return $this->returnData('data',  AdvertisementResource::collection( $advertisements ), __('Get  succesfully'));

    }

    public function addBadge( Request $request ){
        Badge::create( $request->all() );
        $user = User::find( $request->user_id );
        return $this->returnData('user', new $this->resource($user), 'User updated successfully');
    }

    public function deleteBadge( $id ){
        $model = Badge::find($id);

        if (!$model) {
            return $this->returnError(__('Sorry! Failed to get !'));
        }

        $model->delete();
        return $this->returnSuccessMessage(__('Delete succesfully!'));
    }

    public function myBadges()
    {

        // $advertisements = Auth::user()->advertisements;
        $badges = Badge::where('user_id',Auth::user()->id)->paginate(10) ;
        return $this->returnData('data',  BadgeResource::collection( $badges ), __('Get  succesfully'));

    }

    // public function myNotifications()
    // {

    //     // $advertisements = Auth::user()->advertisements;
    //     $notifications = Notification::where('user_id',Auth::user()->id)->paginate(10) ;
    //     return $this->returnData('data',  NotificationResource::collection( $notifications ), __('Get  succesfully'));

    // }


    public function myNotifications(){



        foreach (Notification::where('user_id',Auth::user()->id)->paginate(10) as $not) {


                $not->update([
                    'is_read' => 1
                ]);

            }


        return $this->returnData('data', NotificationResource::collection(Notification::where('user_id',Auth::user()->id)->paginate(10)), __('Get  succesfully'));
    }



}
