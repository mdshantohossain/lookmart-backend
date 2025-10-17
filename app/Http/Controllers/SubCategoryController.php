<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubCategoryRequest;
use App\Models\Admin\Category;
use App\Models\Admin\SubCategory;
use App\Services\SubCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.sub-category.index', [
            'subCategories' => SubCategory::with('category')->get()
        ]);
    }

    public function create(): View
    {
        return view('admin.sub-category.create', [
            'categories' => Category::where('status', 1)->get()
        ]);
    }

    public function store(SubCategoryRequest $request, SubCategoryService $subCategoryService): RedirectResponse
    {
        $subCategory = $subCategoryService->updateOrCreate($request->validated());

        if (!$subCategory) return back()->with('error', 'Sub category not created');

        return  redirect('/sub-categories')->with('success', 'Sub category created successfully');
    }

    public function edit(SubCategory $subCategory): View
    {
        return view('admin.sub-category.edit', [
            'subCategory' => $subCategory,
            'categories' => Category::where('status', 1)->get()
        ]);
    }

    public function  update(SubCategoryRequest $request, SubCategory $subCategory, SubCategoryService $subCategoryService)
    {
        $subCategory = $subCategoryService->updateOrCreate($request->validated(), $subCategory);

        if(!$subCategory) return back()->with('error', 'Sub category not created');

        return redirect('/sub-categories')->with('success', 'Sub category updated successfully');

    }

    public function destroy(SubCategory $subCategory): RedirectResponse
    {
        try {
            $subCategory->delete();

            return back()->with('success', 'Sub category deleted successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function getSubCategories(int $categoryId)
    {
        try {
            if (!$categoryId) return 'category id not found';

            $subCategories = SubCategory::where('category_id', $categoryId)
                                        ->where('status', 1)
                                        ->get();

            return response()->json($subCategories);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
