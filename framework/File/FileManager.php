<?php

namespace Framework\File;

use Exception;
use Framework\File\Enums\FileType;

class FileManager
{
    const TYPES_IMAGE = [
        'image/jpeg', 'image/gif', 'image/png'
    ];

    const TYPES_DOCUMENT = [
        'application/msword',
        'application/vnd.oasis.opendocument.text',
        'application/octet-stream',
        'application/wps-office.doc'
    ];

    const TYPES_EXCEL = [
        'application/vnd.ms-excel'
    ];

    const TYPES_PDF = [
        'application/pdf',
    ];

    const TYPES = [
        FileType::IMAGE => self::TYPES_IMAGE,
        FileType::DOCUMENT => self::TYPES_DOCUMENT,
        FileType::EXCEL => self::TYPES_EXCEL,
        FileType::PDF => self::TYPES_PDF
    ];

    /**
     * Root path of file manager
     *
     * @var string
     */
    protected $rootPath;

    /**
     * List of enabled Types
     *
     * @var array
     */
    protected $enabledTypes = [];

    /**
     * @var bool
     */
    protected $createFolderIfMissing = true;

    /**
     * FileManager constructor.
     * @param string $rootPath
     */
    public function __construct($rootPath = '', array $enabledTypes = ['*'])
    {
        $this->rootPath = static::addDirectorySeparator($rootPath);

        $this->setEnabledTypes($enabledTypes);
    }

    /**
     * Upload a file to the server
     *
     * @param array $fileData
     * @param string $fileName
     * @return File
     * @throws Exception
     */
    public function uploadFile(array $fileData, $fileName = null, $subDir = '')
    {
        $file = new File($fileData['tmp_name']);
        $file->setFileName($fileName ?: $fileData['name']);
        if ($this->createFolderIfMissing) {
            $this->createFolder($subDir);
        }

        if (!$this->fileTypeEnabled($fileData['type'])) {
            throw new Exception('File Type not allowed');
        }

        return $file->move($this->rootPath . $subDir);
    }

    /**
     * @param string $folderName
     * @throws Exception
     */
    public function createFolder($folderName = '', &$error = [])
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

    /**
     * @param array $enabledTypes
     */
    public function setEnabledTypes(array $enabledTypes)
    {
        $this->enabledTypes = $enabledTypes;
    }

    /**
     * @param $filePath
     * @return string
     */
    private static function addDirectorySeparator($filePath)
    {
        return rtrim($filePath, '/') . '/';
    }

    /**
     * @param $folderName
     * @return bool
     */
    public function folderExists($folderName)
    {
        return is_dir($this->rootPath . $folderName);
    }

    /**
     * @param $type
     * @return bool
     */
    public function fileTypeEnabled($type)
    {
        return in_array($type, $this->enabledTypes) || in_array('*', $this->enabledTypes);
    }

    /**
     * @return bool
     */
    private function rootFolderExists()
    {
        return is_dir($this->rootPath);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->rootPath;
    }


    public function getDirName()
    {
        return basename($this->rootPath);
    }

    public function createSymLink($link)
    {
        return symlink($this->rootPath, $link);
    }

    /**
     * @return File[]
     */
    public function getFiles($subDir = '')
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
