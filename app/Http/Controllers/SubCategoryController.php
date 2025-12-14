<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubCategoryRequest;
use App\Models\Admin\Category;
use App\Models\Admin\SubCategory;
use App\Services\SubCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redis;
use Illuminate\View\View;

class SubCategoryController extends Controller
{
    protected string $redisKey = 'subCategory', $redisField = 'all';

    public function index(): View
    {
        $cached = Redis::hget($this->redisKey, $this->redisField);

        if($cached) {
            $subCategories = json_decode($cached);
        } else {
            $subCategories = SubCategory::with('category')->get();

            // caching in redis
            Redis::hset($this->redisKey, $this->redisField, json_encode($subCategories));
        }

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

    public function  update(SubCategoryRequest $request, SubCategory $subCategory, SubCategoryService $subCategoryService)
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
        $subCategories = SubCategory::with('category')->get();
        Redis::hset($this->redisKey, $this->redisField, json_encode($subCategories));
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
