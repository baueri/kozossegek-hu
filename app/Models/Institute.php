<?php

namespace App\Models;

use App\Helpers\InstituteHelper;

/**
 * Description of Institute
 *
 * @author ivan
 */
class Institute extends \Framework\Model\Model
{
    use \Framework\Model\TimeStamps;

    public $id;

    public $name;

    public $name2;

    public $city;

    public $district;

    public $address;

    public $leader_name;

    public $user_id;

    public $user;

    public $approved;

    public $miserend_id;

    public $image_url;

    public $website;

    public function getImageRelPath()
    {
        if ($this->image_url) {
            return $this->image_url;
        }

        return InstituteHelper::getImageRelPath($this->id);
    }

    public function isFromMiserend()
    {
        return !is_null($this->miserend_id);
    }

    public function getImageStoragePath()
    {
        return InstituteHelper::getImageStoragePath($this->id);
    }

    public function hasImage()
    {
        return !is_null($this->image_url) || file_exists($this->getImageStoragePath());
    }
}
