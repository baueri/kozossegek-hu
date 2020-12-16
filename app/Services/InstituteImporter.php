<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Institutes;
use Framework\Support\Csv;

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
    private Institutes $institutes;

    public function __construct(Institutes $institutes)
    {
        $this->institutes = $institutes;
    }

    /**
     *
     * @param string $filePath
     * @param User $user
     * @return int[]
     */
    public function run(string $filePath, User $user)
    {
        $rows = Csv::parse($filePath, ';');

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
     * @param array $instituteData
     * @return bool
     */
    private function instituteExists(array $instituteData): bool
    {
        $query = builder('institutes')->where('name', $instituteData['name'])->where('city', $instituteData['city']);
        
        if (isset($instituteData['district']) && $instituteData['district']) {
            $query->where('district', $instituteData['district']);
        }
        
        return $query->exists();
    }
}
