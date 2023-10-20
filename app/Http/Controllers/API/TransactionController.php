<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    public function index(): JsonResponse
    {
        $transactions = Transaction::paginate(10);
        return TransactionResource::collection($transactions)->response();
    }
    public function store(StoreTransactionRequest $request): Application|Response|ResponseFactory
    {
        $data = $request->validated();
        $transaction = Transaction::create($data);
        return response(new TransactionResource($transaction), 201);
    }
    public function show(Transaction $transaction): TransactionResource
    {
        return new TransactionResource($transaction);
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
