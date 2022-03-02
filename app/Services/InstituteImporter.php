<?php

namespace App\Services;

use App\Models\UserLegacy;
use Framework\Support\Csv;
use Legacy\Institutes;

class InstituteImporter
{

    public function __construct(private Institutes $institutes)
    {
    }

    /**
     * @return int[]
     */
    public function run(string $filePath, UserLegacy $user): array
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

    private function instituteExists(array $instituteData): bool
    {
        $query = builder('institutes')->where('name', $instituteData['name'])->where('city', $instituteData['city']);
        
        if (isset($instituteData['district']) && $instituteData['district']) {
            $query->where('district', $instituteData['district']);
        }
        
        return $query->exists();
    }
}
