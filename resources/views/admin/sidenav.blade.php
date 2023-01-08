<ul>
    <li>
        <a href="{{route('admin.home')}}" class="{{Str::contains(url()->current(), 'home')  ? 'active-item' : '' }}" >
            <i class="bi bi-house-fill"></i>
            <span>{{__('Home')}}</span>
        </a>
    </li>
    <li>
        <a href="{{route('admin.courses')}}" class="{{Str::contains(url()->current(), 'courses')  ? 'active-item' : '' }}">
            <i class="bi bi-wrench"></i>
            <span>{{__('Course management')}}</span>
        </a>
    </li>
    <li>
        <a href="{{route('admin.members')}}" class="{{Str::contains(url()->current(), 'members')  ? 'active-item' : '' }}">
            <i class="bi bi-person-lines-fill"></i>
            <span>{{ __('List of registered')}}</span>
        </a>
    </li>
    <li>
        <a href="" class="{{Str::contains(url()->current(), '#')  ? 'active-item' : '' }}">
            <i class="bi bi-chat-square-text-fill"></i>
            <span> {{__('Front page text')}}</span>
        </a>
    </li>
    <li>
        <a href="{{route('admin.add_user')}}" class="{{Str::contains(url()->current(), 'add_user_form')  ? 'active-item' : '' }}">
            <i class="bi bi-person-plus-fill"></i>
            <span>{{__('Add user')}}</span>
        </a>
    </li>
    <li>
        <a href="{{route('admin.password')}}" class="{{Str::contains(url()->current(), 'reset_password')  ? 'active-item' : '' }}">
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