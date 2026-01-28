@extends('admin.layouts.master')

@section('title', 'App Credential')

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <span class="h2">Application's All Credential</span>
                </div>
                <div class="col-md-10 mx-auto">
{{--                    google credential--}}
                    <div class="card-body">
                        <a class="h4 d-block mb-2" data-bs-toggle="collapse" href="#googleLoginCredential" role="button" aria-expanded="false" aria-controls="googleLoginCredential">
                            Google Login Credential
                            <i class="chevron-icon fa fa-chevron-down" data-target="#googleLoginCredential"></i>
                        </a>

                        <div class="collapse" id="googleLoginCredential">
                            <form method="POST" action="{{ route('change.google.login.credential') }}">
                                @csrf
                                <input type="hidden" name="credential_for" value="google_login" />
                                <div class="mb-3">
                                    <label class="form-label">Google Client Id <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $googleLoginCredential->client_id ?? old('google_client_id') }}" name="google_client_id" placeholder="google client id" />
                                    @error('google_client_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Google Client Secret <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $googleLoginCredential->client_secret ?? old('google_client_secret') }}" name="google_client_secret" placeholder="google client secret" />
                                    @error('google_client_secret')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <button type="submit" class="btn btn-primary w-md">{{ $googleLoginCredential ? 'Update' : 'Save' }}</button>
                                </div>
                            </form>
                        </div>
                    </div>

{{--                    facebook credential--}}
                    <div class="card-body">
                        <a class="h4 d-block mb-2" data-bs-toggle="collapse" href="#facebookLoginCredential" role="button" aria-expanded="false" aria-controls="facebookLoginCredential">
                            Facebook Login Credential
                        </a>

                        <div class="collapse" id="facebookLoginCredential">
                            <!-- Another form or fields here -->
                            <form method="POST" action="{{ route('change.facebook.login.credential') }}">
                                @csrf
                                <input type="hidden" name="credential_for" value="facebook_login" />
                                <div class="mb-3">
                                    <label class="form-label">Facebook Client ID<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $facebookLoginCredential->client_id ?? old('facebook_client_id') }}" name="facebook_client_id" placeholder="facebook client id" />
                                    @error('facebook_client_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Facebook Client Secret<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $facebookLoginCredential->client_secret ?? old('facebook_client_secret') }}" name="facebook_client_secret" placeholder="facebook client secret" />
                                    @error('facebook_client_secret')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <button type="submit" class="btn btn-primary w-md">{{ $facebookLoginCredential ? 'Update' : 'Save' }}</button>
                                </div>
                            </form>
                        </div>
                    </div>

{{--                    mail credential--}}
                    <div class="card-body">
                        <a class="h4 d-block mb-2" data-bs-toggle="collapse" href="#mailSendCredential" role="button" aria-expanded="false" aria-controls="facebookLoginCredential">
                            Mail Send Credential
                        </a>

                        <div class="collapse" id="mailSendCredential">
                            <!-- Another form or fields here -->
                            <form method="POST" action="{{ route('change.mail.credential') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Mail Mailer<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $mailCredential->client_id ?? old('mail_mailer') }}" name="mail_mailer" placeholder="mail mailer" />
                                    @error('mail_mailer')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mail Scheme<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $mailCredential->mail_scheme ?? old('mail_scheme') }}" name="mail_scheme" placeholder="mail scheme" />
                                    @error('mail_scheme')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mail Host<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $mailCredential->mail_host ?? old('mail_host') }}" name="mail_host" placeholder="mail host" />
                                    @error('mail_host')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mail Port<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $mailCredential->mail_port ?? old('mail_port') }}" name="mail_port" placeholder="mail port" />
                                    @error('mail_port')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mail Username<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $mailCredential->mail_username ?? old('mail_username') }}" name="mail_username" placeholder="mail username" />
                                    @error('mail_username')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mail Password<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $mailCredential->mail_password ?? old('mail_password') }}" name="mail_password" placeholder="mail password" />
                                    @error('mail_password')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mail From Address<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $mailCredential->mail_from_address ?? old('mail_from_address') }}" name="mail_from_address" placeholder="mail from address" />
                                    @error('mail_from_address')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <button type="submit" class="btn btn-primary w-md">{{ $facebookLoginCredential ? 'Update' : 'Save' }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
