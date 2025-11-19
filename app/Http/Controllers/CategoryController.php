<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\SubCategory;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function getAllCategories()
    {
        $category = Category::with(['subCategories', 'products' => function ($query) {
            $query->latest()->take(6);
        }])->where('status', 1)->get();
        return response()->json($category);
    }

    public function index(): View
    {
        return view('admin.category.index', [
            'categories' => Category::all()
        ]);
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

    public function update(CategoryRequest $request, Category $category, CategoryService $categoryService): RedirectResponse
    {
        // check permission of current user
        isAuthorized('category edit');

        try {
           $category = $categoryService->updateOrCreate($request->validated(), $category);

           if(! $category) return back()->with('error', 'category not updated');

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
            $category->delete();

            return back()->with('success', 'Category deleted successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception);
        }
    }

    public function subCategoryProducts(SubCategory $subCategory): View
    {
        return view('website.product-page.index', [
            'title' => "$subCategory->name Products",
            'products' => Product::where('sub_category_id', $subCategory->id)
                ->where('status', 1)
                ->paginate(12),
            'categories' =>Category::with('products')
                ->where('status', 1)
                ->take(5)
                ->get()
        ]);
    }

    public function categoryProducts(Category $category): View
    {
        return view('website.product-page.index', [
            'title' => "$category->name Products",
            'products' => Product::where('category_id', $category->id)
                ->where('status', 1)
                ->paginate(12),
            'categories' => Category::with('products')
                ->where('status', 1)
                ->take(5)
                ->get()
        ]);
    }
}
