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
            'name' => $request->input('name'),
            'user_id' => $user->id,
            'price' => $request->input('price'),
            'type' => $request->input('type'),
            'quantity' => $request->input('quantity'),
            'category_id' => $request->input('category_id'),
            'created_at' => Carbon::now(),
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

    public function details(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $groupBy = $request->query('group_by', 'day'); // 'year', 'month', 'week', 'day', 'expense', 'income'

        if (in_array($groupBy, ['expense', 'income'])) {
            $transactions = $this->getTransactions($user, 'day', $groupBy);

            Log::info('Fetched transactions:', $transactions->toArray());

            $groupedTransactions = $this->groupAndSummarize($transactions, 'day');
            $response = $this->formatForDisplay($groupedTransactions, 'day');
        } else {
            $allTransactions = $this->getTransactions($user, $groupBy);
            $groupedAllTransactions = $this->groupAndSummarize($allTransactions, $groupBy);

            $expenses = $this->getTransactions($user, $groupBy, 'expense');
            $groupedExpenses = $this->groupAndSummarize($expenses, $groupBy);

            $income = $this->getTransactions($user, $groupBy, 'income');

            Log::info('Fetched income transactions:', $income->toArray());

            $groupedIncome = $this->groupAndSummarize($income, $groupBy);

            $response = [
                'general' => $this->formatForDisplay($groupedAllTransactions, $groupBy),
                'expenses' => $this->formatForDisplay($groupedExpenses, $groupBy),
                'income' => $this->formatForDisplay($groupedIncome, $groupBy),
            ];
        }

        return response()->json($response);
    }



    private function formatForDisplay($groupedData, $groupBy)
    {
        $result = [];

        foreach ($groupedData as $date => $data) {
            $dateFormatted = match ($groupBy) {
                'day' => Carbon::parse($date)->format('Y-m-d'),
                'week' => 'Week of ' . Carbon::parse($date)->startOfWeek()->format('Y-m-d'),
                'month' => Carbon::parse($date)->format('F Y'),
                'year' => $date,
                default => $date,
            };

            $categories = [];
            foreach ($data['categories'] as $categoryName => $category) {
                $categories[$categoryName] = [
                    'category' => $category['category'] ?? 'Unknown', // Ensure category is accessed correctly
                    'total_amount' => $category['total_amount'],
                    'transaction_count' => $category['transaction_count']
                ];
            }

            $result[] = [
                'date' => $dateFormatted,
                'total_amount' => $data['total_amount'],
                'categories' => $categories
            ];
        }

        return $result;
    }
    private function getTransactions($user, $groupBy, $type = null, $year = null, $week = null, $categoryId = null)
    {
        $query = Transaction::where('user_id', $user->id)
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select('transactions.*', 'categories.name as category_name'); // Ensure the category name is selected

        if ($type) {
            $query->where('type', $type);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        switch ($groupBy) {
            case 'day':
                $query->whereDate('transactions.created_at', now()->format('Y-m-d'));
                break;
            case 'week':
                $query->whereRaw('YEARWEEK(transactions.created_at, 1) = YEARWEEK(?, 1)', [now()]);
                break;
            case 'month':
                $query->whereRaw('YEAR(transactions.created_at) = YEAR(?) AND MONTH(transactions.created_at) = MONTH(?)', [now(), now()]);
                break;
            case 'year':
                $query->whereRaw('YEAR(transactions.created_at) = YEAR(?)', [now()]);
                break;
            default:
                throw new \InvalidArgumentException('Invalid group_by parameter');
        }

        return $query->get();
    }

    public function categoryDetails(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $categoryId = $request->query('category_id');
        $groupBy = $request->query('group_by', 'day'); // 'year', 'month', 'week', 'day'
        $year = $request->query('year');
        $week = $request->query('week');

        if (!$categoryId) {
            return response()->json(['error' => 'Category ID is required'], 400);
        }

        $transactions = $this->getTransactions($user, $groupBy, null, $year, $week, $categoryId);
        $groupedTransactions = $this->groupAndSummarize($transactions, $groupBy);

        return response()->json($groupedTransactions);
    }
    private function groupAndSummarize($transactions, $groupBy)
    {
        $grouped = $transactions->groupBy(function ($item) use ($groupBy) {
            return match ($groupBy) {
                'week' => $item->created_at->startOfWeek()->format('Y-m-d'),
                'month' => $item->created_at->format('Y-m'),
                'year' => $item->created_at->format('Y'),
                default => $item->created_at->format('Y-m-d'),
            };
        });

        return $grouped->map(function ($group) {
            $totalAmount = $group->sum('price');
            $categories = $group->groupBy('category_name')->map(function ($items) {
                return [
                    'category' => $items->first()->category_name ?? 'Unknown', // Use category_name
                    'total_amount' => $items->sum('price'),
                    'transaction_count' => $items->count()
                ];
            });

            return [
                'total_amount' => $totalAmount,
                'categories' => $categories
            ];
        });
    }

}
