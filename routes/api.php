
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\GiftController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TermController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\PrivacyController;
use App\Http\Controllers\Api\ProblemController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\SkinColorController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\PhoneController;
use App\Http\Controllers\Api\RequirmentController;
use App\Http\Controllers\Api\BlockReasonController;
use App\Http\Controllers\Api\ProblemTypeController;
use App\Http\Controllers\Api\SellingPortController;
use App\Http\Controllers\Api\TransactionController;
use App\Models\MarriageReadiness\MarriageReadiness;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\Auth\LocationController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\EducationTypeController;
use App\Http\Controllers\Api\MaritalStatusController;
use App\Http\Controllers\Api\SearchPartnerController;
use App\Http\Controllers\Api\FetchLastSearchController;
use App\Http\Controllers\Api\UserInformationController;
use App\Http\Controllers\Admin\MarriageReadinessController;
use App\Http\Controllers\Admin\PackageRuleController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ReportTypeController;
use App\Http\Controllers\Api\MarriageReadinessController as ApiMarriageReadinessController;
use App\Http\Controllers\Api\PackageRuleController as ApiPackageRuleController;
use App\Http\Controllers\Api\PackageTermController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\WhatsappController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//register
Route::post("register", [RegisterController::class, "register"]);

//login
Route::post("login", [LoginController::class, "login"]);

Route::get("token_invalid", [RegisterController::class, "token_invalid"])->name("token_invalid");

//reset_password
Route::post("reset_password", [PasswordController::class, "reset_password"]);

//check_phone
Route::post("check_phone", [PhoneController::class, "check_phone"]);

//fetch_problem_types
Route::get("fetch_problem_types", [ProblemTypeController::class, "fetch_problem_types"])->name('fetch_problem_types');

//fetch_problem_types
Route::get("fetch_report_types", [ReportTypeController::class, "fetch_report_types"])->name('fetch_report_types');

//send_problem
Route::post("send_problem", [ProblemController::class, "send_problem"])->name('send_problem');


//fetch_privacy
Route::get("fetch_privacy", [PrivacyController::class, "fetch_privacy"]);

//fetch_term
Route::get("fetch_term", [TermController::class, "fetch_term"]);

//fetch_questions
Route::get("fetch_questions", [QuestionController::class, "fetch_questions"]);

//fetch_about
Route::get("fetch_about", [SettingController::class, "fetch_about"]);

//fetch_packages
Route::get("fetch_packages", [PackageController::class, "fetch_packages"]);

//fetch_requirements
Route::get("fetch_requirments", [RequirmentController::class, "fetch_requirments"]);

//fetch_questions
Route::get("fetch_user_questions", [RequirmentController::class, "fetch_user_questions"]);

//fetch_block_reasons
Route::get("fetch_block_reasons", [BlockReasonController::class, "fetch_block_reasons"]);

//fetch_marital_status
Route::get("fetch_marital_status", [MaritalStatusController::class, "fetch_marital_status"]);

//fetch_countries
Route::get("fetch_countries", [CountryController::class, "fetch_countries"]);

