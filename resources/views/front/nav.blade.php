<header class="header-area">
    <div class="header-container">
        <div class="site-logo">
            <a href="/"><img src="/images/junior_academy_logo.png" alt=""></a>
        </div>
        <div class="mobile-nav">
            <i class="bi bi-list"></i>
        </div>
        <div class="site-nav-menu">
            <ul class="primary-menu">
                <li><a href="{{route('front-home')}}" class="{{Str::contains(url()->current(), 'home') || count(request()->segments()) === 0 ? 'active' : '' }}"> {{__('Home')}}</a></li>
                <li><a href="{{route('gallery')}}" class="{{ Str::contains(url()->current(), 'gallery') ? 'active' : '' }}">{{__('Gallery')}}</a></li>
                <li><a href="{{route('contact')}}" class="{{ Str::contains(url()->current(), 'contact') ? 'active' : '' }}">{{__('Contact')}}</a></li>
                @if(Auth::check())
                    <li class="login-item">
                        <a href="{{route('login')}}" class="px-2 {{ Str::contains(url()->current(), 'login') ? 'active' : '' }}">
                            {{ Auth::user()->name }}
                            <i class="bi  {{Auth::user()->role == 0 ? 'bi-person-fill' : ( Auth::user()->role == 1 ? 'bi-book-half' : 'bi-shield-shaded') }}"></i>
                        </a>
                    </li>
                @else
                    <li class="login-item"><a href="{{route('register')}}" class="{{ Str::contains(url()->current(), 'register') ? 'active' : '' }}">{{__('Register')}}</a></li>
                    <li><a href="{{route('login')}}" class="{{ Str::contains(url()->current(), 'login') ? 'active' : '' }}">{{__('Login')}}</a></li>
                @endif
            </ul>
        </div>
    </div>
</header>

