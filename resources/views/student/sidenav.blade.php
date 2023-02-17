<ul>
    <li>
        <a href="{{route('student.home')}}"  class="{{Str::contains(url()->current(), 'home') || count(request()->segments()) === 1 ? 'active-item' : '' }}">
            <i class="bi bi-house-fill"></i>
            <span>{{__('Home')}}</span>
        </a>
    </li>
    <li>
        <a href="{{route('student.messages')}}" class="{{ Str::contains(url()->current(), 'messages') ? 'active-item' : '' }}">
            <i class="bi bi-envelope-fill"></i>
            <span>{{ __('Inbox')}}</span>
        </a>
    </li>
    <li>
        <a href="{{route('student.password')}}" class="{{Str::contains(url()->current(), 'reset_password')  ? 'active-item' : '' }}">
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
    <li class="user d-flex d-sm-none">
        <div>
            <div class="mx-3 d-flex justify-content-center">
                <div class="mx-2">
                    {{ Auth::user()->name }}
                </div>
                    <i class="bi bi-person-fill "></i>
            </div>
        </div>
    </li>
</ul>