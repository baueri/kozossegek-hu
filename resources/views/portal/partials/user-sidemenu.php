<ul class="list-group">
    <li class="list-group-item"><a href="@route('portal.my_profile')">Fiókom</a></li>
    <li class="list-group-item"><a href="@route('portal.my_groups')">Közösségeim</a></li>
    @admin()
        <li class="list-group-item"><a href="@route('admin.dashboard')">Adminisztráció</a></li>
    @endadmin
    <li class="list-group-item"><a href="@route('logout')" class="text-danger">Kijelentkezés</a></li>
</ul>
