<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Database\Factories\categoryFactory;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $categories= Category::with('purchases')->paginate(10);
        return CategoryResource::collection($categories);
    }
    public function store(StoreCategoryRequest $request): CategoryResource
    {
        $category=Category::create($request->validated());
        return new CategoryResource($category);
    }
    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }
    public function update(Category $category, UpdateCategoryRequest $request): CategoryResource
    {
        $category->update($request->validated());
        return new CategoryResource($category);
    }
    public function destroy(Category $category): Application|Response|ResponseFactory
    {
        $category->purchases()->delete();
        $category->delete();
        return response(null,Response::HTTP_NO_CONTENT);
    }
}
