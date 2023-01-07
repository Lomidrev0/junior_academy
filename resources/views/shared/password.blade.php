@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>{{ __('Change password') }}</h1>
            <hr>
            <p>{{ __('You can change your password here') }}</p>

            @if(session()->has('message'))
                @if(session()->get('message') == 1)
                    <div id="message" class="alert alert-danger">
                        {{ __('New password matches old') }}
                    </div>
                @elseif(session()->get('message') == 0)
                <div id="message" class="alert alert-success">
                    {{  __('Password has been changed successfully') }}
                </div>
                @else
                    <div id="message" class="alert alert-danger">
                        {{  __('Incorrect password') }}
                    </div>
                @endif
            @endif
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Reset Password') }}</div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('reset') }}">
                                @csrf
                                <div class="row mb-3">
                                    <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror pass" name="password" required autocomplete="new-password">

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control pass" name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm by password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control pass" name="old_password" required autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Reset Password') }}
                                        </button>
                                        <input type="checkbox" class="showPass">Show Password
                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

