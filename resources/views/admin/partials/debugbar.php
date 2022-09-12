<div id="debugbar">
    <div id="debugbar-header" class="bg-light">
        <div class="btn-group btn-shadow">
            @foreach($headers as $id => $header)
                <label for="{{ $id }}" class="btn btn-default btn-sm mb-0">
                    {{ $header }}
                </label>
            @endforeach
        </div>
        <label class="float-right mr-4 text-danger" for="close"><i class="fa fa-times"></i><input type="radio" name="debug-tab" id="close" style="display: none"></label>
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

    #debugbar-content > div > div {
        display: none;
        max-height: 600px;
        overflow-y: auto;
    }

    #debugbar-content input[type=radio]:checked ~ div {
        display: block;
    }

</style>