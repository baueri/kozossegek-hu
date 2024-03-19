<?php

namespace Framework\File;

use Exception;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\File\Enums\FileType;

class FileManager
{
    public const TYPES_IMAGE = [
        'image/jpeg', 'image/gif', 'image/png'
    ];

    public const TYPES_DOCUMENT = [
        'application/msword',
        'application/vnd.oasis.opendocument.text',
        'application/octet-stream',
        'application/wps-office.doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.template'
    ];

    public const TYPES_EXCEL = [
        'application/vnd.ms-excel'
    ];

    public const TYPES_PDF = [
        'application/pdf',
    ];

    public const TYPES = [
        FileType::IMAGE => self::TYPES_IMAGE,
        FileType::DOCUMENT => self::TYPES_DOCUMENT,
        FileType::EXCEL => self::TYPES_EXCEL,
        FileType::PDF => self::TYPES_PDF
    ];

    /**
     * Root path of file manager
     */
    public readonly ?string $rootPath;

    /**
     * List of enabled Types
     */
    protected array $enabledTypes = [];

    protected bool $createFolderIfMissing = true;

    public function __construct(string $rootPath = '', array $enabledTypes = ['*'])
    {
        $this->rootPath = static::addDirectorySeparator($rootPath);

        $this->setEnabledTypes($enabledTypes);
    }

    /**
     * @throws FileTypeNotAllowedException
     */
    public function uploadFileByFileData(array $fileData, ?string $fileName = null, string $subDir = ''): File
    {
        $file = new File($fileData['tmp_name']);

        return $this->uploadFile($file, $fileName ?: $fileData['name'], $subDir);
    }

    public function uploadFile(File $file, ?string $fileName = '', string $subDir = ''): File
    {
        if ($this->createFolderIfMissing) {
            $this->createFolder($subDir);
        }

        if ($fileName) {
            $file->setFileName($fileName);
        }

        if (!$this->fileTypeEnabled($file->getFileType())) {
            throw new FileTypeNotAllowedException();
        }

        return $file->move($this->rootPath . $subDir);
    }

    /**
     * @throws Exception
     */
    public function createFolder(string $folderName = '', &$error = []): bool
    {
        if ($this->folderExists($folderName)) {
            return true;
        }

        $ok = mkdir(rtrim($this->rootPath . $folderName, '/'), 0777, true);

        if (!$ok) {
            $error = error_get_last();
        }

        return $ok;
    }

    public function setEnabledTypes(array $enabledTypes): void
    {
        $this->enabledTypes = $enabledTypes;
    }

    private static function addDirectorySeparator(string $filePath): string
    {
        return rtrim($filePath, '/') . '/';
    }

    public function folderExists(string $folderName): bool
    {
        return is_dir($this->rootPath . $folderName);
    }

    public function fileTypeEnabled(string $type): bool
    {
        return in_array($type, $this->enabledTypes) || in_array('*', $this->enabledTypes);
    }

    private function rootFolderExists(): bool
    {
        return is_dir($this->rootPath);
    }

    public function getDirName(): string
    {
        return basename($this->rootPath);
    }

    public function createSymLink($link): bool
    {
        return symlink($this->rootPath, $link);
    }

    /**
     * @return File[]
     */
    public function getFiles($subDir = ''): array
    {
        $files = glob(rtrim($this->rootPath . $subDir, '/') . DS . '*');
        usort($files, function ($a, $b) {
            $aIsDir = is_dir($a);
            $bIsDir = is_dir($b);
            if ($aIsDir === $bIsDir) {
                return strnatcasecmp($a, $b);
            } // both are dirs or files
            elseif ($aIsDir && !$bIsDir) {
                return -1;
            } // if $a is dir - it should be before $b
            elseif (!$aIsDir && $bIsDir) {
                return 1;
            } // $b is dir, should be before $a
        });
        return array_filter(array_map(function ($file) {
            return new File($file);
        }, $files), function (File $file) {
            return $this->fileTypeEnabled($file->getFileType()) || $file->isDir();
        });
    }
}
