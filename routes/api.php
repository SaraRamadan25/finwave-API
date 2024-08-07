<?php

use App\Http\Controllers\API\AuthController;
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
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'getUserInfo']);

    Route::get('/user', [AuthController::class, 'getUserInfo']);
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::post('setBalance', [UserController::class, 'setCashBalance']);
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::get('wallet', [UserController::class, 'getWallet']);
    Route::post('goals', [GoalController::class, 'store']);
    Route::get('goals', [GoalController::class, 'index']);
    Route::patch('goals/{goal}', [GoalController::class, 'update']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('transactions/statistics', [TransactionController::class, 'getStatistics']);
    Route::get('transactions/expenses', [TransactionController::class, 'getExpenses']);
    Route::get('transactions/expenses/{category}', [TransactionController::class, 'getExpenseDetails']);
    Route::get('statistics', [TransactionController::class, 'index']);
    Route::get('statistics-details', [TransactionController::class, 'details']);
    Route::get('category-details', [TransactionController::class, 'categoryDetails']);
    Route::get('investments', [InvestmentController::class, 'index']);
    Route::post('investments', [InvestmentController::class, 'store']);
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('forgot',  [AuthController::class, 'forgot']);
Route::post('reset', [AuthController::class, 'reset']);



