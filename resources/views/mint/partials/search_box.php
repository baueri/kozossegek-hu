<?php
if (! isset($filter) || ! is_array($filter)) {
    $filter = [];
}
?>
<form method="get" action="@route('portal.groups')" class="position-relative search-form">
    <input type="text" class="form-control rounded-pill"
           placeholder="keresés" name="search" autocomplete="off"
           value="{{ $filter['search'] ?? '' }}" aria-label="Keresőszó" style="height: 30px; z-index: 1"/>
    <button type="submit" class="btn p-0" style="right: 10px; top: 2px; position:absolute; z-index: 2;" aria-label="Keresés"><mod-icon :name="{'search'}" :additional-class="{'p-1'}" /></button>
</form>
