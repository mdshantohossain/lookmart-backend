<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Admin\Category;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    protected string $cacheKey = 'api.categories', $adminCacheKey = 'all.categories';

    public function getAllCategories()
    {
        $categories = Cache::remember($this->cacheKey, now()->addHours(12), function () {
            return Category::with(['subCategories', 'products' => function ($query) {
                $query->select(['id', 'category_id', 'name', 'slug', 'image_thumbnail', 'sku',
                    'video_thumbnail', 'selling_price', 'original_price', 'discount',
                    'total_delivery_day', 'total_sold', 'default_sold', 'show_default_sold', 'is_free_delivery', 'is_export'])
                    ->withCount('reviews')
                    ->withAvg('reviews', 'rating')
                    ->latest()
                    ->take(6);
            }])
                ->withCount('products')
                ->where('status', 1)->get();
        });

        return response()->json($categories);
    }

    public function index(): View
    {
        $categories = Cache::remember($this->adminCacheKey, now()->addHours(6), function () {
            return Category::withCount('subCategories')->latest()->get();
        });

        return view('admin.category.index', compact('categories'));
    }

    public function create(): View
    {
        // check permission of current user
        isAuthorized('category create');

        return view('admin.category.create');
    }

    public function store(CategoryRequest $request, CategoryService $categoryService): RedirectResponse
    {
        // check permission of current user
        isAuthorized('category create');

        try {
            $category = $categoryService->updateOrCreate($request->validated());

            if (!$category) return back()->with('error', 'category not created');

            // updating redis cache after create
            $this->updateRedisCacheForCategory();

            return redirect('/categories')->with('success', 'Category created successfully');

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function edit(Category $category): View
    {
        // check permission of current user
        isAuthorized('category edit');

        return view('admin.category.edit', [
            'category' => $category
        ]);
    }

    public function update(CategoryRequest $request, Category $category, CategoryService $categoryService)
    {
        // check permission of current user
        isAuthorized('category edit');

        try {
            $category = $categoryService->updateOrCreate($request->validated(), $category);

            if(! $category) return back()->with('error', 'category not updated');

            // updating redis cache after updating
            $this->updateRedisCacheForCategory();

            return redirect('/categories')->with('success', 'Category updated successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception);
        }
    }

    public function destroy(Category $category): RedirectResponse
    {
        // check permission of current user
        isAuthorized('category destroy');

        try {
            if($category->image) {
                removeImage($category->image);
            }
            $category->delete();

            // updating redis cache after delete
            $this->updateRedisCacheForCategory();

            return back()->with('success', 'Category deleted successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception);
        }
    }

    public function updateRedisCacheForCategory(): void
    {
        Cache::forget($this->cacheKey);
        Cache::forget($this->adminCacheKey);
    }
}
