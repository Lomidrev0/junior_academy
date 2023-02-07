@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>{{ __('Change password') }}</h1>
            <hr>
            <info-card
                    :text="'{{ __('You can change your password here') }}'"
            ></info-card>
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
                    <div class="card card-style">
                        <div class="card-header">{{ __('Reset Password') }}</div>
                        <div class="card-body custom-card-body">
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
                                <div class="d-flex flex-row mb-3 checkbox-wrapper">
                                    <label  class="col-md-4 col-form-label text-md-end">Show Password</label>
                                    <div class="checkbox-wrapper-31">
                                        <input type="checkbox" class="showPass"/>
                                        <svg viewBox="0 0 35.6 35.6">
                                            <circle class="background" cx="17.8" cy="17.8" r="17.8"></circle>
                                            <circle class="stroke" cx="17.8" cy="17.8" r="14.37"></circle>
                                            <polyline class="check" points="11.78 18.12 15.55 22.23 25.17 12.87"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="button_first">
                                            {{ __('Reset Password') }}
                                        </button>
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

