<?php

use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\FeedbackController;
use App\Http\Controllers\API\FilterController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\ReferralController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\SlotController;
use App\Http\Controllers\API\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Customer Routes
Route::prefix('customer')->group(function () {
    Route::post('/register', [CustomerController::class, 'register']);
    Route::post('/document', [CustomerController::class, 'document']);
    Route::post('/wishlist/add', [CustomerController::class, 'addToWishlist']);
    Route::post('/add-family', [CustomerController::class, 'AddFamily']);
    Route::post('/update', [CustomerController::class, 'update']);
    Route::post('/login', [CustomerController::class, 'login']);
    Route::post('/logout', [CustomerController::class, 'logout']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::post('/feedback', [FeedbackController::class, 'addFeedback']);
    Route::post('/complaint', [FeedbackController::class, 'addComplaint']);
    Route::post('/schedule_slots/register', [SlotController::class, 'register']);
    Route::get('/vendors/filter', [FilterController::class, 'filterVendors']);
});
// Vendor Routes
Route::prefix('vendor')->group(function () {
    Route::post('/register', [VendorController::class, 'register']);
    Route::post('/update', [VendorController::class, 'update']);
    Route::post('/login', [VendorController::class, 'login']);
    Route::post('/logout', [VendorController::class, 'logout']);
    Route::post('/change-password', [VendorController::class, 'changePassword']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::post('/posts/update', [PostController::class, 'update']);
    Route::post('/blogs/register', [BlogController::class, 'register']);
});
Route::prefix('posts')->group(function () {
    Route::post('/comments', [CommentController::class, 'store']);
    Route::post('/likes', [LikeController::class, 'store']);
});

