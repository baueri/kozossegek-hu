@section('title')Új közösség létrehozása@endsection
@extends('admin')

<form>
    <div class="form-group">
        <label for="name">Közösség neve</label>
        <input type="text" id="name" name="name" class="form-control" autofocus>

    </div>
    <div class="form-group">
        <label for="city">Város / település</label>
            <select name="varos" style="width:200px" class="form-control">
                <option value="{{ $filter['varos'] }}">{{ $filter['varos'] ?: '-- város --' }}</option>
            </select>

    </div>
</form>

<script>
    $(()=>{
        $("[name=varos]").select2({
            placeholder: "város",
            allowClear: true,
            ajax: {
                url: '/api/v1/search-city',
                dataType: 'json',
                delay: 300
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
            }
        });
    });
</script>