<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\SubCategory;
use App\Models\OtherImage;
use App\Models\ProductPolicy;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\View\View;

class ProductController extends Controller
{
    protected string $redisKey = 'products', $redisField = 'all';

    // get exclusive section product on frontend
    public function getExclusiveProducts(): JsonResponse
    {
        $redisKey ='exclusive-products';
        $redisField ='all';

        $cached = Redis::hget($redisKey, $redisField);

        if($cached){
            $products = json_decode($cached);

        } else {
            $products = Product::with('variants')
                ->select(['id', 'name', 'slug', 'thumbnail', 'selling_price', 'original_price', 'discount']) // add more field if needed
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->where('status', 1)
                ->take(30)
                ->get();

            // caching
            Redis::hset($redisKey, $redisField, json_encode($products));
        }

        return response()->json($products);
    }

    // get tending section product on frontend
    public function getTrendingProducts(): JsonResponse
    {
        $redisKey ='trending-products';
        $redisField ='all';

        $cached = Redis::hget($redisKey, $redisField);

        if($cached) {
            $products = json_decode($cached);

        } else {
            $products = Product::with('variants')
                ->select(['id', 'name', 'slug', 'thumbnail', 'selling_price', 'original_price', 'discount']) // add more field if needed
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->where('status', 1)
                ->where('is_trending', 1)
                ->take(20)
                ->get();

            // caching
            Redis::hset($redisKey, $redisField, json_encode($products));
        }
        
        return response()->json($products);
    }

    public function index(): View
    {
        $cached = Redis::hget($this->redisKey, $this->redisField);

        if($cached) {
            $products = json_decode($cached);
        } else {
            $products = Product::with('category')->latest()->get(['name', 'slug', 'thumbnail', 'selling_price', 'category_id', 'status']);

            // caching
            Redis::hset($this->redisKey, $this->redisField, json_encode($products));
        }

        return view('admin.product.index', compact('products'));
    }

    public function create(): View
    {
        // check permission of current user
        isAuthorized('product create');

        return view('admin.product.create', [
            'categories' => Category::where('status',  1)->get(),
            'subCategories' => SubCategory::where('status',  1)->get(),
            'productPolicies' => ProductPolicy::all(),
        ]);
    }

    public function store(ProductCreateRequest $request, ProductService $productService): RedirectResponse
    {
        // check permission of request user.
        isAuthorized('product create');

        $product = $productService->updateOrCreate($request->all());

        if (!$product) {
            return back()->with('error', 'Product could not be created');
        }

        $this->clearProductCache($product);

        // updating redis cache for product
        $this->updateRedisCacheForProduct();

        return redirect('/products')->with('success', 'Product created successfully');
    }

    public function show(Product $product): View
    {
        // check permission of request user
        isAuthorized('product show');

        return view('admin.product.detail', [
            'product' =>  $product->load('otherImages')
        ]);
    }

    public function edit(Product $product):  View
    {
        // check permission of request user
        isAuthorized('product edit');

        return view('admin.product.edit', [
            'categories' => Category::where('status',  1)->get(),
            'subCategories' => SubCategory::where('status',  1)->get(),
            'product' => $product->load(['variants', 'otherImages']),
            'productPolicies' => ProductPolicy::all(),
        ]);
    }

