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
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Throwable;

class ProductController extends Controller
{
    protected string $redisKey = 'products',
                    $exclusiveRedisKey = 'exclusive-products',
                    $trendingRedisKey = 'trending-products',
                    $redisSlugKey = "products.slugs";

    // get exclusive section product on frontend
    public function getExclusiveProducts(): JsonResponse
    {
        $products = Cache::remember($this->exclusiveRedisKey, now()->addDay(), function () {
            return Product::with('variants')
                ->select(['id', 'category_id', 'name', 'slug', 'image_thumbnail', 'sku',
                    'video_thumbnail', 'selling_price', 'original_price', 'discount',
                    'total_delivery_day', 'total_sold', 'default_sold', 'show_default_sold', 'is_free_delivery', 'is_export']) // add more field if needed
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->where('status', 1)
                ->take(30)
                ->get();
        });

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    // get tending section product on frontend
    public function getTrendingProducts(): JsonResponse
    {
        $products = Cache::remember($this->trendingRedisKey, now()->addDay(), function () {
            return  Product::with('variants')
                ->select(['id', 'category_id', 'name', 'slug', 'image_thumbnail', 'sku',
                    'video_thumbnail', 'selling_price', 'original_price', 'discount',
                    'total_delivery_day', 'total_sold', 'default_sold', 'show_default_sold', 'is_free_delivery', 'is_export']) // add more field if needed
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->where('status', 1)
                ->where('is_trending', 1)
                ->take(30)
                ->get();
        });

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function index()
    {
        $products = Cache::remember($this->redisKey, now()->addDay(), function () {
            return Product::with('category')->latest()->get(['name', 'slug', 'image_thumbnail', 'video_thumbnail',
                'selling_price', 'category_id', 'status']);
        });

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

    /**
     * @throws Throwable
     */
    public function store(ProductCreateRequest $request, ProductService $productService): RedirectResponse
    {
        // check permission of request user.
        isAuthorized('product create');

        $product = $productService->updateOrCreate($request->validated());

        if (!$product) {
            return back()->with('error', 'Product could not be created');
        }

        $this->clearProductCache($product);

        // updating redis cache for product
        $this->updateRedisCacheForProduct();

        return redirect('/products')->with('success', 'Product created successfully');
    }

    public function show(Product $product)
    {
        // check permission of request user
        isAuthorized('product show');

        return view('admin.product.detail', [
            'product' =>  $product->load(['variants', 'otherImages'])
        ]);
    }

    public function edit(Product $product): View
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

    /**
     * @throws Throwable
     */
    public function update(ProductCreateRequest $request, Product $product, ProductService $productService)
    {
        // check permission of current user
        isAuthorized('product edit');

        $updatedProduct = $productService->updateOrCreate($request->validated(), $product);

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
        Cache::forget($this->redisKey);
    }

    public function clearProductCache(Product $product)
    {
        $productDetailKey = "product_detail:{$product->slug}";
        Cache::forget($productDetailKey);

        Cache::forget($this->redisKey);
        Cache::forget($this->exclusiveRedisKey);
        Cache::forget($this->trendingRedisKey);
        Cache::forget($this->redisSlugKey);
    }

    public function getProductDetail(string $slug): JsonResponse
    {
        try {
            if (!$slug) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slug is missing',
                ], 419);        }

            $cacheKey = "product_detail:{$slug}";


            $product = Cache::remember($cacheKey, now()->addDay(), function () use ($slug) {
                $product = Product::with(['category', 'otherImages', 'reviews' => fn($query) => $query->latest(), 'reviews.user', 'variants'])
                    ->where('slug', $slug)
                    ->withCount('reviews')
                    ->withAvg('reviews', 'rating')
                    ->first();

                $product->policies = $product->policies()->get();

                return $product;
            });

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $product->toArray()
            ]);

        } catch (Throwable $th) {
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

            $products = Cache::remember($cacheKey, now()->addHours(12), function () use ($category) {
                return Product::with('variants')
                    ->where('category_id', $category->id)
                    ->select(['id', 'category_id', 'name', 'slug', 'image_thumbnail', 'sku',
                        'video_thumbnail', 'selling_price', 'original_price', 'discount',
                        'total_delivery_day', 'total_sold', 'default_sold', 'show_default_sold', 'is_free_delivery', 'is_export'])
                    ->withCount('reviews')
                    ->withAvg('reviews', 'rating')
                    ->where('status', 1)
                    ->latest()
                    ->get();
            });

            return response()->json( [
                'success' => true,
                'data' => $products,
                'category' => $category,
            ]);

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

            $cacheKey = "subcategory_products:{$subCategory->slug}";

            $products = Cache::remember($cacheKey, now()->addHours(12),function () use ($subCategory) {
                return Product::with('variants')
                    ->where('sub_category_id', $subCategory->id)
                    ->select(['id', 'category_id', 'name', 'slug', 'image_thumbnail', 'sku',
                        'video_thumbnail', 'selling_price', 'original_price', 'discount',
                        'total_delivery_day', 'total_sold', 'default_sold', 'show_default_sold', 'is_free_delivery', 'is_export'])
                    ->withCount('reviews')
                    ->withAvg('reviews', 'rating')
                    ->where('status', 1)
                    ->latest()
                    ->get();
            });

            $response = [
                'success' => true,
                'data' => $products,
                'category' => $subCategory->category,
                'subCategory' => $subCategory,
            ];

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
        $query = $request->input('search');

        if(empty($search)){
            return response()->json([]);
        }

        $product = Product::with('variants')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%'.$query.'%')
                    ->orWhere('sku', 'like', '%'.$query.'%');
            })
            ->select(['id', 'name', 'slug', 'image_thumbnail', 'sku',
                'video_thumbnail', 'selling_price', 'original_price', 'discount',
                'total_day_to_delivery', 'total_sold', 'is_free_delivery', 'created_at'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('status', 1)
            ->latest()
            ->get();

        // Map image path to full URL if necessary
        $product->transform(function($product){
            $product->image_thumbnail = asset($product->image_thumbnail);
            return $product;
        });

        return response()->json($product);
    }

    public function getProductsSlugs(): JsonResponse
    {
        $products = Cache::remember($this->redisSlugKey, now()->addHours(12), function () {
           return Product::where('status', 1)->get('slug');
        });

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

            $product = Product::where('slug', $slug)->first();

            // Fetch related products by product category
            $relatedProducts = Cache::remember($cacheKey, now()->addHours(12), function () use ($product, $slug) {
               return Product::with( 'variants')
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
            });

            return response()->json([
                'success' => true,
                'data' => $relatedProducts,
            ]);

        } catch (Throwable $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
