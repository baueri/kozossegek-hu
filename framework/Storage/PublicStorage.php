<?php

namespace Framework\Storage;

use App\Helpers\FileHelper;
use Framework\File\File;
use Framework\File\FileManager;

class PublicStorage extends FileManager
{
    public function __construct($storageDir = '', $enabledTypes = ['*'])
    {
        parent::__construct(env('STORAGE_PATH') . 'public' . DS . $storageDir, $enabledTypes);

        if (!file_exists(ROOT . 'public/storage')) {
            if (!$this->createFolder('', $error)) {
                throw new \Exception($error['message'], $error['type']);
            }

            if (!$this->createSymLink(ROOT . 'public/storage')) {
                throw new \Exception('Nem sikerült a szimbolikus link létrehozása');
            }
        }
    }

    public function getPublicPathFor(File $file): string
    {
        return FileHelper::getPublicPathFor($file);
    }

    /**
     * @param $publicPath
     * @return string
     */
    public function getStoragePath($publicPath)
    {
        return env('STORAGE_PATH') . preg_replace('/^\/storage/', 'public', $publicPath);
    }

    /**
     * @param string $publicPath
     * @return File
     */
    public function getFileByPubPath($publicPath)
    {
        return new File($this->getStoragePath($publicPath));
    }
}