// Auth Api
Route::group(['middleware' => 'auth:api'], function () {

    //logout
    Route::get("logout", [LoginController::class, "logout"]);

    //change_password
    Route::post("change_password", [PasswordController::class, "change_password"]);

    //set_user_data
    Route::post("set_user_data", [UserController::class, "set_user_data"]);

    //set_user_images
    Route::post("set_user_images", [UserController::class, "set_user_images"]);

    //delte_account
    Route::get("delte_account", [UserController::class, "delete_account"]);

    //subscribe_package
    Route::post("subscribe_package", [PackageController::class, "subscribe_package"]);

    //fetch_requirements
    Route::get("fetch_requirments", [RequirmentController::class, "fetch_requirments"]);

    //fetch_questions
    Route::get("fetch_user_questions", [RequirmentController::class, "fetch_user_questions"]);

    //fetch_block_reasons
    Route::get("fetch_block_reasons", [BlockReasonController::class, "fetch_block_reasons"]);

    //fetch_marital_status
    Route::get("fetch_marital_status", [MaritalStatusController::class, "fetch_marital_status"]);

    //fetch_readiness_for_marriages
    Route::get("fetch_readiness_for_marriages", [ApiMarriageReadinessController::class, "fetch_readiness_for_marriages"]);

    //fetch_countries
    Route::get("fetch_countries", [CountryController::class, "fetch_countries"]);

    //fetch_all_partners
    Route::get("fetch_all_partners", [PartnerController::class, "fetch_all_partners"]);

    //POST
    Route::get("fetch_post", [PostController::class, "fetch_post"]);
    Route::post("like_post", [PostController::class, "likePost"]);
    //COMMENT
    Route::post("store_comment", [CommentController::class, "store"]);
    Route::post("like_comment", [CommentController::class, "likeComment"]);

    Route::get("fetch_all_partners", [PartnerController::class, "fetch_all_partners"]);

    //fetch_partner_details
    Route::post("fetch_partner_details", [PartnerController::class, "fetch_partner_details"]);

    //fetch_new_partners
    Route::get("fetch_new_partners", [PartnerController::class, "fetch_new_partners"]);

    //like_partner
    Route::post("like_partner", [PartnerController::class, "like_partner"]);

    //block_partner
    Route::post("block_partner", [PartnerController::class, "block_partner"]);

    //fetch_education_types
    Route::get("fetch_education_types", [EducationTypeController::class, "fetch_education_types"]);

    //fetch_skin_colors
    Route::get("fetch_skin_colors", [SkinColorController::class, "fetch_skin_colors"]);

    //bookmark_partner
    Route::post("bookmark_partner", [PartnerController::class, "bookmark_partner"]);

    //user_watch
    Route::post("user_watch", [PartnerController::class, "user_watch"]);

    //fetch_followers
    Route::get("fetch_followers", [PartnerController::class, "fetch_followers"]);

    //fetch_following
    Route::get("fetch_following", [PartnerController::class, "fetch_following"]);

    //send_report
    Route::post("send_report", [ReportController::class, "send_report"])->name('send_report');

    //chatting
    Route::post("create_chat", [ChatController::class, "create_chat"])->name('create_chat');
    Route::post('send_message', [ChatController::class, 'send_message'])->name('send_message');
    Route::get('fetch_chats', [ChatController::class, 'fetch_chats'])->name('fetch_chats');
    //fetch_all_messages
    Route::post("fetch_messages", [ChatController::class, "fetch_messages"]);


    //fetch_my_block_partners
    Route::get("fetch_my_block_partners", [PartnerController::class, "fetch_my_block_partners"]);

    //fetch_blockers
    Route::get("fetch_blockers", [PartnerController::class, "fetch_blockers"]);

    //who_i_watch
    Route::get("who_i_watch", [PartnerController::class, "who_i_watch"]);

    //who_watch_my_account
    Route::get("who_watch_my_account", [PartnerController::class, "who_watch_my_account"]);

    //who_i_favorite
    Route::get("who_i_favorite", [PartnerController::class, "who_i_favorite"]);

    //who_favorite_me
    Route::get("who_favorite_me", [PartnerController::class, "who_favorite_me"]);

    //most_compatible_partners
    Route::get("most_compatible_partners", [PartnerController::class, "most_compatible_partners"]);

    //fetch_most_liked_partners
    Route::get("fetch_most_liked_partners", [PartnerController::class, "fetch_most_liked_partners"]);

    //SEARCH PARTNER
    Route::post("search_partner", [SearchPartnerController::class, "search_partner"]);

    //fetch_nearst_partners
    Route::post("fetch_nearst_partners", [PartnerController::class, "fetch_nearst_partners"]);

    //FETCH LAST SEARCH
    Route::get("fetch_last_search", [FetchLastSearchController::class, "fetch_last_search"]);

    //account_document
    Route::post("account_document", [UserController::class, "account_document"]);

    //entry_status
    Route::post("entry_status", [UserController::class, "entry_status"]);

    //new_partner_activity
    Route::get("new_partner_activity", [UserController::class, "new_partner_activity"]);

    //notifications
    Route::get("fetch_notifications", [NotificationController::class, "fetch_notifications"]);


    //set_visibility
    Route::post("set_visibility", [UserController::class, "set_visibility"]);

    //update_location
    Route::post("update_location", [UserController::class, "update_location"]);


    //package_rules
    Route::get("fetch_package_rules", [ApiPackageRuleController::class, "fetch_package_rules"]);

    //fetch_package_terms
    Route::get("fetch_package_terms", [PackageTermController::class, "fetch_package_terms"]);


    // users_off
    // Route::get("users_off", [UserController::class, "users_off"]);

    //screenshot_session_api
Route::get("screenshot_session_api", [WhatsappController::class, "screenshot_session_api"]);
});
