<div id="debugbar">
    <div id="debugbar-header">
        @foreach($headers as $id => $header)
            <label for="{{ $id }}">
                {{ $header }}
            </label>
        @endforeach
        <label class="float-right text-danger" for="close"><i class="fa fa-times"></i><input type="radio" name="debug-tab" id="close" style="display: none"></label>
    </div>
    <div id="debugbar-content">
        @foreach($tab_contents as $id => $tab_content)
            <input type="radio" id="{{ $id }}" value="1" style="display: none;" name="debug-tab">
            <div>{{ $tab_content }}</div>
        @endforeach
    </div>
</div>
<style>
    #debugbar {
        /*height: 39px;*/
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #fff;
    }
    #debugbar-header {
        border-top: 2px solid #333;
        border-bottom: 2px solid #333;
    }
    #debugbar-header label {
        margin: 0;
        padding: 5px 10px;
        color: var(--red);
    }

    #debugbar-header label.active {
        background: var(--blue);
        color: #fff;
    }

    #debugbar-content > div {
        display: none;
    }

    #debugbar-content input[type=radio]:checked ~ div {
        display: block;
    }
</style>