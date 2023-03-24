<?php

namespace App\Console\Commands;

use Framework\Console\Command;
use Framework\Console\Out;
use Framework\File\FileManager;

class PublishApp extends Command
{
    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        $fileManager = new FileManager(_env('STORAGE_PATH') . 'public');

        if (!file_exists(ROOT . 'public/uploads')) {
            $fileManager->createFolder('uploads');
            $ok = $fileManager->createSymLink(ROOT . 'public/storage');
            if (!$ok) {
                Out::error('Nem sikerült a symlink generálás');
            }
        }
    }

    public static function signature(): string
    {
        return 'app:publish';
    }
}
