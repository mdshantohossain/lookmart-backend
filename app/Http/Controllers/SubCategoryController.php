<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubCategoryRequest;
use App\Models\Admin\Category;
use App\Models\Admin\SubCategory;
use App\Services\SubCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class SubCategoryController extends Controller
{
    protected string $redisKey = 'all.subCategories';

    public function index(): View
    {
        $subCategories = Cache::remember($this->redisKey, now()->addHours(6), function () {
            return SubCategory::with('category')->get();
        });

        return view('admin.sub-category.index', compact('subCategories'));
    }

    public function create(): View
    {
        // check permission of request user
        isAuthorized('sub-category create');

        return view('admin.sub-category.create', [
            'categories' => Category::where('status', 1)->get()
        ]);
    }

    public function store(SubCategoryRequest $request, SubCategoryService $subCategoryService): RedirectResponse
    {
        // check permission of request user
        isAuthorized('sub-category create');

        $subCategory = $subCategoryService->updateOrCreate($request->validated());

        if (!$subCategory) return back()->with('error', 'Sub category not created');

        // updating redis cache after create
        $this->updateRedisCacheForSubCategory();

        return  redirect('/sub-categories')->with('success', 'Sub category created successfully');
    }

    public function edit(SubCategory $subCategory): View
    {
        // check permission of request user
        isAuthorized('sub-category edit');

        return view('admin.sub-category.edit', [
            'subCategory' => $subCategory,
            'categories' => Category::where('status', 1)->get()
        ]);
    }

    public function update(SubCategoryRequest $request, SubCategory $subCategory, SubCategoryService $subCategoryService)
    {
        // check permission of request user
        isAuthorized('sub-category edit');

        $subCategory = $subCategoryService->updateOrCreate($request->validated(), $subCategory);

        if(!$subCategory) return back()->with('error', 'Sub category not created');

        // updating redis cache after update
        $this->updateRedisCacheForSubCategory();

        return redirect('/sub-categories')->with('success', 'Sub category updated successfully');

    }

    public function destroy(SubCategory $subCategory): RedirectResponse
    {
        // check permission of request user
        isAuthorized('sub-category destroy');

        try {
            $subCategory->delete();

            // updating redis cache after delete
            $this->updateRedisCacheForSubCategory();

            return back()->with('success', 'Sub category deleted successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function updateRedisCacheForSubCategory(): void
    {
        Cache::forget($this->redisKey);
    }

    public function getSubCategories(int $categoryId)
    {
        try {
            if (!$categoryId) return 'category id not found';

            $subCategories = SubCategory::where('category_id', $categoryId)
                                        ->where('status', 1)
                                        ->get();

            return response()->json($subCategories);

        } catch (\Throwable $e) {
            report($e);
            return response()->json($e->getMessage());
        }
    }
}
