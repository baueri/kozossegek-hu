<?php

namespace App\Helpers;

use Framework\File\File;
use Framework\Support\Collection;

/**
 * Description of FileHelper
 *
 * @author ivan
 */
class FileHelper {
    
    /**
     * 
     * @param Collection|File[] $files
     */
    public static function parseFilesToArray(Collection $files): array
    {
        return $files->map(function (File $file) {
            return [
                'name' => $file->getFileName(),
                'type' => $file->getFileType(),
                'main_type' => $file->getFileType(),
                'size' => $file->getFileSize('MB', 2),
                'path' => static::getPublicPathFor($file),
                'is_dir' => $file->isDir(),
                'is_img' => $file->isImage()
            ];
        })->all();
    }
    
    /**
     * 
     * @param File $file
     * @return string
     */
    public static function getPublicPathFor(File $file): string
    {
        $path = $file->getFilePath();
        return str_replace(STORAGE_PATH . 'public', '/storage', $path);
    }
}
