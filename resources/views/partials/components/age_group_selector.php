<select class="form-control" name="age_group[]" multiple="multiple" @if($required) required @endif>
    @foreach($age_groups as $age_group)
        <option value="{{ $age_group->name }}" @if(in_array($age_group->name, $age_group_array)) selected @endif>{{ $age_group }}</option>
    @endforeach
</select>
<script>$(() => $("[name='age_group[]']").select2())</script>
