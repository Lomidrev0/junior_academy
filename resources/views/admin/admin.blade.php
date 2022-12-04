@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>{{ __('Add user') }}</h1>
            <hr>
            <p>{{ __('You can add another user here') }}</p>
        </div>
        <div>
            <add-user
                @isset($courses)
                   :courses="{{$courses}}"
                @endisset
            ></add-user>
        </div>
{{--        <div id="admin-reegister-form" class="container">--}}
{{--            <div class="row justify-content-center">--}}
{{--                <div class="col-md-8">--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-header">{{ __('Register') }}</div>--}}

{{--                        <div class="card-body">--}}
{{--                            <form id="admin-form" method="POST" action="{{ route('admin.add') }}">--}}
{{--                                @csrf--}}

{{--                                <div class="row mb-3">--}}
{{--                                    <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name and surname') }}</label>--}}

{{--                                    <div class="col-md-6">--}}
{{--                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>--}}

{{--                                        @error('name')--}}
{{--                                        <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="row mb-3">--}}
{{--                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email address') }}</label>--}}

{{--                                    <div class="col-md-6">--}}
{{--                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">--}}

{{--                                        @error('email')--}}
{{--                                        <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="row mb-3">--}}
{{--                                    <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Confirm by password') }}</label>--}}

{{--                                    <div class="col-md-6">--}}
{{--                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror pass" name="password" required autocomplete="new-password">--}}

{{--                                        @error('password')--}}
{{--                                        <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="row mb-0">--}}
{{--                                    <div class="col-md-6 offset-md-4">--}}
{{--                                        <button type="submit" class="btn btn-primary">--}}
{{--                                            {{ __('Add admin') }}--}}
{{--                                        </button>--}}
{{--                                        <input type="checkbox" id="showPass">Show Password--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
@endsection
