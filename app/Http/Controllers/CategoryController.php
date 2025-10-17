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
        $category = Category::where('status', 1)->get();
        return response()->json($category);
    }

    public function index(): View
    {
         $res = Http::withHeaders([
             'CJ-Access-Token' => env('CJ_ACCESS_TOKEN')
         ])->get("https://developers.cjdropshipping.com/api2.0/v1/product/getCategory");

         $categories = $res->json('data');

         foreach($categories as $category) {
             // category created
             $firstCategory = Category::updateOrCreate(
                 ['cj_id' => $category['categoryFirstId']],
                 [
                     'name' => $category['categoryFirstName'],
                     'slug' => Str::slug($category['categoryFirstName']),
                 ]
             );

             foreach($category['categoryFirstList'] as $subCategory) {
                SubCategory::updateOrCreate(
                     ['cj_id' => $subCategory['categorySecondId']],
                     [
                         'category_id' => $firstCategory->id,
                         'name' => $subCategory['categorySecondName'],
                         'slug' => Str::slug($subCategory['categorySecondName']),
                     ]
                 );
                 if(! empty($subCategory['categorySecondList'])) {
                     foreach ($subCategory['categorySecondList'] as $subSubCategory) {
                         SubCategory::updateOrCreate(
                             ['cj_id' => $subSubCategory['categoryId']],
                             [
                                 'category_id' => $firstCategory->id,
                                 'name' => $subSubCategory['categoryName'],
                                 'slug' => Str::slug($subSubCategory['categoryName']),
                             ]
                         );
                     }
                 }
             }
         }

        return view('admin.category.index', [
            'categories' => Category::all()
        ]);
    }

    public function create(): View
    {
        return view('admin.category.create');
    }

    public function store(CategoryRequest $request, CategoryService $categoryService): RedirectResponse
    {
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
        return view('admin.category.edit', [
            'category' => $category
        ]);
    }

    public function update(CategoryRequest $request, Category $category, CategoryService $categoryService): RedirectResponse
    {
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
