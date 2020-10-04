@title('Címkék')
@extends('admin')
<table class="table table-sm" style="max-width: 400px;">
    <thead>
        <tr>
            <th>Név</th><th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($tags as $tag)
            <tr>
                <td>
                    <input type="text" class="form-control form-control-sm" data-id="{{ $tag['id'] }}" value="{{ $tag['tag'] }}">
                </td>
                <td>
                    <button type="button" class="btn btn-primary btn-sm">Mentés</button>
                    <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        @endforeach
    </tbod>
</table>
