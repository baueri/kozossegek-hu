<?php

namespace Framework\File;

use Framework\File\Enums\SizeUnit;
use InvalidArgumentException;
use RuntimeException;

class File
{
    protected ?string $fileName = null;

    protected ?string $filePath = null;

    protected $pathInfo;

    protected ?string $fileType;

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
    public function setFileName(string $fileName): self
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
     * @param string|null $newFilename
     * @param int|null $mode
     * @return static
     */
    public function move(string $newPath, string $newFilename = null, $mode = null): self
    {
        $newFilePath = $newPath . ($newFilename ?: $this->fileName);

        if (!is_dir($newFilePath)) {
            mkdir(dirname($newFilePath), 0777, true);
        }

        $ok = move_uploaded_file($this->filePath, $newFilePath);

        if (!$ok) {
            throw new RuntimeException("Error while moving file {$this->filePath} to $newFilePath");
        }

        if ($mode) {
            chmod($newFilePath, $mode);
        }

        $this->filePath = $newFilePath;

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
     * @param null $key
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

    public function getMainType(): string
    {
        foreach (FileManager::TYPES as $mainType => $types) {
            if (in_array($this->fileType, $types)) {
                return $mainType;
            }
        }

        return 'unknown';
    }

    public function is($fileType)
    {
        return in_array($this->fileType, (array) $fileType);
    }

    public function mainTypeIs($fileType): bool
    {
        return in_array($this->getMainType(), (array) $fileType);
    }

    public function touch(): bool
    {
        return touch($this->filePath);
    }

    public static function createFromFormData(?array $formData = null): ?File
    {
        if (!$formData) {
            return null;
        }

        return new static($formData['tmp_name']);
    }
}
