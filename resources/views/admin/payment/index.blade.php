@extends('admin.layouts.master')

@section('title', 'Payment Gateway Setting')

@section('body')
    <div class="row">
        <div class="col-8 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="">Payment Gateway Setting</h4>
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary waves-effect waves-light">
                            Back
                        </a>
                    </div>

                    <form action="{{ route('payment.setting.store') }}" method="POST">
                        @csrf
                        @if($setting)
                            @method('PUT')
                        @endif

                        <div class="row mb-3">
                            <label for="">Api Key<span class="text-danger">*</span></label>
                            <textarea name="api_key" rows="5" class="form-control">{{ $setting?->api_key }}</textarea>
                            @error('api_key')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <button type="submit" class="btn btn-primary">{{ $setting ? 'Update' : 'Save' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
