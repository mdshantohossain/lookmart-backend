@extends('admin.layouts.master')

@section('title', 'Sub category edit')

@section('body')
    <div class="row">
        <div class="col-xl-6 mx-auto ">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="">Sub-category edit form</h4>
                        <a href="{{ route('sub-categories.index') }}" class="btn btm-sm btn-primary waves-effect waves-light">
                            Back
                        </a>
                    </div>
                    <form method="POST" action="{{ route('sub-categories.update', $subCategory->slug) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <select name="category_id" class="form-select">
                                <option value="">Select category</option>
                                @forelse($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        {{ $category->id === $subCategory->category_id ? 'selected' : null }}
                                    >{{ $category->name }}</option>
                                @empty
                                    <option value="">haven't any category</option>
                                @endforelse
                            </select>
                            @error('category_id')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ $subCategory->name }}" name="name" placeholder="Enter category name" />
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="radio" class="form-check-input" id="formrow-for-published" @checked($subCategory->status === 1) name="status" value="1" />
                                    <label for="formrow-for-published" class="form-check-label">Publish</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="radio" class="form-check-input" id="formrow-for-unpublished" @checked($subCategory->status === 0) name="status" value="0" />
                                    <label for="formrow-for-unpublished" class="form-check-label" >Unpublish</label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary w-md">Update</button>
                        </div>
                    </form>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
@endsection
