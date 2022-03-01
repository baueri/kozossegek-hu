<select id="spiritual_movement_id" name="spiritual_movement_id" class="form-control">
    <option></option>
    @foreach($spiritual_movements as $spiritual_movement)
    <option value="{{ $spiritual_movement['id'] }}" @if($selected_spiritual_movement == $spiritual_movement['id']) selected @endif>
        {{ $spiritual_movement['name'] }}
    </option>
    @endforeach
</select>
<script>
  $(()=> {
    $('[name=spiritual_movement_id]').select2({
      placeholder: 'lelkiségi mozgalom',
      allowClear: true,
    });
  });
</script>
