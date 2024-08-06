<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGoalRequest;
use App\Http\Requests\updateGoalRequest;
use App\Http\Resources\GoalResource;
use App\Mail\GoalAchievementMail;
use App\Models\Goal;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class GoalController extends Controller
{

    public function index(): AnonymousResourceCollection
    {
        $user = Auth::user();
        $goals = $user->goals()->paginate(10);
        return GoalResource::collection($goals);
    }

    public function store(StoreGoalRequest $request): JsonResponse
    {
        $request->validated();
        $user = Auth::user();
        $goal = new Goal([
            'name' => $request->input('name'),
            'target_amount' => $request->input('target_amount'),
        ]);

        $user->goals()->save($goal);

        return response()->json($goal, 201);
    }


    public function update(UpdateGoalRequest $request, Goal $goal): JsonResponse
    {
        $request->validated();

        $user = Auth::user();
        $increaseAmount = $request->input('saved_amount');

        if ($user->cash_balance < $increaseAmount) {
            return response()->json(['error' => 'Insufficient balance'], 400);
        }

        if ($goal->saved_amount >= $goal->target_amount) {
            $user->cash_balance += $increaseAmount;
            $user->save();

            return response()->json([
                'message' => 'Goal already achieved. Amount added to cash balance.',
                'goal' => $goal
            ]);
        }

        $goal->saved_amount += $increaseAmount;

        $user->cash_balance -= $increaseAmount;

        if ($goal->saved_amount >= $goal->target_amount) {
            $excessAmount = $goal->saved_amount - $goal->target_amount;

            $goal->saved_amount = $goal->target_amount;

            $user->cash_balance += $excessAmount;

            $user->save();
            $goal->save();

            Mail::to($user->email)->send(new GoalAchievementMail($goal));

            return response()->json([
                'message' => 'Congratulations! You have achieved your goal.',
                'goal' => $goal
            ]);
        }

        $user->save();
        $goal->save();

        return response()->json($goal);
    }
    public function show(Goal $goal): GoalResource
    {
        return new GoalResource($goal);
    }


    public function destroy(Goal $goal): Application|Response|ResponseFactory
    {
        $goal->delete();
        return response(null,Response::HTTP_NO_CONTENT);
    }
}
