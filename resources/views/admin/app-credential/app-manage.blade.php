@extends('admin.layouts.master')

@section('title', 'App Manage')

@section('body')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                    <div class="card-body">
                        <a class="h4 d-block mb-2" role="button">
                            App manage
                        </a>

                        <form method="POST" action="{{ route('app.manage') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">App name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{ $app?->app_name }}" name="app_name" placeholder="Enter your app name" />
                                @error('app_name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{ $app?->phone }}" name="phone"  placeholder="Enter phone to contact"  />
                                @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{ $app?->email }}" name="email" placeholder="Enter app email to mail"  />
                                @error('email')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">App Logo<span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="logo"  />
                                @error('logo')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror

                                @if($app?->logo)
                                    <img src="{{ $app?->logo }}" class="rounded-2 mt-2" width="120" height="80" alt="" />
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Favicon<span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="favicon" placeholder="Enter your app name" />
                                @error('favicon')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror

                                @if($app?->favicon)
                                    <img src="{{ $app->favicon }}" class="rounded-2 mt-2" width="80" height="60" alt="" />
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" placeholder="Enter office address" name="address" rows="3">{{ $app?->address }}</textarea>
                                @error('address')
                                  <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" value="{{ $app?->location }}" name="location" placeholder="Enter office location" />
                                @error('location')
                                 <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" placeholder="Tell about app" name="description" rows="3">{{ $app?->description }}</textarea>
                                @error('description')
                                 <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row px-2">
                                <button type="submit" class="btn btn-primary w-md">{{ $app ? 'Update' : 'Save' }}</button>
                            </div>
                        </form>

                    </div>
            </div>
        </div>
    </div>
@endsection
