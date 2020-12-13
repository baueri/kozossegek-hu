<?php

namespace Framework\File;

use Framework\File\Enums\SizeUnit;
use InvalidArgumentException;
use RuntimeException;

class File
{
    protected $fileName;

    protected $filePath;

    protected $pathInfo;

    protected $fileType;

    public function __construct($filePath = '')
    {
        if ($filePath) {
            $this->setFileName($filePath);
            $this->setFilePath($filePath);
            $this->fileType = strtolower(mime_content_type($filePath));
        }
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return static
     */
    public function setFileName($fileName)
    {
        $this->fileName = basename($fileName);
        return $this;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    public function getFileSizeIntelligent()
    {
        $sizeMB = $this->getFileSize(SizeUnit::MB, 2);
        if ($sizeMB < 1) {
            return $this->getFileSize(SizeUnit::KB, 3) . ' KB';
        }
        return $sizeMB . ' MB';
    }

    /**
     * @param string $unit
     * @param int $precision
     * @return float|int
     */
    public function getFileSize($unit = 'B', $precision = 5)
    {
        $size = filesize($this->filePath);

        if ($unit !== SizeUnit::B) {
            
            if (!SizeUnit::values()->contains($unit)) {
                throw new InvalidArgumentException('Invalid size unit ' . $unit);
            }
            return round($size / pow(1024, SizeUnit::getSizeUnits()[$unit]), $precision);
        }
        return $size;
    }

    /**
     * @param string $newPath
     * @return static
     * @throws RuntimeException
     */
    public function move($newPath)
    {
        $ok = move_uploaded_file($this->filePath, $newPath . $this->fileName);
        
        if (!$ok) {
            throw new RuntimeException('Error while moving file: ' . $this->filePath . ' to ' . $newPath . $this->fileName);
        }
        $this->filePath = $newPath . $this->fileName;
        return $this;
    }

    /**
     * @return bool
     */
    public function delete()
    {
        if (!$this->isDir()) {
            return unlink($this->filePath);
        }
        return rmdir($this->filePath);
    }

    /**
     * @return bool
     */
    public function isDir()
    {
        return is_dir($this->filePath);
    }

    /**
     * @param string $mode
     * @return bool
     */
    public function setPermission($mode)
    {
        return chmod($this->filePath, $mode);
    }

    /**
     * @param string $user
     * @return bool]
     */
    public function setOwner($user)
    {
        return chown($this->filePath, $user);
    }

    /**
     * @return string
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        $typeClass = 'icon ' . ($this->isDir() ? 'folder' : 'file');
        $extensionClass = 'f-' . strtolower($this->getExtension());
        $classes = compact('typeClass', 'extensionClass');
        return '<span class="' . implode(' ', $classes) . '">' . strtolower($this->getExtension(true)) . '</span>';
    }

    /**
     * @param bool $withDot
     * @return string
     */
    public function getExtension($withDot = false)
    {
        $ext = $this->getPathinfo('extension');
        return ($ext && $withDot ? '.' : '') . $ext;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getPathInfo($key = null)
    {
        if (!$this->pathInfo) {
            $this->pathInfo = pathinfo($this->filePath);
        }
        if ($key) {
            return isset($this->pathInfo[$key]) ? $this->pathInfo[$key] : null;
        }
        return $this->pathInfo;
    }

    /**
     * @return bool
     */
    public function isImage()
    {
        return strpos($this->fileType, 'image/') === 0;
    }

    /**
     * @return false|string
     */
    public function getCreationDate()
    {
        return date('Y.m.d H:i:s', filectime($this->filePath));
    }

    /**
     * @return false|string
     */
    public function getModificationDate()
    {
        return date('Y.m.d H:i:s', filemtime($this->filePath));
    }

    public function getDirName()
    {
        return dirname($this->filePath);
    }
    
    /**
     * @param string $link
     * @return bool
     */
    public function createSymLink($link)
    {
        return symlink($this->filePath, $link);
    }
}
