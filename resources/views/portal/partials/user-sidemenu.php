<div class="mb-5">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="@route('portal.my_profile')" class="nav-link {{ route_is('portal.my_profile') ? 'active' : '' }}">@icon('user') Profil</a>
        </li>
        <li class="nav-item">
            <a href="@route('portal.my_groups')" class="nav-link {{ route_is('portal.my_groups') || route_is('portal.edit_group') ? 'active' : '' }}">@icon('comments') Közösségeim</a>
        </li>
        @admin()
            <li class="nav-item">
                <a href="@route('admin.dashboard')" class="nav-link">@icon('cog') Adminisztráció</a>
            </li>
        @endadmin
    </ul>
</div>