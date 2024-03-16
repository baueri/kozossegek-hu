<!--<nav aria-label="breadcrumb">-->
<!--    <ol class="breadcrumb">-->
<!--        @foreach($breadcrumbs as $i => $breadcrumb)-->
<!--        <li class="breadcrumb-item">-->
<!--            @if($i+1 < count($breadcrumbs))-->
<!--            <a href="#" onclick="selectImageFromMediaLibrary({-->
<!--            dir: '{{ $breadcrumb['path'] }}',-->
<!--            onSelect: (selected) => {-->
<!--                $('.selected-image').html('<img src=' + selected.src + '/>');-->
<!--                $('.image-url').val(selected.src);-->
<!--            }-->
<!--            }); return false;">-->
<!--                {{ $breadcrumb['name'] }}-->
<!--            </a>-->
<!--            @else-->
<!--            {{ $breadcrumb['name'] }}-->
<!--            @endif-->
<!--        </li>-->
<!--        @endforeach-->
<!--    </ol>-->
<!--</nav>-->
@if($uploads)
<div class="row" id="modal-uploads">
    @foreach($uploads as $upload)
    <div class="flex-grow-0 flex-shrink-0">
        <div class="p-3 pb-5" style="position: relative;">
            <div class="file-item {{ $upload['is_dir'] ? 'item-dir' : '' }}" data-src="{{ $upload['path'] }}" data-is_img="{{ $upload['is_img'] ? 1 : 0 }}" data-text="{{ $upload['name'] }}">
                @if($upload['is_dir'])
                    <div>
                        <div class="text-center"><img src="/images/folder.png" style="width: 150px; height: 150px"></div>
                        <div class="text-center" title="{{ $upload['name'] }}" style="text-overflow: ellipsis; display:block; overflow: hidden;">{{ $upload['name'] }}</div>
                    </div>
                @else
                    <div class="text-center">
                        <img src="{{ $upload['path'] }}" style="width: 150px; height: 150px; object-fit: contain;">
                    </div>
                    <div title="{{ $upload['name'] }}" class="text-center" style="text-overflow: ellipsis; display:block; overflow: hidden;">
                        {{ str_shorten($upload['name'], 20, '...') }}
                    </div>
                    <span><b>Fájl típusa:</b> {{ $upload['type'] }}</span><br/>
                    <span><b>Fájlméret:</b> {{ $upload['size'] }} MB</span><br/>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
    <h4>Nincsenek még feltöltött fájlok</h4>
@endif