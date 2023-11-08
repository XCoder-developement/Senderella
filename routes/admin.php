<?php


use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EducationTypeController;
use App\Http\Controllers\Admin\HijibTypeController;
use App\Http\Controllers\Admin\WorkTypeController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\HairColorController;
use App\Http\Controllers\Admin\EyeColorController;
use App\Http\Controllers\Admin\ProcreationController;
use App\Http\Controllers\Admin\Location\CountryController;
use App\Http\Controllers\Admin\Location\StateController;
use App\Http\Controllers\Admin\MaritalStatusController;
use App\Http\Controllers\Admin\PrivacyController;
use App\Http\Controllers\Admin\ProblemTypeController;
use App\Http\Controllers\Admin\TermController;

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

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
    ],
    function () {
        Route::group(["prefix" => "ciadmin"], function () {
            Route::group(["middleware" => "guest:admin"], function () {

                //login
                Route::get("login", [AuthController::class, "login"])->name("admin_loginpage");
                Route::post("admin_login", [AuthController::class, "admin_login"])->name("admin_login");
            });

            Route::group(["middleware" => "auth:admin"], function () {
                Route::get("logout", [AuthController::class, "logout"])->name("logout");

                //admins
                Route::resource('admins', AdminController::class);

                //countries
                Route::resource('countries', CountryController::class);

                //states
                Route::resource('states', StateController::class);

                //eduaction_types
                Route::resource('education_types', EducationTypeController::class);

                //marital_statuses
                Route::resource('marital_statuses', MaritalStatusController::class);

                //colors
                Route::resource('colors', ColorController::class);

                //problem_types
                Route::resource('problem_types', ProblemTypeController::class);

                //hijib_types
                Route::resource('hijib_types', HijibTypeController::class);

                //work_types
                Route::resource('work_types', WorkTypeController::class);

                 //hair-colors
                 Route::resource('hair_colors', HairColorController::class);

                 //eye-colors
                 Route::resource('eye_colors', EyeColorController::class);

                 //procreations
                 Route::resource('procreations', ProcreationController::class);

                 //privacies
                Route::get('privacies', [PrivacyController::class, 'index'])->name('privacies.index');
                Route::post('privacies/update', [PrivacyController::class, 'update'])->name('privacies.update');

                //terms
                Route::get('terms', [TermController::class, 'index'])->name('terms.index');
                Route::post('terms/update', [TermController::class, 'update'])->name('terms.update');

            });
        });
    }
);
