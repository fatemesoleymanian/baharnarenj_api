<?php

namespace App\Http\Controllers;


use App\Exceptions\CustomException;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
//        $this->middleware('only-admin')->except('update','index','show','latest','most_popular','best_seller','related_products');
    }

    /**
     * @return JsonResponse
     */
    public function latest(): JsonResponse
    {
        $products = Product::with('category')->latest()->take(8)->get()->makeHidden('description');
        return successResponse([
            'products' => $products
        ]);
    }

    public function most_popular(): JsonResponse
    {
        $products = Product::with('category')->orderByDesc('price')->take(8)->get()->makeHidden('description');
        return successResponse([
            'products' => $products
        ]);
    }

    public function best_seller(): JsonResponse
    {
        $products = Product::with('category')->orderBy('price','asc')->take(8)->get()->makeHidden('description');
        return successResponse([
            'products' => $products
        ]);
    }

    public function related_products($category)
    {
        $products = Product::query()->where('category_id',$category)->get()->makeHidden('description');
        return successResponse([
           'products' => $products->load('category')
        ]);
    }
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = Product::with('category')->orderByDesc('id')->get()->makeHidden('description');
        return successResponse([
            'products' => $products
        ]);
    }

    /**
     * @param ProductRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $data = filterData($request->validated());
//        $data['image'] = handleFile('/products', $data['image']);
//        $data['slug'] =  Str::slug($data['slug']);
        Product::query()->updateOrCreate(['name' => $data['name']], $data);
        return successResponse();
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        return successResponse([
            'product' => $product->load('category')
        ]);
    }

    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return JsonResponse
     * @throws CustomException
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        $data = filterData($request->validated());
//        if (exists($data['image'])) {
//            $data['image'] = handleFile('/products', $data['image']);
//        }
//       if (exists($data['slug'])) $data['slug'] =  Str::slug($data['slug']);
        $product->update($data);
        return successResponse();
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product)
    {
        $path = parse_url($product['image']);
        $remove = \Illuminate\Support\Facades\File::delete(public_path($path['path']));
        $product->delete();
        return successResponse();
    }
}
