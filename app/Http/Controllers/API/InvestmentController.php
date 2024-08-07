<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\StoreInvestmentRequest;
use App\Http\Requests\UpdateInvestmentRequest;
use App\Http\Resources\InvestmentResource;
use App\Models\Investment;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class InvestmentController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $investments = Investment::with('user')->get();
        return InvestmentResource::collection($investments);
    }
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'videos' => 'nullable|array',
            'videos.*' => 'nullable|file|mimes:mp4,mov,avi,wmv',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $imagePaths = [];
        foreach ($data['image'] as $image) {
            $path = $this->uploadImage($image);
            $imagePaths[] = $path;
        }
        $data['image'] = json_encode($imagePaths);

        if ($request->has('videos')) {
            $videoPaths = [];
            foreach ($data['videos'] as $video) {
                $path = $this->uploadVideo($video);
                $videoPaths[] = $path;
            }
            $data['videos'] = json_encode($videoPaths);
        }

        $investment = Investment::create($data);

        return response()->json(['message' => 'Your Investment Created Successfully!'], 201);
    }
    public function show(Investment $investment): InvestmentResource
    {
        return new InvestmentResource($investment);
    }
    public function update(Investment $investment, UpdateInvestmentRequest $request): JsonResponse
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

        if ($request->has('videos')) {
            $videoPaths = [];

            foreach ($data['videos'] as $video) {
                $path = $this->uploadVideo($video);
                $videoPaths[] = $path;
            }

            $data['videos'] = json_encode($videoPaths);
        }

        $investment->update($data);

        return response()->json(['message' => 'Your Investment Updated Successfully ! '], 200);

    }
    public function destroy(Investment $investment): Application|Response|ResponseFactory
    {
        $investment->delete();
        return response(null,Response::HTTP_NO_CONTENT);
    }
    public function search(Request $request): AnonymousResourceCollection
    {
        $query = $request->input('query');

        $investments = Investment::where('title', 'LIKE', "%$query%")
            ->orWhere('description', 'LIKE', "%$query%")
            ->get();

        return InvestmentResource::collection($investments);
    }
    private function uploadImage($file): string
    {
        $uniqueName = uniqid() . '_' . time();
        $extension = $file->getClientOriginalExtension();
        $fileName = $uniqueName . '.' . $extension;
        $file->storeAs('public/investments', $fileName);

        return 'investments/' . $fileName;
    }
    private function uploadVideo($file): string
    {
        $uniqueName = uniqid() . '_' . time(); // Generate a unique name
        $extension = $file->getClientOriginalExtension();
        $fileName = $uniqueName . '.' . $extension;
        $file->storeAs('public/videos', $fileName);

        return 'videos/' . $fileName;
    }

}
