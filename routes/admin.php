<?php

use App\Http\Controllers\Admin\NewDurationController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\TermController;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PrivacyController;
use App\Http\Controllers\Admin\ProblemController;
use App\Http\Controllers\Admin\EyeColorController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\WorkTypeController;
use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\FirstMeetController;
use App\Http\Controllers\Admin\HairColorController;
use App\Http\Controllers\Admin\HijibTypeController;
use App\Http\Controllers\Admin\RequirmentController;
use App\Http\Controllers\Admin\BlockReasonController;
use App\Http\Controllers\Admin\FamilyValueController;
use App\Http\Controllers\Admin\MovingPlaceController;
use App\Http\Controllers\Admin\ProblemTypeController;
use App\Http\Controllers\Admin\ProcreationController;
use App\Http\Controllers\Admin\ReligiosityController;
use App\Http\Controllers\Admin\HealthStatusController;
use App\Http\Controllers\Admin\EducationTypeController;
use App\Http\Controllers\Admin\EleganceStyleController;
use App\Http\Controllers\Admin\MaritalStatusController;
use App\Http\Controllers\Admin\Location\StateController;
use App\Http\Controllers\Admin\RequirmentItemController;
use App\Http\Controllers\Admin\Location\CountryController;
use App\Http\Controllers\Admin\MarriageReadinessController;
use App\Http\Controllers\Admin\MultiplicityStatusController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ReportTypeController;
use App\Http\Controllers\Admin\UserController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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

                //users
                Route::resource('users', UserController::class);
                Route::post('users/active/{id}', [UserController::class, 'active'])->name('users.active');

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

                //report_types
                Route::resource('report_types', ReportTypeController::class);

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

                //religiositys
                Route::resource('religiositys', ReligiosityController::class);

                //elegance_styles
                Route::resource('elegance_styles', EleganceStyleController::class);

                //health_statuss
                Route::resource('health_statuss', HealthStatusController::class);

                //multiplicity_statuses
                Route::resource('multiplicity_statuses', MultiplicityStatusController::class);

                //first_meets
                Route::resource('first_meets', FirstMeetController::class);

                //family_values
                Route::resource('family_values', FamilyValueController::class);

                //moving_places
                Route::resource('moving_places', MovingPlaceController::class);

                //questions
                Route::resource('questions', QuestionController::class);

                //abouts
                Route::get('abouts', [AboutController::class, 'index'])->name('abouts.index');
                Route::post('abouts/update', [AboutController::class, 'update'])->name('abouts.update');

                //problems
                Route::get('problems', [ProblemController::class, 'index'])->name('problems.index');

                //marriage_readiness
                Route::resource('marriage_readinesses', MarriageReadinessController::class);



                //marriage_readiness
                Route::resource('block_reasons', BlockReasonController::class);

                //packages
                Route::resource('packages', PackageController::class);

                //requirments
                Route::resource('requirments', RequirmentController::class);

                //requirment_items
                Route::get('requirment_items/{id}', [RequirmentItemController::class, 'index'])->name('requirment_items.index');
                Route::get('requirment_items/create/{id}', [RequirmentItemController::class, 'create'])->name('requirment_items.create');
                Route::post('requirment_items/store/{id}', [RequirmentItemController::class, 'store'])->name('requirment_items.store');
                Route::get('requirment_items/edit/{id}', [RequirmentItemController::class, 'edit'])->name('requirment_items.edit');
                Route::post('requirment_items/update/{id}', [RequirmentItemController::class, 'update'])->name('requirment_items.update');
                Route::delete('requirment_items/{id}', [RequirmentItemController::class, 'destroy'])->name('requirment_items.destroy');

                //POSTS
                Route::resource('posts', PostController::class);
                // Route::get('posts/{id}', [PostController::class, 'index'])->name('posts.index');
                // Route::get('posts/create/{id}', [PostController::class, 'create'])->name('posts.create');
                // Route::post('posts/store/{id}', [PostController::class, 'store'])->name('posts.store');
                // Route::get('posts/edit/{id}', [PostController::class, 'edit'])->name('posts.edit');
                // Route::post('posts/update/{id}', [PostController::class, 'update'])->name('posts.update');
                Route::post('active_post', [PostController::class, 'active_post'])->name('active_post');
                //COMMENTS
                Route::get('comments/{id}', [CommentController::class, 'index'])->name('comments.index');
                Route::get('comments/create/{id}', [CommentController::class, 'create'])->name('comments.create');
                Route::post('comments/store/{id}', [CommentController::class, 'store'])->name('comments.store');
                Route::get('comments/edit/{id}', [CommentController::class, 'edit'])->name('comments.edit');
                Route::post('comments/update/{id}', [CommentController::class, 'update'])->name('comments.update');
                Route::delete('comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
                //SETTINGS
                // Route::resource('settings', SettingController::class);
                Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
                Route::post('settings/update', [SettingController::class, 'update'])->name('settings.update');



                //notification
                Route::get('notifications',  [NotificationController::class , 'index'])->name('notifications.index');
                Route::post('notifications/send',[ NotificationController::class, 'send'])->name('notifications.send');

                //new_duration
                Route::resource('new_durations', NewDurationController::class);
                Route::post('new_durations/update', [NewDurationController::class, 'update'])->name('new_durations.update');


            });
        });
    }
);
