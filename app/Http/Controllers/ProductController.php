<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\SubCategory;
use App\Models\OtherImage;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use function PHPUnit\Framework\logicalOr;
use function React\Promise\all;

class ProductController extends Controller
{
    public function getAllProducts(): JsonResponse
    {
        $products = Product::where('status', 1)->take(10)->get();
        return response()->json($products);
    }

    public function index(): View
    {
        return view('admin.product.index', [
            'products' => Product::with('category')->latest()->take(20)->get()
        ]);
    }

    public function create(): View
    {
        // check permission of current user
        isAuthorized('product create');

        return view('admin.product.create', [
            'categories' => Category::where('status',  1)->get(),
            'subCategories' => SubCategory::where('status',  1)->get(),
            ]);
    }

    public function store(ProductCreateRequest $request, ProductService $productService): RedirectResponse
    {
        // check permission of request user.
        isAuthorized('product create');

        $product = $productService->createOrUpdate($request->all());

        if (!$product) {
            return back()->with('error', 'Product could not be created');
        }

        return redirect('/products')->with('success', 'Product created successfully');
    }

    public function show(Product $product): View
    {
        // check permission of request user
        isAuthorized('product show');

        $product->load(['category', 'subCategory', 'otherImages']);

        return view('admin.product.detail', [
            'product' => $product
        ]);
    }

    public function edit(Product $product)
    {
        // check permission of request user
        isAuthorized('product edit');

        if (!empty($product->color_images)) {
            $product->color_images = json_decode($product->color_images, true);
        }

        return view('admin.product.edit', [
            'categories' => Category::where('status',  1)->get(),
            'subCategories' => SubCategory::where('status',  1)->get(),
            'product' => $product->load('variants')
        ]);
    }

    public function update(Request $request, Product $product)
    {
        return $request;
        // check permission of current user
        isAuthorized('product edit');

        try {
            $inputs = $request->only([
                'category_id',
                'sub_category_id',
                'name',
                'regular_price',
                'selling_price',
                'cj_id',
                'buy_price',
                'sku',
                'discount',
                'quantity',
                'short_description',
                'long_description',
                'status'
            ]);

            if ($request->hasFile('main_image')) {
                if(file_exists($product->main_image)) {
                    unlink($product->main_image);
                }
                $inputs['main_image'] = $this->getImageUrl($request->file('main_image'), 'admin/assets/images/product-images/');
            }

            $product->update($inputs);

            if ($request->hasFile('other_images')) {
                foreach ($product->otherImages  as $otherImage) {
                    if (file_exists($otherImage)) {
                        unlink($otherImage);
                    }
                    $otherImage->delete();
                }
                foreach ($request->file('other_images') as $otherImage) {
                    OtherImage::create([
                        'product_id' => $product->id,
                        'image' => $this->getImageUrl($otherImage, 'admin/assets/images/other-images/')
                    ]);
                }
            }

            return redirect('products')->with('success', 'Product updated successfully');

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception);
        }
    }

    public function destroy(Product $product): RedirectResponse
    {
        // check permission of request user
        isAuthorized('product destroy');

        try {

            $otherImages = OtherImage::where('product_id', $product->id)->get();
            foreach ($otherImages as $otherImage) {
                if (file_exists($otherImage->image))
                {
                    unlink($otherImage->image);
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
        $product = Product::with(['category', 'otherImages', 'reviews', 'variants'])->where('slug', $slug)->first();

        if (!$product) return response()->json([
            'error', 'Product not found'
        ]);
        return response()->json($product);
    }

    public function getCategoryProducts(string $slug): JsonResponse
    {
        try {
            $category = Category::where('slug', $slug)->first();
            $products = Product::where('category_id', $category->id)
                ->latest('id')
                ->get();

            return response()->json($products);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
                'code' => 500
            ]);
        }
    }

    public function getSubCategoryProducts(string $slug): JsonResponse
    {
        try {
            $subCategory = SubCategory::where('slug', $slug)->first();

            $products = Product::where('sub_category_id', $subCategory->id)
                ->latest('id')
                ->get();

            return response()->json($products);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
                'code' => 500
            ]);
        }
    }
}
