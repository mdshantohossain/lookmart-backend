@extends('admin.layouts.master')

@section('title', 'Cj Token')

@section('body')
    <div class="row">
        <div class="col-8 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="">CJ Token</h4>
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary waves-effect waves-light">
                            Back
                        </a>
                    </div>

                    <form action="{{ route('cj.token.store') }}" method="POST">
                        @csrf
                        @if($token)
                            @method('PUT')
                        @endif
                        <div class="row mb-3">
                            <label for="">API Token</label>
                            <textarea name="token" rows="10" placeholder="Enter your cj api key" class="form-control">{{ $token }}</textarea>
                            @error('token')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <button type="submit" class="btn btn-primary">{{ $token ? 'Update' : 'Save' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
