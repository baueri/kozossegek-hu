<?php

namespace App\Models;

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

    public $city;

    public $district;

    public $address;

    public $leader_name;

    public $user_id;

    public $user;

    public $image;

    public function getImageRelPath()
    {
        return $this->hasImage() ? \App\Helpers\InstituteHelper::getInstituteRelPath($this->id) : '/images/default_thumbnail.jpg';
    }

    public function getImageAbsPath()
    {
        return \App\Helpers\InstituteHelper::getInstituteAbsPath($this->id);
    }

    public function hasImage()
    {
        return file_exists($this->getImageAbsPath());
    }
}
