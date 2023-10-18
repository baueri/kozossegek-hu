<nav id="top_menu" class="navbar navbar-expand navbar-light bg-white fixed-top">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item" id="mobile_menu_toggle">
            <a class="nav-link"><i class="fa fa-bars"></i></a>
        </li>
        @if(isset($current_menu_item['submenu']))
        @foreach($current_menu_item['submenu'] as $submenuItem)
        <li class="nav-item  {{ $submenuItem['active'] ? 'active' : '' }}">
            <a class="nav-link" href="{{ $submenuItem['uri'] }}"><i class="fa fa-{{ $submenuItem['icon'] }} {{ $submenuItem['active'] ? 'text-primary' : '' }}"></i>
                <span>{{ $submenuItem['title'] }}</a></span>
        </li>
        <li class="nav-item divider"></li>
        @endforeach
        @else
        <li class="nav-item  {{ $current_menu_item['active'] ? 'active' : '' }}">
            <a class="nav-link" href="{{ $current_menu_item['uri'] }}">
                <i class="fa fa-{{ $current_menu_item['icon'] }}"></i>
                <span>{{ $current_menu_item['title'] }}</span></a>
        </li>
        @endif
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <span>Hello <a href="@route('admin.user.profile')">@icon('user') {{ App\Auth\Auth::user()->keresztnev() }}</a></span>
        </li>
        <li class="divider nav-item"></li>
        <li class="nav-item"><a href="@route('home')" title="ugrás az oldalra" target="_blank" class= nav-link"><i class="fa fa-eye"></i></a></li>
        <li class="nav-item"><a href="@route('logout')" title="kilépés" class="text-danger nav-link"><i class="fa fa-sign-out-alt"></i></a></li>
    </ul>
</nav>