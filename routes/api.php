<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api as Controller;

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
Route::get('comments',[Controller\CommentController::class, 'index'])->name('getComments');
Route::post('comment/store', [Controller\CommentController::class, 'store'])->name('saveComment');
Route::post('comment/reply/store', [Controller\CommentController::class, 'storeReply'])->name('saveReply');
