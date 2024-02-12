<a href="{{ $group['url'] }}" class="d-flex flex-row group-result py-2 px-1 text-left">
    <div style="width: 55px; height: 55px; overflow: hidden;" class="mr-1 flex-shrink-0">
        <img src="{{ $group['image_url'] }}" alt="{{ $group['name'] }}" style="width: 55px; height: 55px"/>
    </div>
    <div>
        {{ $group['name'] }}
        <span class="text-muted small">{{ $group['institute_name'] }} ({{ $group['city'] }})</span>
        <br/>
        <span class="text-muted small">{{ $tags }}</span>
    </div>
</a>