<select class="form-control" name="{{ $name }}" @if(isset($required) && $required) required @endif>
    @if(isset($placeholder))
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach($values as $value => $text)
        <option value="{{ $value }}" @if($selected_value == $value) selected @endif>{{ $text }}</option>
    @endforeach
</select>