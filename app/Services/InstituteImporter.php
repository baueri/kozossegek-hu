<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services;

use App\Models\User;
use App\Repositories\Institutes;

/**
 * Description of IntezmenyImporter
 *
 * @author ivan
 */
class InstituteImporter
{

    /**
     * @var Institutes
     */
    private $institutes;

    public function __construct(Institutes $institutes)
    {
        $this->institutes = $institutes;
    }
    
    /**
     *
     * @param string $filePath
     * @param User $user
     */
    public function run($filePath, User $user)
    {
        $rows = array_map('str_getcsv', file($filePath));

        unset($rows[0]);
        $skipped = $imported = 0;
        foreach ($rows as $row) {
            $instituteData = [
                    'name' => $row[0],
                    'city' => mb_ucfirst(mb_strtolower($row[1])),
                    'district' => mb_ucfirst(mb_strtolower($row[2])),
                    'address' => $row[3],
                    'leader_name' => $row[4],
                    'user_id' => $user->id
                ];
            if (!$this->instituteExists($instituteData)) {
                $imported++;
                $this->institutes->create($instituteData);
            } else {
                $skipped++;
            }
        }
        
        return [$imported, $skipped];
    }

    /**
     * @param string $instituteData
     * @return bool
     */
    private function instituteExists($instituteData)
    {
        $query = builder('institutes')->where('name', $instituteData['name'])->where('city', $instituteData['city']);
        
        if (isset($instituteData['district'])) {
            $query->where('district', $instituteData['district']);
        }
        
        return $query->exists();
    }
}
