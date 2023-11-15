<?php

use App\Http\Controllers\api\AccessTokensController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\AttachmentController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\BoardController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\CommentController;
use App\Http\Controllers\api\GroupController;
use App\Http\Controllers\api\PhaseController;
use App\Http\Controllers\api\CardController;
use App\Http\Controllers\api\InvitationController;
use App\Http\Controllers\api\LoginController as ApiLoginController;
use App\Http\Controllers\api\TaskController;
use App\Http\Controllers\api\UserAttachmentController;
use App\Http\Controllers\api\UserBoardController;
use App\Http\Controllers\api\UserCardController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\UserMemberController;
use App\Http\Controllers\api\UserWorkspaceController;
use App\Http\Controllers\api\WorkspaceController;
use App\Http\Controllers\api\PayPalController;
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

Route::middleware('auth:sanctum')->get('/auth/user', function (Request $request) {
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
Route::apiResource('user', UserController::class);

Route::get('/attachments/{id}/download', [AttachmentController::class, 'serveAttachment']);

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::delete('/auth/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::post('send-invitation', [InvitationController::class, 'sendInvitation']);
Route::post('accept-invitation/{id}', [InvitationController::class, 'acceptInvitation'])->name('accept-invitation');
Route::post('decline-invitation/{id}', [InvitationController::class, 'declineInvitation'])->name('decline-invitation');


Route::post('pay', [MyFatoorahController::class, 'payOrder']);
Route::get('payment/success', function () {
    return 'payment succeeded';
});
Route::get('payment/error', function () {
    return 'payment failed';
});


Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

Route::get('auth/login/{provider}', [ApiLoginController::class, 'redirectToProvider']);
Route::get('auth/login/{provider}/callback', [ApiLoginController::class, 'handleProviderCallback']);

Route::get('paypal', [PayPalController::class, 'index'])->name('paypal');
Route::get('paypal/payment', [PayPalController::class, 'payment'])->name('paypal.payment');
Route::get('paypal/payment/success', [PayPalController::class, 'paymentSuccess'])->name('paypal.payment.success');
Route::get('paypal/payment/cancel', [PayPalController::class, 'paymentCancel'])->name('paypal.payment/cancel');
