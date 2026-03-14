@extends('admin.layouts.master')

@section('title', 'Email Setting')

@section('body')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">
                        <span class="h4 d-block mb-2" >
                            Email Setting
                        </span>
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i> <span>These settings control how your application sends emails. Only change them if necessary.</span>
                    </div>

                    <form method="POST" action="{{ url('/mail-setting') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Mail Host<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ config('mail.mailers.smtp.host') }}" name="mail_host" placeholder="Enter mail host" />
                            @error('mail_host')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mail Port<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ config('mail.mailers.smtp.port') }}" name="mail_port" placeholder="Enter mail port" />
                            @error('mail_port')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mail Username<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ config('mail.mailers.smtp.username') }}" name="mail_username" placeholder="Enter mail username" />
                            @error('user_username')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mail Password<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ config('mail.mailers.smtp.password') }}" name="mail_password" placeholder="Enter mail password" />
                            @error('mail_password')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mail Encryption</label>
                            <input type="text" class="form-control" value="{{ config('mail.mailers.smtp.encryption') }}" name="mail_encryption" placeholder="tls, ssl etc" />
                            @error('mail_encryption')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mail From Address<span class="text-danger">*</span></label>
                            <input type="email" class="form-control" value="{{ config('mail.from.address') }}" name="mail_from_address" placeholder="Enter mail from address" />
                            @error('mail_from_address')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mail From Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ config('mail.from.name') }}" name="mail_from_name" placeholder="Enter mail from name" />
                            @error('mail_from_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row px-2">
                            <button type="submit" class="btn btn-primary w-md">Save</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // setTimeout(() => {
        //     location.reload(); // reloads the current page
        // }, 300);
    </script>
@endpush

