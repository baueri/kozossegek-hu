<input type="radio" name="debug-tab" id="debugbar-close" style="display: none">
<input type="radio" name="debug-tab" id="debugbar-open" style="display: none">
<div id="debugbar">
    <label class="mr-2 ml-3 text-danger" for="debugbar-close" style="cursor: pointer; position: absolute; right: 5px; top: 6px; z-index: 1000">@icon('times')</label>
    <label class="mr-2 ml-3 text-common" for="debugbar-open" title="debugbar">@icon('terminal')</label>
    <div id="debugbar-inner">
        <div id="debugbar-header" class="bg-light d-flex">
            <div class="btn-group btn-shadow mr-auto">
                @foreach($headers as $id => $header)
                    <label for="{{ $id }}" class="btn btn-default btn-sm mb-0" title="{{ strip_tags($header) }}">
                        {{ $header }}
                    </label>
                @endforeach
            </div>
            <div class="px-5 d-flex">
                <div title="memory usage: {{ strip_tags($memory_usage) }}" class="mx-2" style="line-height: 2"><i class="fa fa-cogs"></i><span class="d-none d-md-inline"> {{ $memory_usage }}</span></div> |
                <div title="query exec time: {{ strip_tags($query_time) }}" class="mx-2" style="line-height: 2"><i class="fa fa-database"></i><span class="d-none d-md-inline"> {{ $query_time }}</span></div> |
                <div title="total load time: {{ strip_tags($total_load_time) }}" class="mx-2" style="line-height: 2"><i class="fa fa-stopwatch"></i><span class="d-none d-md-inline"> {{ $total_load_time }}ms</span></div>
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
</div>
<style>
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

    #debugbar-content input[type=radio]:checked ~ div {
        display: block;
    }

    #debugbar-close:not(:checked) ~ #debugbar label[for=debugbar-open] {
        display:none;
    }

    #debugbar-close:checked ~ #debugbar label[for=debugbar-open] {
        right: 0 !important;
        width: 30px;
        height: 30px;
        margin: 0 !important;
        padding: 5px;

        cursor: pointer;
        position: absolute;
        top: 0;
        z-index: 1000;
        background: linear-gradient(to bottom, #ffffff 0%, #dee5f2 100%);
    }

    #debugbar-close:checked ~ #debugbar label[for=debugbar-close] {
        display:none !important;
    }

    #debugbar-close:checked ~ label[for=debugbar-content] {
        right: initial;
        left: 0;
    }

    #debugbar-close:checked ~ #debugbar #debugbar-content {
        display: none !important;
    }

    #debugbar-close:checked ~ #debugbar {
        width: 30px;
    }

    #debugbar-close:checked ~ #debugbar #debugbar-header {
        width: 30px;
        height: 30px;
        overflow: hidden;
    }
</style>
<script>
    (() => {
        let debugbar_open = window.localStorage.getItem("debugbar-open") || "1";
        let open_tab = window.localStorage.getItem("debugbar-tab");

        if (debugbar_open === "0") {
            $("#debugbar-close").attr("checked", "checked");
        }

        if (open_tab) {
            $(`label[for=${open_tab}]`).click();
        }

        $("#debugbar-close, #debugbar-open").on("change", function() {
            window.localStorage.setItem("debugbar-open", $("#debugbar-close").is(":checked") ? "0" : "1");
            if ($("#debugbar-close").is(":checked")) {
                window.localStorage.setItem("debugbar-tab", null);
            }
        });

        $("input[name=debug-tab]").on("change", function () {
            window.localStorage.setItem("debugbar-tab", $(this).attr("id"));
        })
    })()
</script>
