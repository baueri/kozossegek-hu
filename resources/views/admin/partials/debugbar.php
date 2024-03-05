<div id="debugbar">
    <div id="debugbar-header" class="bg-light d-flex">
        <div class="btn-group btn-shadow mr-auto">
            @foreach($headers as $id => $header)
                <label for="{{ $id }}" class="btn btn-default btn-sm mb-0" title="{{ strip_tags($header) }}">
                    {{ $header }}
                </label>
            @endforeach
        </div>
        <div class="px-5">
            <span title="memory usage"><i class="fa fa-cogs"></i> {{ $memory_usage }}</span> |
            <span title="query exec time"><i class="fa fa-database"></i> {{ $query_time }}</span> |
            <span title="total load time"><i class="fa fa-stopwatch"></i> {{ $total_load_time }}ms</span>
        </div>
    </div>
    <div id="debugbar-content">
        @foreach($tab_contents as $id => $tab_content)
        <div>
            <input type="radio" id="{{ $id }}" value="1" style="display: none;" name="debug-tab">
            <div class="p-2">{{ $tab_content }}</div>
        </div>
        @endforeach
    </div>
</div>
<label class="mr-2 ml-3 text-danger" for="debugbar-close" style="cursor: pointer; position: fixed; right: 0; bottom: 0; z-index: 1000">
    <i class="fa fa-times"></i>
</label>
<input type="radio" name="debug-tab" id="debugbar-close" style="display: none">
<style>

    body{
        padding-bottom: 32px;
    }

    #debugbar {
        position: fixed;
        z-index: 999;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #fff;
    }
    #debugbar-header {
        border-top: 1px solid #ccc;
    }

    #debugbar-header .btn {
        border-radius: 0;
    }

    #debugbar-header .float-right > span {
        display: inline-block;
    }

    #debugbar-content > div > div {
        display: none;
        max-height: 600px;
        overflow-y: auto;
    }

    #debugbar-content input[type=radio]:checked ~ #debugbar-content div {
        display: block;
    }

    #debugbar-close:checked ~ label[for=debugbar-content] {
        right: initial;
        left: 0;
    }

    #debugbar-close:checked ~ #debugbar #debugbar-content {
        display: none !important;
    }
    #debugbar-close:checked ~ #debugbar #debugbar-header {
        width: 30px;
        height: 30px;
        overflow: hidden;
    }
</style>
