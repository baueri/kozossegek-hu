@if($uploads)
<div class="row" id="modal-uploads">
    @foreach($uploads as $upload)
    <div class="col-md-3 col-sm-3">
        <div class="p-3 pb-5" style="position: relative;">
            <div class="file-item {{ $upload['is_dir'] ? 'item-dir' : '' }}" data-src="{{ $upload['path'] }}" data-is_img="{{ $upload['is_img'] ? 1 : 0 }}" data-text="{{ $upload['name'] }}">
                @if($upload['is_dir'])
                    <div>
                        <div class="text-center"><img src="/images/folder.png" style="width: 100%; max-width: 200px;"></div>
                        <div class="text-center" title="{{ $upload['name'] }}" style="text-overflow: ellipsis; display:block; overflow: hidden;">{{ $upload['name'] }}</div>
                    </div>
                @else
                    <div style="width: 100%; max-width: 200px; height: 200px; background: url({{ $upload['path'] }}) no-repeat center; background-size: contain; margin: auto;"></div>
                    <div title="{{ $upload['name'] }}" class="text-center" style="text-overflow: ellipsis; display:block; overflow: hidden;">{{ $upload['name'] }}</div>
                    <br/>
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