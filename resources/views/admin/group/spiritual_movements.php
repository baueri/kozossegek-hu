@title('Lelkiségi mozgalmak')
@extends('admin')
<table class="table table-sm" style="max-width: 640px;">
    <thead>
        <tr>
            <th>Név</th><th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($movements as $movement)
            <tr>
                <td>
                    <input type="text" class="form-control form-control-sm" data-id="{{ $movement['id'] }}" value="{{ $movement['name'] }}">
                </td>
                <td>
                    <button type="button" class="btn btn-primary btn-sm">Mentés</button>
                    <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        @endforeach
    </tbod>
</table>
