<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IntroductionController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\AdvertisementController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\BidController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryUserController;
use App\Http\Controllers\Api\TipController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('sociallogin', [AuthController::class, 'sociallogin']);

Route::post('/otp-check', [AuthController::class, 'check']);

Route::post('/password-otp', [AuthController::class, 'password']);

Route::post('change-password', [AuthController::class, 'changePassword']);

Route::post('delete-user/{id}', [AuthController::class, 'delete']);

Route::post('phone-update/{id}', [AuthController::class, 'updatePhone']);

Route::post('resend-otp/{id}', [AuthController::class, 'resendOTP']);


Route::post('add-badge', [UserController::class, 'addBadge']);
Route::get('delete-badge/{id}', [UserController::class, 'deleteBadge']);



//Auth
Route::middleware(['auth:api','changeLang'])->group(function () {

    Route::post('/user-update', [AuthController::class, 'updateProfile']);

    Route::post('/update-password', [AuthController::class, 'updatePassword']);

    Route::get('my-favorites', [UserController::class, 'myFavorites']);

    Route::get('my-advertisements', [UserController::class, 'myAdvertisement']);

    Route::get('my-badges', [UserController::class, 'myBadges']);

});

Route::middleware('changeLang')->group(function () {


    //Category
Route::get('categories', [CategoryController::class, 'pagination']);
Route::post('category-create', [CategoryController::class, 'save']);
Route::get('category/{id}', [CategoryController::class, 'view']);
Route::get('category/delete/{id}', [CategoryController::class, 'delete']);
Route::post('category/edit/{id}', [CategoryController::class, 'edit']);

Route::get( 'advertisement/get-advertisements/{category_id}', [ CategoryController::class, 'getAdvByCategory' ]);



//Introduction
Route::get('introductions', [IntroductionController::class, 'pagination']);
Route::post('introduction-create', [IntroductionController::class, 'save']);
Route::get('introduction/{id}', [IntroductionController::class, 'view']);
Route::get('introduction/delete/{id}', [IntroductionController::class, 'delete']);
Route::post('introduction/edit/{id}', [IntroductionController::class, 'edit']);




//Plan
Route::get('plans', [PlanController::class, 'pagination']);
Route::post('plan-create', [PlanController::class, 'save']);
Route::get('plan/{id}', [PlanController::class, 'view']);
Route::get('plan/delete/{id}', [PlanController::class, 'delete']);
Route::post('plan/edit/{id}', [PlanController::class, 'edit']);



//Slider
Route::get('sliders', [SliderController::class, 'pagination']);
Route::post('slider-create', [SliderController::class, 'save']);
Route::get('slider/{id}', [SliderController::class, 'view']);
Route::get('slider/delete/{id}', [SliderController::class, 'delete']);
Route::post('slider/edit/{id}', [SliderController::class, 'edit']);


});

//add categories to user
Route::post('add-categories', [CategoryUserController::class, 'addCategoriesToUser']);

//Auth
Route::post('login', [AuthController::class, 'login']);

Route::post('/user-reg', [AuthController::class, 'store']);

Route::post('/otp-check', [AuthController::class, 'check']);

Route::post('/password-otp', [AuthController::class, 'password']);

Route::post('change-password', [AuthController::class, 'changePassword']);





//Subscription
Route::get('subscriptions', [SubscriptionController::class, 'pagination']);
Route::post('subscription-create', [SubscriptionController::class, 'save']);
Route::get('subscription/{id}', [SubscriptionController::class, 'view']);
Route::get('subscription/delete/{id}', [SubscriptionController::class, 'delete']);
Route::post('subscription/edit/{id}', [SubscriptionController::class, 'edit']);

Route::get('my-subscriptions/{user_id}', [SubscriptionController::class, 'mySubscription']);


//Advertisement
Route::get('advertisements', [AdvertisementController::class, 'pagination']);
Route::post('advertisement-create', [AdvertisementController::class, 'save']);
Route::get('advertisement/{id}', [AdvertisementController::class, 'view']);
Route::get('advertisement/delete/{id}', [AdvertisementController::class, 'delete']);
Route::post('advertisement/edit/{id}', [AdvertisementController::class, 'edit']);

Route::get('popular-advertisements', [AdvertisementController::class, 'getPopularAdvertisings']);

Route::post('advertisements/search', [AdvertisementController::class, 'lookfor']);

//Image
Route::get('images', [ImageController::class, 'pagination']);
Route::post('image-create', [ImageController::class, 'save']);
Route::get('image/{id}', [ImageController::class, 'view']);
Route::get('image/delete/{id}', [ImageController::class, 'delete']);
Route::post('image/edit/{id}', [ImageController::class, 'edit']);

Route::post( 'advertisement/add-image/{advertisement_id}', [ ImageController::class, 'addImageToAdvertising' ]);

//Bid
Route::get('bids', [BidController::class, 'pagination']);
Route::post('bid-create', [BidController::class, 'save']);
Route::get('bid/{id}', [BidController::class, 'view']);
Route::get('bid/delete/{id}', [BidController::class, 'delete']);
Route::post('bid/edit/{id}', [BidController::class, 'edit']);


//Favorite
Route::get('favorites', [FavoriteController::class, 'pagination']);
Route::post('favorite-create', [FavoriteController::class, 'save']);
Route::get('favorite/{id}', [FavoriteController::class, 'view']);
Route::get('favorite/delete/{advertisement_id}/{user_id}', [FavoriteController::class, 'deletebyID']);
Route::post('favorite/edit/{id}', [FavoriteController::class, 'edit']);

Route::get( 'users/get-advertisement/{user_id}', [ FavoriteController::class, 'getFavoritesAdv' ]);


  //Tips
   Route::get('tips', [TipController::class, 'pagination']);
   Route::post('tip-create', [TipController::class, 'save']);
   Route::get('tip/{id}', [TipController::class, 'view']);
   Route::get('tip/delete/{id}', [TipController::class, 'delete']);
   Route::post('tip/edit/{id}', [TipController::class, 'edit']);
