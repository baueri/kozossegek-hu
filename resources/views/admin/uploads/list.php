@header('')
    <script src="/assets/dropzone/dropzone.min.js"></script>
    <link rel="stylesheet" href="/assets/dropzone/dropzone.min.css"/>
@endheader
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
<form action="@route('admin.content.upload.upload_file', ['dir' => $dir])" id="upload-files" class="dropzone">
    <div class="dz-message">Húzd ide a fájlt a feltöltéshez, vagy kattints ide.</div>
</form>
@if($uploads)
<div class="row">
    @foreach($uploads as $upload)
        <div class="col-md-3 col-sm-3">
            <div class="p-3 pb-5" style="position: relative;">
                <div style="border: 1px solid #dadada; padding: 10px; background: #fafafa;">
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
                </div>
            </div>
        </div>
    @endforeach
</div>
@else
    <h4 class='text-center'>Üres mappa</h4>
@endif
