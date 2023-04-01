<ul class="list-unstyled components mb-0" id="admin_menu">
    @foreach($admin_menu as $menu_item)
    <li class="{{ $menu_item['class'] }}">
        <a href="{{ $menu_item['uri'] }}" class="{{ isset($menu_item['link_class']) ? $menu_item['link_class'] : '' }}">
            <i class="fa fa-{{ $menu_item['icon'] }} mr-3 align-middle text-center" style="width: 28px;"></i> {{ $menu_item['title'] }}
        </a>
    </li>
    @endforeach
</ul>
