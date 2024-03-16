<?php

declare(strict_types=1);

namespace App\Helpers;

use Framework\File\Enums\FileType;
use Framework\File\File;
use Framework\File\Path;
use Framework\Support\Collection;

class FileHelper
{
    /**
     * @param Collection<File> $files
     */
    public static function parseFilesToArray(Collection $files): array
    {
        return $files->map(fn (File $file) => [
            'name' => $file->getFileName(),
            'type' => $file->getFileType(),
            'main_type' => $file->getMainType(),
            'size' => $file->getFileSize('MB', 2),
            'path' => static::getPublicPathFor($file),
            'is_dir' => $file->isDir(),
            'url' => $file->isDir() ?
                route('admin.content.upload.list', ['dir' => $file->getFileName()]) : static::getPublicPathFor($file),
            'is_img' => $file->isImage(),
            'icon' => static::getIcon($file),
            'upload_date' => $file->getCreationDate(),
            'mod_date' => $file->getModificationDate()
        ])->all();
    }

    public static function getIcon(File $file): string
    {
        $type = $file->getMainType();

        if ($type == FileType::IMAGE) {
            return 'fa fa-image';
        } elseif ($type == FileType::DOCUMENT) {
            return 'fa fa-file-word';
        } elseif ($type == FileType::PDF) {
            return 'fa fa-file-pdf';
        } elseif ($type === 'folder') {
            return 'fa fa-folder-open';
        }

        return '';
    }

    public static function getPublicPathFor(File $file): string
    {
        $path = $file->getFilePath();
        return str_replace(env('STORAGE_PATH') . 'public', '/storage', $path);
    }

    public static function getExtension(string $fileName): string
    {
        return pathinfo($fileName, PATHINFO_EXTENSION);
    }

    public static function getBreadCrumb(Path $storage, string|null $dir): Collection
    {
        return collect([
            ['name' => 'Feltöltések', 'path' => '']
        ])->merge(Collection::fromList($dir, '/')->reverse()->map(function ($dir) {
            return ['name' => basename($dir), 'path' => $dir];
        }));
    }

    public static function sortFiles(array &$files): void
    {
        usort($files, function ($a, $b) {
            if ($a['is_dir'] && !$b['is_dir']) {
                return -1;
            } elseif (!$a['is_dir'] && $b['is_dir']) {
                return 1;
            }
            return strcasecmp($a['name'], $b['name']);
        });
    }
}
