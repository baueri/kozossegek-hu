@title('Email sablonok')
@extends('admin')
<table class="table table-sm table-striped table-responsive">
    <tr><td><a href="@route('admin.email_template.registration')">Fiók regisztrációs sablon</a></td></tr>
    <tr><td><a href="@route('admin.email_template.register_by_group')">Fiók regisztrációs sablon (Közösség kapcsolattartó alapján)</a></td></tr>
    <tr><td><a href="@route('admin.email_template.reset_password')">Elfelejtett jelszó</a></td></tr>
    <tr><td><a href="@route('admin.email_template.group_contact')">Közösségvezetői kapcsolatfelvételi sablon</a></td></tr>
    <tr><td><a href="@route('admin.email_template.created_group')">Új közösség létrehozása (új fiókkal)</a></td></tr>
    <tr><td><a href="@route('admin.email_template.created_group_with_new_user')">Új közösség létrehozása (létező fiókkal)</a></td></tr>
</table>
