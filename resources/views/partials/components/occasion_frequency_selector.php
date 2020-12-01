<select class="form-control" name="occasion_frequency" required>
    @foreach($occasion_frequencies as $occasion_frequency)
    <option value="{{ $occasion_frequency->name }}" @if($selected_occasion_frequency == $occasion_frequency->name) selected @endif>{{ $occasion_frequency }}</option>
    @endforeach
</select>
<script>$(() => $("[name='occasion_frequency']").select2())</script>