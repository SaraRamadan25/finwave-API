<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PurchaseController extends Controller
{
    public function index(): JsonResponse
    {
        $purchases = Purchase::paginate(10);
        return PurchaseResource::collection($purchases)->response();
    }
    public function store(StorePurchaseRequest $request): Application|ResponseFactory|Response
    {
        $file = $request->file('image');
        $filename = "No Images Uploaded, If you wanna put your purchase an image so do it now !";

        if ($file) {
            $extension = $file->extension();
            $filename = uniqid() . '.' . $extension;
            Storage::disk('public')->putFileAs('PurchasesPhotos', $file, $filename);
        }
        $data = $request->validated();

        if ($filename) {
            $data['image'] = $filename;
        }

        $purchase = Purchase::create($data);
        return response(new PurchaseResource($purchase), 201);
    }
    public function show(Purchase $purchase): PurchaseResource
    {
        return new PurchaseResource($purchase);
    }
    public function destroy(Purchase $purchase): JsonResponse
    {
        $purchase->delete();

        return response()->json(null, 204);
    }

    public function update(Purchase $purchase, UpdatePurchaseRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->has('image')) {
            $imagePaths = [];

            foreach ($data['image'] as $image) {
                $path = $this->uploadImage($image);
                $imagePaths[] = $path;
            }

            $data['image'] = json_encode($imagePaths);
        }

        $purchase->update($data);
        return response()->json(['message' => 'Your Purchase Updated Successfully ! '], 200);
    }

    private function uploadImage($file): string
    {
        $uniqueName = uniqid() . '_' . time();
        $extension = $file->getClientOriginalExtension();
        $fileName = $uniqueName . '.' . $extension;
        $file->storeAs('public/purchases', $fileName);

        return 'purchases/' . $fileName;
    }

}
