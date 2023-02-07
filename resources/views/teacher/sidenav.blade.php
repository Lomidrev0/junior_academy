<ul>
    <li>
        @if($courses->count() > 0)
        <form action="{{route('change-course')}}" method="POST" class="d-flex">
            @csrf
            <label for="id" class="select-wrapper">
                 <span>Kurz: </span>
                <select name="id" onchange="this.form.submit()"  class="select">

                    @foreach ($courses as $course)
                        <option value="{{$course->id}}" {{ Auth::user()->getSelectedCourse()->id === $course->id ? 'selected' : '' }}>
                            {{$course->name}}
                        </option>
                    @endforeach
                </select>
            </label>
        </form>
        @endif
    </li>
    <li>
        <a href="{{route('teacher.home')}}">
            <i class="bi bi-house-fill"></i>
            <span>{{__('Home')}}</span>
        </a>
    </li>
    <li>
        <a href="{{route('teacher.messages')}}" {!! (Str::contains(url()->current(), 'messages') ? 'class=active-item' : '') !!}>
            <i class="bi bi-chat-dots-fill"></i>
            <span>{{ __('Messages')}}</span>
        </a>
    </li>
    <li>
        <a href="{{route('teacher.members')}}" {!! (Str::contains(url()->current(), 'members') ? 'class=active-item' : '') !!}>
            <i class="bi bi-person-lines-fill"></i>
            <span>{{ __('List of registered')}}</span>
        </a>
    </li>
    <li>
        <a href="{{route('teacher.gallery')}}" {!! (Str::contains(url()->current(), 'gallery') ? 'class=active-item' : '') !!}>
            <i class="bi bi-image-fill"></i>
            <span>{{ __('Gallery')}}</span>
        </a>
    </li>
    <li>
        <a href="{{route('teacher.password')}}">
            <i class="bi bi-lock-fill"></i>
            <span>{{__('Change password')}}</span>
        </a>
    </li>
    <li>
        <div>
            <a class="dropdown-item" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();">
                <i class="bi bi-door-open-fill"></i>{{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </li>
</ul>