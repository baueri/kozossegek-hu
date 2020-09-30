<ul class="list-unstyled components mb-5">
    @foreach($admin_menu as $menu_item)
    <li {{ $menu_item['active'] ? 'class="active"' : '' }}>
        <a href="{{ $menu_item['uri'] }}" class="{{ isset($menu_item['link_class']) ? $menu_item['link_class'] : '' }}">
            <i class="fa fa-{{ $menu_item['icon'] }} mr-3"></i> {{ $menu_item['title'] }}
        </a>
<!--        @if($menu_item['active'] && $menu_item['submenu'])-->
<!--            <ul class="submenu list-unstyled">-->
<!--            @foreach($menu_item['submenu'] as $submenu_item)-->
<!--                <li {{ $submenu_item['active'] ? 'class="active"' : '' }}>-->
<!--                    <a href="{{ $submenu_item['uri'] }}" class="{{ isset($submenu_item['link_class']) ? $submenu_item['link_class'] : '' }}">-->
<!--                        <i class="fa fa-{{ $submenu_item['icon'] }} mr-3"></i> {{ $submenu_item['title'] }}-->
<!--                    </a>-->
<!--                </li>-->
<!--            @endforeach-->
<!--            </ul>-->
<!--        @endif-->
    </li>
    @endforeach
</ul>
