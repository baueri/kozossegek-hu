<?php

namespace App\Repositories;

/**
 * Description of InstituteRepository
 *
 * @author ivan
 */
class InstituteRepository extends \Framework\Repository
{
    
    //put your code here
    public static function getModelClass(): string {
        return \App\Models\Institute::class;
    }

    public static function getTable(): string {
        return 'institutes';
    }

}
