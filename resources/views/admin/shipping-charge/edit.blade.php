@extends('admin.layouts.master')

@section('title', 'Shipping Cost Edit')

@section('body')
    <div class="row">
        <div class="col-xl-8 mx-auto ">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Make Shipping Cost</h4>
                    <a href="{{ route('shipping.index') }}" class="btn btn-secondary waves-effect waves-light">
                        Back
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('shipping.update', $shipping->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">City name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ $shipping->city_name }}" name="city_name" placeholder="Enter city name" />
                            @error('city_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Charge<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ $shipping->charge }}" name="charge" placeholder="Enter delivery charge" />
                            @error('charge')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Free Shipping?</label>
                            <select name="is_free" class="form-select">
                                <option value="0" {{ $shipping->is_free == 0 ? 'selected' : '' }}>No – Charge Delivery</option>
                                <option value="1" {{ $shipping->is_free == 1 ? 'selected' : '' }}>Yes – Free Delivery</option>
                            </select>
                            @error('is_free')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formrow-firstname-input" class="form-label">Description</label>
                           <textarea name="description" id="summernote" class="form-control" rows="5">{{ $shipping->description }}</textarea>
                            @error('description')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="radio" class="form-check-input" id="formrow-for-published" {{ $shipping->status === 1 ? 'checked': '' }} checked name="status" value="1" />
                                    <label for="formrow-for-published" class="form-check-label">Active</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="radio" class="form-check-input" id="formrow-for-unpublished" {{ $shipping->status === 0 ? 'checked': '' }} name="status" value="0" />
                                    <label for="formrow-for-unpublished" class="form-check-label" >Inactive</label>
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

@push('scripts')
{{--    <script>--}}
{{--        $(document).ready(function() {--}}
{{--            $('#summernote').summernote();--}}
{{--        });--}}
{{--    </script>--}}
@endpush



