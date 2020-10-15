@title('Widgetek')
@extends('admin')
<div class="dropdown">
  <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="create-widget" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Új
    <i class="fa fa-caret-down"></i>
  </button>
  <div class="dropdown-menu" aria-labelledby="create-widget">
    <a class="dropdown-item" href="@route('admin.widget.create', ['type' => 'text'])">Szövegrész</a>
  </div>
</div>
