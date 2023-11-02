<?php

use App\Http\Controllers\api\AccessTokensController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\AttachmentController;
use App\Http\Controllers\api\BoardController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\CommentController;
use App\Http\Controllers\api\GroupController;
use App\Http\Controllers\api\PhaseController;
use App\Http\Controllers\api\CardController;
use App\Http\Controllers\api\TaskController;
use App\Http\Controllers\api\UserAttachmentController;
use App\Http\Controllers\api\UserBoardController;
use App\Http\Controllers\api\UserCardController;
use App\Http\Controllers\api\UserMemberController;
use App\Http\Controllers\api\UserWorkspaceController;
use App\Http\Controllers\api\WorkspaceController;
use Illuminate\Support\Facades\Auth;

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
    return Auth::guard('sanctum')->user();
});

Route::apiResource('workspaces', WorkspaceController::class);
Route::apiResource('boards', BoardController::class);
Route::apiResource('phases', PhaseController::class);
Route::apiResource('cards', CardController::class);
Route::apiResource('groups', GroupController::class);
Route::apiResource('tasks', TaskController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('comments', CommentController::class);
Route::apiResource('attachments', AttachmentController::class);
Route::apiResource('user-workspaces', UserWorkspaceController::class);
Route::apiResource('user-boards', UserBoardController::class);
Route::apiResource('user-cards', UserCardController::class);
Route::apiResource('user-members', UserMemberController::class);
Route::post('/auth/access-tokens', [AccessTokensController::class, 'store'])
    ->middleware('guest:sanctum');
Route::delete('/auth/access-tokens/{token?}', [AccessTokensController::class, 'destroy'])
    ->middleware('auth:sanctum');
