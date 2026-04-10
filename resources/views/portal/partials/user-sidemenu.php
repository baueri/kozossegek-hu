<div class="account-sidebar-card">
    <p class="account-nav-heading">Fiók</p>
    <ul class="nav flex-column account-nav" id="user-menu">
        <li class="nav-item">
            <a href="@route('portal.my_profile')" class="nav-link {{ route_is('portal.my_profile') ? 'active' : '' }}">@icon('user') Profil</a>
        </li>
        <li class="nav-item">
            <a href="@route('portal.my_groups')" class="nav-link {{ route_is('portal.my_groups') || route_is('portal.edit_group') ? 'active' : '' }}">@icon('comments') Közösségeim</a>
        </li>
        <li class="nav-item">
            <a href="@route('portal.my_events')" class="nav-link {{ route_is('portal.my_events') || route_is('portal.my_event.create') || route_is('portal.my_event.edit') ? 'active' : '' }}">@icon('calendar-alt') @lang('menu.my_events')</a>
        </li>
        @admin()
            <li class="nav-item">
                <a href="@route('admin.dashboard')" class="nav-link">@icon('cog') Adminisztráció</a>
            </li>
        @endadmin
    </ul>
</div>
