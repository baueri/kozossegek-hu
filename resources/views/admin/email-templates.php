@title('Email sablonok')
@extends('admin')
<table class="table table-sm table-striped table-responsive">
    <tr><td><a href="@route('admin.email_template.registration')" title="megtekintés"><i class="fa fa-eye"></i> Fiók regisztrációs sablon</a></td></tr>
    <tr><td><a href="@route('admin.email_template.reset_password')" title="megtekintés"><i class="fa fa-eye"></i> Elfelejtett jelszó</a></td></tr>
    <tr><td><a href="@route('admin.email_template.group_contact')" title="megtekintés"><i class="fa fa-eye"></i> Közösségvezetői kapcsolatfelvételi sablon</a></td></tr>
</table>