<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(StoreContactRequest $request): JsonResponse
    {
        Contact::create($request->validated());
        return response()->json(['message' => 'we will contact you soon'], 201);
    }

}
