<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function getStatistics()
    {
        $user = Auth::user();

        $statistics = Transaction::where('user_id', $user->id)
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->selectRaw('DATE(transactions.created_at) as date, sum(transactions.amount) as total_amount, transactions.type, categories.name as category')
            ->groupBy('date', 'transactions.type', 'categories.name')
            ->get();

        return response()->json($statistics);
    }

    public function getExpenses(Request $request)
    {
        $user = Auth::user();

        $expenses = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->orderBy('date', 'desc')
            ->get();

        return response()->json($expenses);
    }

    public function getExpenseDetails($category)
    {
        $user = Auth::user();

        $expenses = Transaction::where('user_id', $user->id)
            ->where('category', $category)
            ->where('type', 'expense')
            ->orderBy('date', 'desc')
            ->get();

        return response()->json($expenses);
    }

    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $groupBy = $request->query('group_by', 'month'); // 'day', 'week', 'month', 'year'

        if (!in_array($groupBy, ['day', 'week', 'month', 'year'])) {
            return response()->json(['error' => 'Invalid group_by parameter'], 400);
        }

        $totalExpenses = $this->getTotalAmount($user->id, 'expense', $groupBy);
        $totalIncome = $this->getTotalAmount($user->id, 'income', $groupBy);

        return response()->json([
            'expenses' => $totalExpenses,
            'income' => $totalIncome,
        ]);
    }

    private function getTotalAmount(int $userId, string $type, string $groupBy): float
    {
        $query = Transaction::where('user_id', $userId)
            ->where('type', $type);

        $this->applyDateFilter($query, $groupBy);

        return $query->sum('amount');
    }

    private function applyDateFilter($query, string $groupBy)
    {
        switch ($groupBy) {
            case 'day':
                $query->whereDate('created_at', now());
                break;
            case 'week':
                $query->whereRaw('YEARWEEK(created_at) = YEARWEEK(NOW())');
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }
    }

    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => $request->input('type'),
            'category_id' => $request->input('category_id'),
            'amount' => $request->input('amount'),
            'created_at' => Carbon::createFromFormat('d/m/Y', $request->input('created_at'))->format('Y-m-d')
        ]);

        return response()->json($transaction);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction): TransactionResource
    {
        $transaction->update($request->validated());
        return new TransactionResource($transaction);
    }

    public function destroy(Transaction $transaction): Response|ResponseFactory
    {
        $transaction->delete();
        return response(null, 204);
    }



}
