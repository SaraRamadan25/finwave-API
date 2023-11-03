<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CharityController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\GoalController;
use App\Http\Controllers\API\IncomeController;
use App\Http\Controllers\API\InvestmentController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PurchaseController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\StatisticController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;

//Protected Routes

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [AuthController::class, 'getUserInfo']);
    Route::get('/logout', [AuthController::class, 'logout']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('forgot',  [AuthController::class, 'forgot']);
Route::post('reset', [AuthController::class, 'reset']);


Route::apiResource('users', UserController::class)->except('index');
Route::post('/purchases/{purchase}', [PurchaseController::class, 'update']);
Route::apiResource('purchases',PurchaseController::class)->except('update');

Route::get('/investments/search', [InvestmentController::class, 'search']);
Route::post('/investments/{investment}', [InvestmentController::class, 'update']);
Route::apiResource('investments',InvestmentController::class)->except('update');
Route::apiResource('incomes', IncomeController::class);
Route::apiResource('transactions', TransactionController::class);
Route::apiResource('goals', GoalController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('reports', ReportController::class);

Route::get('statistics', [StatisticController::class,'index']);
Route::get('statistics/{category}', [StatisticController::class, 'show']);
Route::get('charities', [CharityController::class, 'index']);
Route::get('charities/{charity}', [CharityController::class, 'show']);
Route::post('contact', [ContactController::class, 'store']);



Route::prefix('auth')
    ->as('auth.')
    ->group(function () {

        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::post('login_with_token', [AuthController::class, 'loginWithToken'])
            ->middleware('auth:sanctum')
            ->name('login_with_token');
        Route::get('logout', [AuthController::class, 'logout'])
            ->middleware('auth:sanctum')
            ->name('logout');

    });

Route::middleware('auth:sanctum')->group(function (){

    Route::apiResource('chat', ChatController::class)->only(['index','store','show']);
    Route::apiResource('chat_message', ChatMessageController::class)->only(['index','store']);
    Route::apiResource('user', UserController::class)->only(['index']);

});
