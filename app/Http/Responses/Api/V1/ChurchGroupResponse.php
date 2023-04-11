<?php

declare(strict_types=1);

namespace App\Http\Responses\Api\V1;

use App\Models\ChurchGroupView;
use Framework\Http\JsonResponse;

class ChurchGroupResponse extends JsonResponse
{
    public function __construct(
        private readonly ChurchGroupView $groupView
    ) {
    }

    public function response(): array
    {
        return [
            'name' => $this->groupView->name,
            'description' => $this->groupView->description,
            'age_groups' => $this->groupView->allAgeGroupsAsString(),
            'group_leaders' => $this->groupView->group_leaders,
            'spiritual_movement' => $this->groupView->spiritual_movement,
            'city' => $this->groupView->city,
            'district' => $this->groupView->district,
            'institute_name' => $this->groupView->institute_name,
            'link' => $this->groupView->url(),
            'image' => get_site_url() . $this->groupView->getThumbnail(),
            'on_days' => $this->groupView->getDaysAsString(),
            'occasion_frequency' => $this->groupView->occasionFrequency()
        ];
    }
}