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
    protected string $redisKey = 'products', $exclusiveRedisKey = 'exclusive-products', $trendingRedisKey = 'trending-products', $redisField = 'all';

    // get exclusive section product on frontend
    public function getExclusiveProducts(): JsonResponse
    {
        $redisField ='all';

        $cached = Redis::hget($this->exclusiveRedisKey, $redisField);

        if($cached){
            $products = json_decode($cached);

        } else {
            $products = Product::with('variants')
                ->select(['id', 'name', 'slug', 'image_thumbnail', 'sku',
                    'video_thumbnail', 'selling_price', 'original_price', 'discount',
                    'total_day_to_delivery', 'total_sold', 'is_free_delivery']) // add more field if needed
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->where('status', 1)
                ->take(30)
                ->get();

            // caching
            Redis::hset($this->exclusiveRedisKey, $redisField, json_encode($products));
        }

        return response()->json($products);
    }

    // get tending section product on frontend
    public function getTrendingProducts(): JsonResponse
    {
        $redisField ='all';
        $cached = Redis::hget($this->trendingRedisKey, $redisField);

        if($cached) {
            $products = json_decode($cached);

        } else {
            $products = Product::with('variants')
                ->select(['id', 'name', 'slug', 'image_thumbnail', 'sku',
                    'video_thumbnail', 'selling_price', 'original_price', 'discount',
                    'total_day_to_delivery', 'total_sold', 'is_free_delivery']) // add more field if needed
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->where('status', 1)
                ->where('is_trending', 1)
                ->take(20)
                ->get();

            // caching
            Redis::hset($this->trendingRedisKey, $redisField, json_encode($products));
        }

        return response()->json($products);
    }

    public function index(): View
    {
        $cached = Redis::hget($this->redisKey, $this->redisField);

        if($cached) {
            $products = json_decode($cached);
        } else {
            $products = Product::with('category')->latest()->get(['name', 'slug', 'image_thumbnail', 'video_thumbnail', 'selling_price', 'category_id', 'status']);

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

    public function store(ProductCreateRequest $request, ProductService $productService)
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
        $products = Product::with('category')
                        ->latest()
                        ->get(['name', 'slug', 'image_thumbnail', 'video_thumbnail', 'selling_price', 'category_id', 'status']);
        // redis caching
        Redis::hset($this->redisKey, $this->redisField, json_encode($products));
    }

    public function clearProductCache(Product $product)
    {
        Redis::hdel($this->redisKey, $this->redisField);

        Redis::hdel($this->exclusiveRedisKey, $this->redisField);

        Redis::hdel($this->trendingRedisKey, $this->redisField);

        Redis::del("product_detail:$product->slug");

        Redis::del("category_products:{$product->category_id}");

        if ($product->sub_category_id) {
            Redis::del("subcategory_products:{$product->sub_category_id}");
        }

        $cacheKey = "product_detail:$product->slug";

        if(Redis::exists($cacheKey)) {
            Redis::del($cacheKey);
        }
    }

    public function getProductDetail(string $slug): JsonResponse
    {
        try {
            if (!$slug) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slug is missing',
                ], 419);        }

            $cacheKey = "product_detail:$slug";

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
                    'message' => 'Invalid product slug',
                ]);
            }

            $product->policies = $product->policies()->get();

            // make response
            $response = [
                'success' => true,
                'data' => $product
            ];

            // cached
            Redis::set($cacheKey, json_encode($response));

            return response()->json($response);
        } catch (\Throwable $th) {
          report($th);
          return response()->json([
              'success' => false,
              'message' => $th->getMessage(),
          ], 500);
        }
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
                return response()->json(json_decode(Redis::get($cacheKey)));
            }

            $products = Product::with('variants')
                ->where('category_id', $category->id)
                ->select(['id', 'name', 'slug', 'image_thumbnail', 'sku',
                    'video_thumbnail', 'selling_price', 'original_price', 'discount',
                    'total_day_to_delivery', 'total_sold', 'is_free_delivery', 'created_at'])
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->where('status', 1)
                ->latest()
                ->get();

            $response = [
                'success' => true,
                'data' => $products,
                'category' => $category,
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

            $products = Product::with('variants')
                ->where('sub_category_id', $subCategory->id)
                ->select(['id', 'name', 'slug', 'image_thumbnail', 'sku',
                    'video_thumbnail', 'selling_price', 'original_price', 'discount',
                    'total_day_to_delivery', 'total_sold', 'is_free_delivery', 'created_at'])
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->where('status', 1)
                ->latest()
                ->get();

            $response = [
                'success' => true,
                'data' => $products,
                'category' => $subCategory->category,
                'subCategory' => $subCategory,
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

        $products = Product::with('variants')
            ->where('name', 'LIKE', "%{$search}%")
            ->orWhere('sku', 'LIKE', "%{$search}%")
            ->select(['id', 'name', 'slug', 'image_thumbnail', 'sku',
                'video_thumbnail', 'selling_price', 'original_price', 'discount',
                'total_day_to_delivery', 'total_sold', 'is_free_delivery', 'created_at'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('status', 1)
            ->latest()
            ->get();

        // Map image path to full URL if necessary
        $products->transform(function($product){
            $product->image_url = asset($product->thumbnail);
            return $product;
        });

        return response()->json($products);
    }

    public function getProductsSlugs(): JsonResponse
    {
        $products = Product::where('status', 1)->get('slug');
        return response()->json($products);
    }

    public function getRelatedProducts(string $slug): JsonResponse
    {
        try {
            if(!$slug) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slug is required.'
                ]);
            }

            $cacheKey = "related_products:{$slug}";

            if(Redis::exists($cacheKey)) {
                return response()->json(json_decode(Redis::get($cacheKey)));
            }

            $product = Product::where('slug', $slug)->first();

            // Fetch related products by product category
            $relatedProducts = Product::with( 'variants')
                ->select(['id', 'name', 'slug', 'image_thumbnail', 'sku',
                    'video_thumbnail', 'selling_price', 'original_price', 'discount',
                    'total_day_to_delivery', 'total_sold', 'is_free_delivery']) // add more field if needed
                ->where('category_id', $product->category_id)
                ->where('slug', '!=', $slug)
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->latest()
                ->take(20)
                ->get();

            $response = [
                'success' => true,
                'data' => $relatedProducts,
            ];

            Redis::set($cacheKey, json_encode($response));

            return response()->json($response);

        } catch (\Throwable $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
