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
use Illuminate\View\View;

class ProductController extends Controller
{
    // get exclusive section product on frontend
    public function getExclusiveProducts(): JsonResponse
    {
        $products = Product::with('variants')
                        ->select(['id', 'name', 'slug', 'main_image', 'selling_price', 'regular_price', 'discount']) // add more field if needed
                        ->withCount('reviews')
                        ->withAvg('reviews', 'rating')
                        ->where('status', 1)
                        ->take(30)
                        ->get();

        return response()->json($products);
    }

    // get tending section product on frontend
    public function getTrendingProducts(): JsonResponse
    {
        $products = Product::with('variants')
                        ->select(['id', 'name', 'slug', 'main_image', 'selling_price', 'regular_price', 'discount']) // add more field if needed
                        ->withCount('reviews')
                        ->withAvg('reviews', 'rating')
                        ->where('status', 1)
                        ->where('is_trending', 1)
                        ->take(20)
                        ->get();

        return response()->json($products);
    }

    public function index(): View
    {
        return view('admin.product.index', [
            'products' => Product::with('category')->latest()->get(['name', 'slug', 'thumbnail', 'selling_price', 'category_id', 'status'])
        ]);
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

        $storedProduct = $productService->updateOrCreate($request->all(), $product);

        if (!$storedProduct) {
            return back()->with('error', 'Product could not be updated');
        }

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

            $product->delete();

            return back()->with('success', 'Product deleted successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception);
        }
    }

    public function getProductDetail(string $slug): JsonResponse
    {
        if (!$slug) {
            return response()->json([
                'success' => false,
                'message' => 'Slug is missing',
            ], 419);
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
            ->select(['id', 'name', 'slug', 'main_image', 'selling_price', 'regular_price', 'discount']) // add more field if needed
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'product' => $product,
                'relatedProducts' => $relatedProducts,
            ]
        ]);
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
            $products = Product::where('category_id', $category->id)
                ->select(['name', 'slug', 'main_image', 'selling_price', 'regular_price', 'discount'])
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $products,
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

            $products = Product::where('sub_category_id', $subCategory->id)
                ->select(['name', 'slug', 'main_image', 'selling_price', 'regular_price', 'discount'])
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
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
