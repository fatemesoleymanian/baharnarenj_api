<?php

use App\Http\Controllers\AclController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactUsFormController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// TODO: Order
Route::middleware('auth:sanctum')->prefix('/orders')->group(function (){
    Route::get('/',[OrderController::class,'index']);
});

# ACL
    Route::prefix('/acl')->group(function () {
    Route::post('/register', [AclController::class, 'register']);
    Route::post('/login', [AclController::class, 'login']);
    Route::post('/forget-password', [AclController::class, 'forgetPassword']);
    Route::post('/reset-password', [AclController::class, 'resetPassword']);
});
    Route::post('/contact_us',[ContactUsFormController::class,'store']);
    Route::prefix('/contact_form')->group(function (){
        Route::get('/',[ContactUsFormController::class,'index']);
        Route::get('/{contactUsForm}',[ContactUsFormController::class,'show']);
    });

# User
Route::middleware('auth:sanctum')->prefix('/user')->group(function () {
    Route::delete('/{user}', [UserController::class, 'destroy']);
    Route::get('/{user}', [UserController::class, 'show']);
    Route::resource('/', UserController::class);
    Route::put('/update',[UserController::class,'update']);
    Route::put('/change-password', [UserController::class, 'changePass']);
    Route::post('/logout', [AclController::class, 'logout']);
});

#Tag
Route::prefix('/tag')->group( function (){
    Route::get('/',[TagController::class,'index']);
});

# Cart
Route::middleware('auth:sanctum')->prefix('/cart')->group(function () {
    Route::get('/products', [CartProductController::class, 'cartProducts']);
    Route::post('/add-product', [CartProductController::class, 'addProductToCart']);
    Route::delete('/remove-item/{cartProduct}', [CartProductController::class, 'destroy']);
    Route::delete('/empty/{user}', [CartProductController::class, 'empty']);
    Route::put('/change-product-quantity', [CartProductController::class, 'changeProductQuantity']);
});

# Category
Route::middleware('auth:sanctum')->prefix('/category')->group(function () {
        Route::put('/{category}', [CategoryController::class, 'update']);
    Route::post('/', [CategoryController::class, 'store']);

    Route::resource('/', CategoryController::class);
    Route::put('/{category}', [CategoryController::class, 'update']);
    Route::delete('/{category}', [CategoryController::class, 'destroy']);

});

# Product
Route::prefix('/product')->group(function () {
    Route::put('/{product}',[ProductController::class,'update']);
    Route::post('/',[ProductController::class,'store']);
    Route::delete('/{product}',[ProductController::class,'destroy']);

    Route::resource('/', ProductController::class);
});
# Product
Route::prefix('/products')->group(function () {
    Route::get('/', [ProductController::class,'index']);
    Route::get('/latest', [ProductController::class,'latest']);
    Route::get('/most_popular', [ProductController::class,'most_popular']);
    Route::get('/best_seller', [ProductController::class,'best_seller']);
    Route::get('/{product}', [ProductController::class,'show']);
    Route::get('/related_products/{category}', [ProductController::class,'related_products']);
});
# Slider
Route::prefix('/slider')->group(function () {
    Route::resource('/', SliderController::class);
});

# Support
Route::prefix('/support')->group(function () {
    Route::get('/all', [SupportController::class, 'all'])->middleware(['auth:sanctum', 'only-admin']);
    Route::post('/client-request', [SupportController::class, 'clientRequest']);
    Route::post('/admin-response', [SupportController::class, 'adminResponse'])->middleware(['auth:sanctum', 'only-admin']);
});

# Admin
Route::prefix('/admin')->group(function () {
    Route::post('/login', [AdminController::class, 'login']);
    Route::get('/all', [AdminController::class, 'all']);
    Route::get('/users', [UserController::class, 'index']);
});

# Post
Route::prefix('/post')->group(function () {
    Route::post('/',[PostController::class,'store']);
    Route::put('/{post}',[PostController::class,'update']);
    Route::delete('/{post}',[PostController::class,'destroy']);
    Route::resource('/', PostController::class);
    Route::get('/latest', [PostController::class,'latest']);
    Route::get('all-for-admin', [PostController::class, 'allForAdmin']);
    Route::get('/{slug}', [PostController::class,'show']);

});


Route::get('/link', function () {
    Artisan::call('storage:link');
});

Route::post('/upload',function (\Illuminate\Http\Request $request){
    return $request->image = handleFile($request->location, $request->image);


});
Route::post('/remove_upload',function (\Illuminate\Http\Request $request){
    $path = parse_url($request->imageName);

    // $remove = \Illuminate\Support\Facades\File::delete($path['path']);
    $remove = \Illuminate\Support\Facades\Storage::delete($path['path']);
    if ($remove) return response()->json([
        'success' => 1,
        'msg' => 'فایل با موفقیت حذف گردید.'
    ]);
    else  return response()->json([
        'success' => 0,
        'msg' => 'خطا در حذف فایل'
    ]);

});

