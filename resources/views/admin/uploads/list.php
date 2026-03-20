@header()
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
@endheader
@footer()
<script>
    Dropzone.options.uploadFiles2 = {
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
        }
    }
</script>
@endfooter
@title('Feltöltések')
@extends('admin')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach($breadCrumbs as $i => $breadCrumb)
        <li class="breadcrumb-item">
            @if($i+1 < count($breadCrumbs))
            <a href="@route('admin.content.upload.list', ['dir' => $i > 0 ? $breadCrumb['path'] : null])">{{ $breadCrumb['name'] }}</a>
            @else
            {{ $breadCrumb['name'] }}
            @endif
        </li>
        @endforeach
    </ol>
</nav>
<form action="@route('admin.content.upload.upload_file', ['dir' => $dir])" id="upload-files2" class="dropzone">
    <h4 class="dz-message">Húzd ide a fájlt a feltöltéshez, vagy kattints ide.</h4>
</form>
@if($uploads)
<table class="table table-condensed table-striped">
    <thead>
        <tr>
            <th>Fájlnév</th>
            <th>Típus</th>
            <th>Méret</th>
            <th>Létrehozás dátuma</th>
            <th>Utolsó módosítás</th>
            <th>Teljes útvonal</th>
            <th class="text-center"><i class="fa fa-trash-alt text-danger"></i></th>
        </tr>
    </thead>
    @foreach($uploads as $upload)
    <tr>
        <td>
            <a href="{{ $upload['url'] }}" @if(!$upload['is_dir']) target="_blank" @endif><i class="{{ $upload['icon'] }}"></i> {{ $upload['name'] }}</a>
        </td>
        <td>{{ $upload['main_type'] }}</td>
        <td>
            {{ $upload['size'] }} MB
        </td>
        <td>{{ $upload['upload_date'] }}</td>
        <td>{{ $upload['mod_date'] }}</td>
        <td>{{ $upload['path'] }}</td>
        <td class="text-center">
            <a href="@route('admin.content.upload.delete_file', ['file' => $upload['path']])" class="text-danger" title="törlés"><i class="fa fa-trash-alt"></i></a>
        </td>
<!--            <div class="file-item">
                @if($upload['is_dir'])
                <a href="@route('admin.content.upload.list', ['dir' => $upload['name']])">
                    <div class="text-center"><img src="/images/folder.png" style="width: 100%; max-width: 200px;"></div>
                    <div class="text-center" title="{{ $upload['name'] }}" style="text-overflow: ellipsis; display:block; overflow: hidden;">{{ $upload['name'] }}</div>
                </a>
                @else
                <div style="width: 100%; max-width: 200px; height: 200px; background: url({{ $upload['path'] }}) no-repeat center; background-size: contain; margin: auto;"></div>
                <div title="{{ $upload['name'] }}" class="text-center" style="text-overflow: ellipsis; display:block; overflow: hidden;">{{ $upload['name'] }}</div>
                <a href="@route('admin.content.upload.delete_file', ['file' => $upload['path']])" class="text-danger float-right" title="törlés"><i class="fa fa-trash-alt"></i></a>
                <br/>
                <span><b>Fájl típusa:</b> {{ $upload['type'] }}</span><br/>
                <span><b>Fájlméret:</b> {{ $upload['size'] }} MB</span><br/>
                @endif
            </div>-->
    </tr>
    @endforeach
</table>
@endif
