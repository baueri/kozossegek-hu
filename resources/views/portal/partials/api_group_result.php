<a href="{{ $group['url'] }}" class="d-flex flex-row mb-1 group-result p-1">
    <div style="width: 55px; height: 55px; overflow: hidden;" class="mr-1">
        <img src="{{ $group['image_url'] }}" alt="{{ $group['name'] }}" style="width: 55px; height: 55px"/>
    </div>
    <div>
        {{ $group['name'] }}<br>
        <i class="text-muted small">{{ $group['institute_name'] }} ({{ $group['city'] }})</i>
    </div>
</a>