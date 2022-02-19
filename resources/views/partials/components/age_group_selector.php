<select class="form-control" name="age_group[]" multiple="multiple" @if(isset($required) && $required) required @endif>
    @foreach($age_groups as $age_group)
        <option value="{{ $age_group->name }}" @selected(in_array($age_group->name, $age_group_array))>{{ $age_group->translate() }}</option>
    @endforeach
</select>
<script>$(() => $("[name='age_group[]']").select2())</script>
