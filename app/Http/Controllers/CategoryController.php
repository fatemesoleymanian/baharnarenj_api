<?php

namespace App\Http\Controllers;


use App\Exceptions\CustomException;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('only-admin')->except('index');
//    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = Category::query()->with(['products'])->get();
        return successResponse([
            'categories' => $categories
        ]);
    }

    /**
     * @param CategoryRequest $request
     * @return JsonResponse
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        $data = $request->validated();
//        $data['image'] = handleFile('/categories', $data['image']);
        Category::query()->updateOrCreate(['title' => $data['title']], $data);
        return successResponse();
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        return successResponse([
            'category' => $category->load('products')
        ]);
    }

    /**
     * @param CategoryRequest $request
     * @param Category $category
     * @return JsonResponse
     * @throws CustomException
     */
    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $data = filterData($request->validated());
//        if (exists($data['image'])) {
//            $data['image'] = handleFile('/categories', $data['image']);
//        }
        $category->update($data);
        return successResponse();
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return successResponse();
    }
}
