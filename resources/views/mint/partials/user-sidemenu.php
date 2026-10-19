<div class="account-sidebar-card">
    <p class="account-nav-heading">Fiók</p>
    <ul class="nav flex-column account-nav" id="user-menu">
        <li class="nav-item">
            <a href="@route('portal.my_profile')" class="nav-link@active_link_class('portal.my_profile')"><mod-icon :name="{'user'}" /> Profil</a>
        </li>
        <li class="nav-item">
            <a href="@route('portal.my_groups')" class="nav-link<?php echo route_is('portal.my_groups') || route_is('portal.edit_group') ? ' active' : ''; ?>"><mod-icon :name="{'comments'}" /> Közösségeim</a>
        </li>
        <li class="nav-item">
            <a href="@route('portal.my_events')" class="nav-link<?php echo route_is('portal.my_events') || route_is('portal.my_event.create') || route_is('portal.my_event.edit') ? ' active' : ''; ?>"><mod-icon :name="{'calendar-alt'}" /> @lang('menu.my_events')</a>
        </li>
        <li x:if="{$is_admin}" class="nav-item">
            <a href="@route('admin.dashboard')" class="nav-link"><mod-icon :name="{'cog'}" /> Adminisztráció</a>
        </li>
    </ul>
</div>
