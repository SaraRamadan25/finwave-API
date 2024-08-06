<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetCashBalanceRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\WalletResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully',
        ], 204);
    }

    public function setCashBalance(SetCashBalanceRequest $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->cash_balance = $request->input('cash_balance');
        $user->save();

        return response()->json(['message' => 'Cash balance updated successfully']);
    }

    public function getWallet(): WalletResource
    {
        $user = Auth::user();

        return new WalletResource($user);
    }
    public function getGoals(): JsonResponse
    {
        $user = Auth::user();

        return response()->json($user->goals);
    }
}
