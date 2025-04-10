<?php

declare(strict_types=1);

namespace Framework\File;

use Framework\File\Enums\SizeUnit;
use InvalidArgumentException;
use RuntimeException;

class File
{
    protected ?string $fileName = null;

    protected string|array $pathInfo;

    protected ?string $fileType = null;

    /**
     * @var resource|null $resource
     */
    protected $resource = null;

    public function __construct(protected ?string $filePath = '')
    {
        if ($filePath) {
            $this->setFileName($filePath);
            $this->fileType = $this->exists() ? strtolower(mime_content_type($filePath)) : null;
        }
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = basename($fileName);
        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function getFileSize(string $unit = 'B', int $precision = 5): float
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
    public function move(string $newPath, string $newFilename = null, int $mode = null): self
    {
        $newFilePath = rtrim($newPath, '/') . '/' . ($newFilename ?: $this->fileName);

        if (!is_dir(dirname($newFilePath))) {
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

    public function delete(): bool
    {
        if (!$this->isDir()) {
            return unlink($this->filePath);
        }
        return rmdir($this->filePath);
    }

    public function isDir(): bool
    {
        return is_dir($this->filePath);
    }

    public function setPermission(int $mode): bool
    {
        return chmod($this->filePath, $mode);
    }

    public function setOwner(string $user): bool
    {
        return chown($this->filePath, $user);
    }

    public function getFileType(): ?string
    {
        return $this->fileType;
    }

    public function getIcon(): string
    {
        $typeClass = 'icon ' . ($this->isDir() ? 'folder' : 'file');
        $extensionClass = 'f-' . strtolower($this->getExtension());
        $classes = compact('typeClass', 'extensionClass');
        return '<span class="' . implode(' ', $classes) . '">' . strtolower($this->getExtension(true)) . '</span>';
    }

    public function getExtension(bool $withDot = false): string
    {
        $ext = $this->getPathinfo()['extension'] ?? null;
        return ($ext && $withDot ? '.' : '') . $ext;
    }

    public function getPathInfo(): array|string
    {
        return $this->pathInfo ??= pathinfo($this->filePath);
    }

    public function isImage(): bool
    {
        return strpos($this->fileType, 'image/') === 0;
    }

    public function getCreationDate(): ?string
    {
        return date('Y.m.d H:i:s', filectime($this->filePath)) ?: null;
    }

    public function getModificationDate(): ?string
    {
        return date('Y.m.d H:i:s', filemtime($this->filePath)) ?: null;
    }

    public function getDirName(): string
    {
        return dirname($this->filePath);
    }

    public function createSymLink(string $link): bool
    {
        return symlink($this->filePath, $link);
    }

    public function getMainType(): string
    {
        if (is_dir($this->filePath)) {
            return 'folder';
        }

        foreach (FileManager::TYPES as $mainType => $types) {
            if (in_array($this->fileType, $types)) {
                return $mainType;
            }
        }

        return 'unknown';
    }

    public function is($fileType): bool
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

    public function exists(): bool
    {
        return file_exists($this->filePath);
    }

    public static function createFromFormData(?array $formData = null): ?File
    {
        if (!$formData) {
            return null;
        }

        return new static($formData['tmp_name']);
    }

    public function open(string $mode = 'r'): static
    {
        $this->resource = fopen($this->getFilePath(), $mode);

        return $this;
    }

    public function close(): static
    {
        fclose($this->resource);

        $this->resource = null;

        return $this;
    }

    public function write($data): static
    {
        fwrite($this->resource, $data);

        return $this;
    }

    public function __toString(): string
    {
        return $this->filePath;
    }
}
