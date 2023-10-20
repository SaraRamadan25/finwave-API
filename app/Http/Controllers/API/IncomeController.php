<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use App\Http\Resources\IncomeResource;
use App\Models\Income;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class IncomeController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $incomes = Income::all();
        return IncomeResource::collection($incomes);
    }

    public function store(StoreIncomeRequest $request): IncomeResource
    {
        $income = Income::create($request->validated());
        return new IncomeResource($income);
    }

    public function show(Income $income): IncomeResource
    {
        return new IncomeResource($income);
    }

    public function update(Income $income, UpdateIncomeRequest $request): IncomeResource
    {
        $income->update($request->validated());
        return new IncomeResource($income);
    }

    public function destroy(Income $income): Application|Response|ResponseFactory
    {
        $income->delete();
        return response(null,Response::HTTP_NO_CONTENT);
    }
}
