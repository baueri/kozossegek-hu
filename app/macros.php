<?php

builder('v_groups')->macro('whereGroupTag', function($builder, array $tags){

    $innerQuery = builder('group_tags')->distinct()->select('group_id')->whereIn('tag', $tags)->toSql();

    $builder->whereRaw("id in ($innerQuery)", $tags);

});

builder('group_tags')->macro('whereGroupId', function($builder, $groupId) {
    $builder->where('group_id', $groupId);
});
