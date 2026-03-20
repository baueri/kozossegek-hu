<select class="form-control" name="join_mode" data-allow-clear="1" data-placeholder="Nincs megadva">
    <option></option>
    @foreach($join_modes as $join_mode)
    <option value="{{ $join_mode->value() }}" @selected($selected_join_mode==$join_mode->value())>
        {{ $join_mode->translate() }}
    </option>
    @endforeach
</select>
<script>$(() => $("[name='join_mode']").select2())</script>
