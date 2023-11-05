<?php

use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\Auth\LocationController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Auth\PhoneController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\GiftController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PrivacyController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\SellingPortController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\TermController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//register
Route::post("register", [RegisterController::class, "register"]);

//login
Route::post("login", [LoginController::class, "login"]);

Route::get("token_invalid", [RegisterController::class, "token_invalid"])->name("token_invalid");

//reset_password
Route::post("set_password", [PasswordController::class, "set_password"]);

//check_phone
Route::post("check_phone", [PhoneController::class, "check_phone"]);


// Auth Api
Route::group(['middleware' => 'auth:api'], function () {

    //logout
    Route::post("logout", [LoginController::class, "logout"]);

  
});
// Route::post("fetch_nearest_selling_port", [SellingPortController::class, "fetch_nearest_selling_port"]);
