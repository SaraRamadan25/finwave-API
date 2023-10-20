<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\StatisticResource;
use App\Models\Category;
use App\Models\Statistic;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class StatisticController extends Controller
{

    public function index(): JsonResponse
    {
        $statistics = Statistic::paginate(5);
        return StatisticResource::collection($statistics)->response();
    }

    public function show(Category $category): StatisticResource|JsonResponse
    {
        $statistic = $category->statistic;

        if (!$statistic) {
            return response()->json(['error' => 'This Category Does not exist, so no statistic for it'], 404);
        }

        return new StatisticResource($statistic);
    }

}
