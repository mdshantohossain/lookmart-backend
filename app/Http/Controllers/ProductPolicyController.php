<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductPolicyRequest;
use App\Models\ProductPolicy;
use App\Services\ProductPolicyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class ProductPolicyController extends Controller
{
    protected string $cachedKey = 'productPolicies';

    public function index(): View
    {
        $productPolicies = Cache::remember($this->cachedKey, now()->addDay(), function () {
            return ProductPolicy::all();
        });

        return view('admin.product-policy.index', compact('productPolicies'));
    }

    public function create(): View
    {
        // check permission of current user
        isAuthorized('product policy create');

        return view('admin.product-policy.create');
    }

    public function store(ProductPolicyRequest $request, ProductPolicyService $productPolicyService): RedirectResponse
    {
        // check permission of current user
        isAuthorized('product policy create');
        $productPolicy = $productPolicyService->updateOrCreate($request->all());

        if(!$productPolicy) {
            return redirect('/product-policies')->with('error', 'Product policy was not saved!');
        }

        // Update Redis cache after creating
        $this->updateRedisCache();

        return redirect('/product-policies')->with('success', 'Product policy created successfully.');
    }

    public function edit(ProductPolicy $productPolicy): View
    {
        // check permission of current user
        isAuthorized('product policy edit');

        return view('admin.product-policy.edit', compact('productPolicy'));
    }

    public function update(ProductPolicyRequest $request, ProductPolicy $productPolicy, ProductPolicyService $productPolicyService): RedirectResponse
    {
        // check permission of current user
        isAuthorized('product policy edit');

        $productPolicy = $productPolicyService->updateOrCreate($request->all(), $productPolicy);

        if(!$productPolicy) {
            return redirect('/product-policies')->with('error', 'Product policy was not updated!');
        }

        // Update Redis cache after updating
        $this->updateRedisCache();

        return redirect('/product-policies')->with('success', 'Product policy updated successfully.');
    }

    public function destroy(ProductPolicy $productPolicy): RedirectResponse
    {
        // check permission of current user
        isAuthorized('product policy destroy');

        if($productPolicy->image) {
            removeImage($productPolicy->image);
        }

        $productPolicy->delete();

        // Update Redis cache after deleting
        $this->updateRedisCache();

        return redirect('/product-policies')->with('success', 'Product policy deleted successfully!');
    }

    public function updateRedisCache(): void
    {
        Cache::forget($this->cachedKey);
    }
}
