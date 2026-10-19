<?php

declare(strict_types=1);

namespace App\Portal\Services\Api;

use App\Models\ChurchGroup;
use App\Models\ChurchGroupView;
use Framework\Support\Collection;

class GroupApiTransformer
{
    public function transformView(ChurchGroupView $group): array
    {
        $tags = $group->tags
            ? $group->tags->map(fn ($tag) => $tag->tag)->values()->all()
            : [];

        return [
            'id' => (int) $group->getId(),
            'name' => $group->name,
            'description' => $group->description,
            'status' => $group->status,
            'pending' => $group->pending !== null ? (int) $group->pending : null,
            'institute_id' => $group->institute_id !== null ? (int) $group->institute_id : null,
            'institute_name' => $group->institute_name,
            'city' => $group->city,
            'district' => $group->district,
            'age_group' => array_values(array_filter(explode(',', (string) $group->age_group))),
            'on_days' => array_values(array_filter(explode(',', (string) $group->on_days))),
            'occasion_frequency' => $group->occasion_frequency,
            'join_mode' => $group->join_mode,
            'group_leaders' => $group->group_leaders,
            'spiritual_movement_id' => $group->spiritual_movement_id !== null ? (int) $group->spiritual_movement_id : null,
            'spiritual_movement' => $group->spiritual_movement,
            'tags' => $tags,
            'image_url' => $group->image_url,
            'url' => $group->url(),
            'user_id' => $group->user_id !== null ? (int) $group->user_id : null,
            'confirmed_at' => $group->confirmed_at,
            'notified_at' => $group->notified_at,
            'created_at' => $group->created_at,
            'updated_at' => $group->updated_at,
        ];
    }

    /**
     * @param Collection<int, ChurchGroupView>|iterable $groups
     */
    public function transformViews(iterable $groups): array
    {
        $data = [];
        foreach ($groups as $group) {
            $data[] = $this->transformView($group);
        }

        return $data;
    }

    public function transformEntity(ChurchGroup $group, ?ChurchGroupView $view = null): array
    {
        if ($view) {
            return $this->transformView($view);
        }

        return [
            'id' => (int) $group->getId(),
            'name' => $group->name,
            'status' => $group->status,
            'pending' => $group->pending !== null ? (int) $group->pending : null,
            'institute_id' => $group->institute_id !== null ? (int) $group->institute_id : null,
            'user_id' => $group->user_id !== null ? (int) $group->user_id : null,
        ];
    }
}
