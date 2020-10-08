<?php
use App\Enums\AgeGroupEnum;

builder('v_groups')->macro('whereGroupTag', function($builder, array $tags){

    $innerQuery = builder('group_tags')->distinct()->select('group_id')->whereIn('tag', $tags);

    $builder->whereRaw("id in ($innerQuery)", $tags);

})->macro('whereAgeGroup', function($builder, $ageGroup) {
    $builder->where('age_group', '<>', '');
    if ($ageGroup != AgeGroupEnum::KOROSZTALYTOL_FUGGETLEN) {
        $builder->whereRaw('(FIND_IN_SET(?, age_group) OR FIND_IN_SET(?, age_group))', [$ageGroup, AgeGroupEnum::KOROSZTALYTOL_FUGGETLEN]);
    }

});

builder('group_tags')->macro('whereGroupId', function($builder, $groupId) {
    $builder->where('group_id', $groupId);
});
