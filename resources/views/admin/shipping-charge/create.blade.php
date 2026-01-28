@extends('admin.layouts.master')

@section('title', 'Shipping Cost Create')

@section('body')
    <div class="row">
        <div class="col-xl-8 mx-auto ">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Make Shipping Cost</h4>
                    <a href="{{ route('shipping.index') }}" class="btn btn-secondary btn-sm  waves-effect waves-light">
                        Back
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('shipping.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">City name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ old('city_name') }}" name="city_name" placeholder="Enter city name" />
                            @error('city_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Charge<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ old('charge') }}" name="charge" placeholder="Enter delivery charge" />
                            @error('charge')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Free Shipping?</label>
                            <select name="is_free" class="form-select">
                                <option value="0">No – Charge Delivery</option>
                                <option value="1">Yes – Free Delivery</option>
                            </select>
                            @error('is_free')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Description</label>
                           <textarea name="description" id="summernote" class="form-control" rows="5"></textarea>
                            @error('description')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="radio" class="form-check-input" id="formrow-for-published" checked name="status" value="1" />
                                    <label for="formrow-for-published" class="form-check-label">Active</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="radio" class="form-check-input" id="formrow-for-unpublished" name="status" value="0" />
                                    <label for="formrow-for-unpublished" class="form-check-label" >Inactive</label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary w-md">Create</button>
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

@push('scripts')
    <script>
        // $(document).ready(function() {
        //     $('#summernote').summernote({
        //         placeholder: '* Some point about shipping <br /> * Hello World',
        //     });
        // });
    </script>
@endpush
