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

    public $approved;

    public function getImageRelPath()
    {
        return \App\Helpers\InstituteHelper::getImageRelPath($this->id);
    }

    public function getImageStoragePath()
    {
        return \App\Helpers\InstituteHelper::getImageStoragePath($this->id);
    }

    public function hasImage()
    {
        return file_exists($this->getImageStoragePath());
    }
}
