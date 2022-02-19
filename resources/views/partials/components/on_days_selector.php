<select class="form-control day-selector" name="on_days[]" multiple="multiple" id="on_days">
    @foreach($days as $day)
        <option value="{{ $day->name }}" @selected(in_array($day->name, $group_days))>
            {{ $day->translate() }}
        </option>
    @endforeach
</select>
<script> 
    $(() => {
        $(".day-selector").select2({
            allowClear: true
        });
    });
</script>