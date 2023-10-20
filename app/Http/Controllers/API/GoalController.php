<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGoalRequest;
use App\Http\Requests\updateGoalRequest;
use App\Http\Resources\GoalResource;
use App\Models\Goal;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class GoalController extends Controller
{

    public function index(): AnonymousResourceCollection
    {
        $goals= Goal::paginate(10);
        return GoalResource::collection($goals);
    }

    public function store(StoreGoalRequest $request): GoalResource
    {
        $goal=Goal::create($request->validated());
        return new GoalResource($goal);
    }

    public function show(Goal $goal): GoalResource
    {
        return new GoalResource($goal);
    }

    public function update(Goal $goal, updateGoalRequest $request): GoalResource
    {
        $goal->update($request->validated());
        return new GoalResource($goal);
    }

    public function destroy(Goal $goal): Application|Response|ResponseFactory
    {
        $goal->delete();
        return response(null,Response::HTTP_NO_CONTENT);
    }
}
