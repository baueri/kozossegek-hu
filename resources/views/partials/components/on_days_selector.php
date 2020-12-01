<select class="form-control" name="on_days[]" multiple="multiple" id="on_days">
    @foreach($days as $day)
        <option value="{{ $day }}" @if(in_array($day, $group_days)) selected @endif>
            @lang("day.$day")
        </option>
    @endforeach
</select>
<script> 
    $(() => {
        $("#on_days").select2({
            placeholder: "napok",
            allowClear: true
        });
    });
</script>