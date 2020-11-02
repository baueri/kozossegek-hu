<?php
use App\Enums\AgeGroupEnum;

builder()->macro('deleted', function($builder){
    $builder->whereNotNull('deleted_at');
})->macro('notDeleted', function($builder){
    $builder->whereNull('deleted_at');
});

builder('v_groups')->macro('whereGroupTag', function($builder, array $tags){

    $innerQuery = builder('group_tags')->distinct()->select('group_id')->whereIn('tag', $tags);

    $builder->whereRaw("id in ($innerQuery)", $tags);

})->macro('whereAgeGroup', function($builder, $ageGroup) {

    $builder->where('age_group', '<>', '');
    $builder->whereRaw('FIND_IN_SET(?, age_group)', [$ageGroup]);

});

builder('group_tags')->macro('whereGroupId', function($builder, $groupId) {
    $builder->where('group_id', $groupId);
});
