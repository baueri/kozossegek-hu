<?php

builder()->macro('deleted', function ($builder) {
    $builder->whereNotNull('deleted_at');
})->macro('notDeleted', function ($builder) {
    $builder->whereNull('deleted_at');
})->macro('deletedEarlierThan', function ($builder, string $date) {
    $builder->whereRaw("DATE(deleted_at) < DATE('{$date}')");
});

builder('v_groups')->macro('whereGroupTag', function ($builder, array $tags) {
    $innerQuery = builder('group_tags')->distinct()->select('group_id')->whereIn('tag', $tags);
    $builder->whereRaw("id in ($innerQuery)", $tags);
})->macro('whereAgeGroup', function ($builder, $ageGroup) {
    $builder->where('age_group', '<>', '');
    $builder->whereRaw('FIND_IN_SET(?, age_group)', [$ageGroup]);
})->macro('active', function ($builder) {
    $builder->where('status', 'active')
        ->where('pending', 0)
        ->apply('notDeleted');
});

builder('group_tags')->macro('whereGroupId', 'whereGroupId');
builder('v_group_tags')->macro('whereGroupId', 'whereGroupId');

builder('institutes')->macro('approved', fn ($builder) => $builder->where('approved', 1));

function whereGroupId($builder, $groupId)
{
    $builder->where('group_id', $groupId);
}
