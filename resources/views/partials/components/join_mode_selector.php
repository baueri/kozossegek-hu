<select class="form-control" name="join_mode" data-allow-clear="1" data-placeholder="Nincs megadva">
    <option></option>
    @foreach($join_modes as $join_mode => $join_mode_name)
    <option value="{{ $join_mode }}" @if($selected_join_mode==$join_mode) selected @endif>
        {{ $join_mode_name }}
    </option>
    @endforeach
</select>
<script>$(() => $("[name='join_mode']").select2())</script>
