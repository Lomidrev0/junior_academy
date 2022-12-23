@extends('layouts.main')

@section('authContent')
    <div>
        <div class="container">
            <course-detail
                    @isset($course)
                    :course="{{json_encode($course)}}"
                    @endisset
                    @isset($users)
                    :users="{{$users}}"
                    @endisset
            ></course-detail>
        </div>
    </div>
@endsection
