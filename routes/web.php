<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AddressController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', [AddressController::class, 'save']);


// test for insert in firebase
Route::get('firebase', function () {
    $bid = app('firebase.firestore')->database()->collection('auctions')->document('some_id');// we will replace this value with auction id
    $bid->set([
        'created_at' => 'aaa'

    ]);
});
