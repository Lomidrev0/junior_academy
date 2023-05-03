@extends('front.main')

@section('frontContent')
<div class="container">
    <div class="row justify-content-center special-height">
        <div class="col-md-8 my-10vh">
            @if($isPermited)
                <div class="card card-style">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body custom-card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name and surname') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="school" class="col-md-4 col-form-label text-md-end">{{ __('School') }}</label>

                                <div class="col-md-6">
                                    <input id="school" type="text" class="form-control @error('school') is-invalid @enderror" name="school" value="{{ old('school') }}" required autocomplete="school">

                                    @error('school')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="class" class="col-md-4 col-form-label text-md-end">{{ __('Class') }}</label>

                                <div class="col-md-6">
                                    <input id="class" type="text" class="form-control @error('class') is-invalid @enderror" name="class" value="{{ old('class') }}" required autocomplete="class">

                                    @error('school')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

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
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>
                            @if(count($courses) > 0)
                                <div class="register-h">
                                    <h5>{{__('I am signing up for courses')}}</h5>
                                </div>
                                <div class="d-flex flex-column register-wrapper" id="course-list">
                                    @foreach($courses as $course)
                                        <div class="register-item">
                                            <label for="{{ $course->id }}">
                                                <img src="{{$course->media[0]->original_url}}" alt="">
                                            </label>

                                            <input class="mx-2" id="{{ $course->id }}" type="checkbox" value="{{ $course->id }}" name="courses[]">
                                            <label class="cursor-pointer" for="{{ $course->id }}">{{ $course->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="register-h">
                                    <h5>{{__('No courses found')}}</h5>
                                </div>
                            @endif


                            <div class="d-flex w-100 my-3 justify-content-center">
                                <button type="submit" class="button_first {{count($courses) > 0 ? '':'disabled'}}">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div>
                    <no-results
                            header="{{__('Registration is currently not open')}}"
                            body="{{__('Registration has been disabled by the administrator.')}}"
                    ></no-results>
                    <div class="my-3">
                        <div class="d-flex my-3 flex-wrap justify-content-center">
                            <p class="mx-2 text-center">
                                {{__('In case of questions, do not hesitate to write us at this email address:')}}
                                &nbsp;
                                <a href="mailto:spse@spse-po.sk">spse@spse-po.sk</a>
                                <br>
                                {{__('Or see the offer of our courses')}}:
                            </p>
                        </div>
                        <div class="d-flex w-100 justify-content-center">
                            <a href="{{route('front-home')}}#course-list"><button class="button_first">{{__('Courses overview')}}</button></a>
                        </div>
                    </div>
                    <div>
                        <p class="text-center my-3">{{__('If you want to receive timely information about the resumption of registration for courses, write us your email address:')}}</p>
                      <div>
                          <form id="watch-dog-form" class="d-flex justify-content-center" novalidate>
                              <input class="inut-text" type="email" name="watchDog" required>
                              <button type="submit"  class="button_first rigth-addition" onclick="addWatchDog(event)">
                                {{__('Submit')}}
                              </button>
                          </form>
                      </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