    public function update(ProductCreateRequest $request, Product $product, ProductService $productService): RedirectResponse
    {
        // check permission of current user
        isAuthorized('product edit');

        $updatedProduct = $productService->updateOrCreate($request->all(), $product);

        if (!$updatedProduct) {
            return back()->with('error', 'Product could not be updated');
        }

        $this->clearProductCache($updatedProduct);

        // updating redis cache for product
        $this->updateRedisCacheForProduct();

        return redirect('/products')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product): RedirectResponse
    {
        // check permission of request user
        isAuthorized('product destroy');

        try {
            // delete products all variants with remove image
            foreach ($product->variants as $variant) {
                if($variant->image) {
                    removeImage($variant->image);
                }
                $variant->delete();
            }

            // delete product gallery images
            $otherImages = OtherImage::where('product_id', $product->id)->get();

            foreach ($otherImages as $otherImage) {
                if ($otherImage->image)
                {
                    removeImage($otherImage->image);
                }
                $otherImage->delete();
            }

            $this->clearProductCache($product);

            $product->delete();

            // updating redis cache for product
            $this->updateRedisCacheForProduct();

            return back()->with('success', 'Product deleted successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception);
        }
    }

    public function updateRedisCacheForProduct(): void
    {
        $products = Product::with('category')->latest()->get(['name', 'slug', 'thumbnail', 'selling_price', 'category_id', 'status']);
        // redis caching
        Redis::hset($this->redisKey, $this->redisField, json_encode($products));
    }

    public function clearProductCache(Product $product)
    {
        Redis::hdel($this->redisKey, $this->redisField);

        Redis::del("product_detail:$product->slug");

        Redis::del("category_products:{$product->category_id}");

        if ($product->sub_category_id) {
            Redis::del("subcategory_products:{$product->sub_category_id}");
        }
    }
    public function getProductDetail(string $slug): JsonResponse
    {
        $cacheKey = "product_detail:$slug";

        if (!$slug) {
            return response()->json([
                'success' => false,
                'message' => 'Slug is missing',
            ], 419);
        }

        // try redis
        if (Redis::exists($cacheKey)) {
            return response()->json(json_decode(Redis::get($cacheKey)));
        }

        $product = Product::with(['category', 'otherImages', 'reviews' => function ($query) {
            $query->latest();
        }, 'reviews.user', 'variants'])
            ->where('slug', $slug)
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Slug is missing',
            ]);
        }

        // Fetch related products by category
        $relatedProducts = Product::with( 'variants')
            ->select(['id', 'name', 'slug', 'thumbnail', 'selling_price', 'original_price', 'discount']) // add more field if needed
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->take(10)
            ->get();


        // make response for
        $response = [
            'success' => true,
            'data' => [
                'product' => $product,
                'relatedProducts' => $relatedProducts,
            ]
        ];

        // cache for 1 hour
        Redis::setex($cacheKey, 3600, json_encode($response));

        return response()->json($response);
    }

    public function getCategoryProducts(): JsonResponse
    {
        $slug = request()->query('query');

        try {
            if(!$slug) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slug is required.'
                ], 419);
            }

            $category = Category::where('slug', $slug)->first();

            if(!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid category slug.'
                ], 419);
            }

            // redis cache key
            $cacheKey = "category_products:{$category->id}";

            if(Redis::exists($cacheKey)) {
                return response()->json([
                    'success' => true,
                    'data' => json_decode(Redis::get($cacheKey)),
                ]);
            }

            $products = Product::where('category_id', $category->id)
                ->select(['name', 'slug', 'thumbnail', 'selling_price', 'original_price', 'discount'])
                ->latest()
                ->get();

            $response = [
                'success' => true,
                'data' => $products,
            ];

            Redis::setex($cacheKey, 1800, json_encode($response));

            return response()->json($response);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
                'code' => 500
            ]);
        }
    }

    public function getSubCategoryProducts(): JsonResponse
    {
        $slug = request()->query('query');

        try {

            if(!$slug) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slug is required'
                ], 419);
            }

            $subCategory = SubCategory::where('slug', $slug)->first();

            if(!$subCategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid sub category slug.'
                ], 419);
            }

            $cacheKey = "subcategory_products:{$subCategory->id}";

            if(Redis::exists($cacheKey)) {
                return response()->json(json_decode(Redis::get($cacheKey)));
            }

            $products = Product::where('sub_category_id', $subCategory->id)
                ->select(['name', 'slug', 'thumbnail', 'selling_price', 'original_price', 'discount'])
                ->latest()
                ->get();

            $response = [
                'success' => true,
                'data' => $products,
            ];
            // caching
            Redis::setex($cacheKey, 1800, json_encode($response));

            return response()->json($response);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
                'code' => 500
            ]);
        }
    }

    // product search for review
    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');

        if(empty($search)){
            return response()->json([]);
        }

        $products = Product::where('name', 'LIKE', "%{$search}%")
            ->orWhere('sku', 'LIKE', "%{$search}%")
            ->select('id', 'name', 'sku', 'thumbnail') // Adjust 'thumbnail' to your actual column name (e.g., main_image)
            ->limit(10)
            ->latest()
            ->get();

        // Map image path to full URL if necessary
        $products->transform(function($product){
            $product->image_url = asset($product->thumbnail);
            return $product;
        });

        return response()->json($products);
    }
}
