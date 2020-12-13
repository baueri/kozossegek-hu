<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Console\Commands;

/**
 * Description of PublishApp
 *
 * @author ivan
 */
class PublishApp implements \Framework\Console\Command
{
    
    public function handle()
    {
        $fileManager = new \Framework\File\FileManager(STORAGE_PATH . 'public');
        
        if (!file_exists(ROOT . 'public/uploads')) {
            $fileManager->createFolder('uploads');
            $ok = $fileManager->createSymLink(ROOT . 'public/storage');
            if (!$ok) {
                \Framework\Console\Out::error('Nem sikerült a symlink generálás');
            }
        }
    }

    public static function signature()
    {
        return 'app:publish';
    }
}
