<?php

namespace App\Models;

use App\Models\Traits\GroupTrait;
use App\Portal\BreadCrumb\BreadCrumbable;
use App\Services\SystemAdministration\SiteMap\ChangeFreq;
use Framework\Model\Entity;
use SebastianBergmann\CodeCoverage\BranchAndPathCoverageNotSupportedException;

/**
 * @property-read null|string $name
 * @property-read null|string $description
 * @property-read null|string $image_url
 * @property-read null|string $institute_image
 * @property-read null|string $institute_name
 * @property-read null|string $city
 * @property-read null|string $institute_id
 * @property-read null|string $on_days
 * @property-read null|string $group_leaders
 * @property-read null|string $user_id
 * @property-read null|string $age_group
 * @property-read null|string $leader_name
 * @property-read null|string $group_leader_email
 * @property-read null|string $district
 * @property-read null|string $institute_name2
 * @property-read null|string $spiritual_movement
 * @property-read null|string $pending
 * @property-read null|string $notified_at
 * @property-read null|string $confirmed_at
 */
class ChurchGroupView extends Entity implements BreadCrumbable
{
    use GroupTrait;

    public function getThumbnail(): string
    {
        if ($this->image_url) {
            return $this->image_url;
        }

        return $this->institute_image ?: '/images/default_thumbnail.jpg';
    }

    public function changeFreq(): ChangeFreq
    {
        return ChangeFreq::monthly;
    }

    public function priority(): ?string
    {
        return '0.7';
    }
}
