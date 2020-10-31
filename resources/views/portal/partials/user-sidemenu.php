<ul class="list-group">
    <li class="list-group-item"><a href="@route('portal.my_profile')">Profilom</a></li>
    <li class="list-group-item"><a href="@route('portal.my_group')">Közösségem</a></li>
    <?php if(App\Auth\Auth::user()->isAdmin()): ?>
        <li class="list-group-item"><a href="@route('admin')">Adminisztráció</a></li>
    <?php endif; ?>
    <li class="list-group-item"><a href="@route('logout')" class="text-danger">Kijelentkezés</a></li>
</ul>