<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CharityResource;
use App\Models\Charity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CharityController extends Controller
{
    public function index(): JsonResponse
    {
        $charities = Charity::paginate(5);
        return CharityResource::collection($charities)->response();
    }

    public function show(Charity $charity): CharityResource
    {
        return new CharityResource($charity);
    }

}
