<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\PathHelper;
use App\Storage\Base64Image;
use InvalidArgumentException;

/**
 * Creates event thumbnail (500×390 cover) and poster (max 1280px long edge) via ImageMagick.
 */
final class EventFeaturedImageProcessor
{
    private const THUMB_W = 500;

    private const THUMB_H = 390;

    private const MIN_SIDE = 500;

    private const POSTER_MAX_EDGE = 1280;

    private const MAX_OUTPUT_BYTES = 2097152;

    /** @var list<int> */
    private const QUALITY_STEPS = [85, 78, 72, 65, 58, 50, 42];

    /**
     * @return array{thumb: string, poster: string} Public URLs under /storage/event/…
     */
    public function processAndStore(string $base64Data): array
    {
        $image = new Base64Image($base64Data);
        $binary = $image->getBinary();

        $hash = substr(hash('sha256', $base64Data), 0, 16);
        $thumbLoc = PathHelper::eventFeaturedImageLocation($hash);
        $posterLoc = PathHelper::eventFeaturedImagePosterLocation($hash);

        $this->ensureParentDir($thumbLoc['fs']);
        $this->ensureParentDir($posterLoc['fs']);

        $tmpIn = tempnam(sys_get_temp_dir(), 'evfeat_');
        if ($tmpIn === false) {
            throw new InvalidArgumentException(lang('event.image.process_failed'));
        }

        try {
            file_put_contents($tmpIn, $binary);

            $info = @getimagesize($tmpIn);
            if ($info === false) {
                throw new InvalidArgumentException(lang('event.image.invalid_image'));
            }

            [$w, $h] = $info;
            if ($w < self::MIN_SIDE || $h < self::MIN_SIDE) {
                throw new InvalidArgumentException(lang('event.image.min_dimensions'));
            }

            $magick = $this->magickBinary();

            $this->writeJpegUnderSize(
                $magick,
                $tmpIn,
                $thumbLoc['fs'],
                [
                    '-auto-orient',
                    '-resize',
                    sprintf('%dx%d^', self::THUMB_W, self::THUMB_H),
                    '-gravity',
                    'center',
                    '-extent',
                    sprintf('%dx%d', self::THUMB_W, self::THUMB_H),
                ]
            );

            $this->writeJpegUnderSize(
                $magick,
                $tmpIn,
                $posterLoc['fs'],
                [
                    '-auto-orient',
                    '-resize',
                    sprintf('%dx%d>', self::POSTER_MAX_EDGE, self::POSTER_MAX_EDGE),
                ]
            );
        } finally {
            @unlink($tmpIn);
        }

        return [
            'thumb' => $thumbLoc['url'],
            'poster' => $posterLoc['url'],
        ];
    }

    /**
     * @param  list<string>  $middleArgs  Args between input path and -strip/-quality/output
     */
    private function writeJpegUnderSize(string $magick, string $inputPath, string $outputPath, array $middleArgs): void
    {
        foreach (self::QUALITY_STEPS as $quality) {
            $args = array_merge(
                [$magick, $inputPath],
                $middleArgs,
                ['-strip', '-quality', (string) $quality, $outputPath]
            );

            $this->execMagick($args);

            if (!is_file($outputPath)) {
                throw new InvalidArgumentException(lang('event.image.process_failed'));
            }

            if (filesize($outputPath) <= self::MAX_OUTPUT_BYTES) {
                return;
            }
        }

        if (filesize($outputPath) > self::MAX_OUTPUT_BYTES) {
            throw new InvalidArgumentException(lang('event.image.too_large_after_compress'));
        }
    }

    /**
     * @param  list<string>  $args
     */
    private function execMagick(array $args): void
    {
        $descriptorspec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $proc = proc_open($args, $descriptorspec, $pipes, null, null, ['bypass_shell' => true]);
        if (!is_resource($proc)) {
            throw new InvalidArgumentException(lang('event.image.process_failed'));
        }

        fclose($pipes[0]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $code = proc_close($proc);

        if ($code !== 0) {
            report(new \RuntimeException('ImageMagick failed: ' . $stderr));

            throw new InvalidArgumentException(lang('event.image.process_failed'));
        }
    }

    private function magickBinary(): string
    {
        foreach (['magick', 'convert'] as $name) {
            $path = trim((string) shell_exec('command -v ' . escapeshellarg($name) . ' 2>/dev/null'));
            if ($path !== '' && @is_executable($path)) {
                return $path;
            }
        }

        throw new InvalidArgumentException(lang('event.image.imagemagick_missing'));
    }

    private function ensureParentDir(string $filePath): void
    {
        $dir = dirname($filePath);
        if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
            throw new InvalidArgumentException(lang('event.image.process_failed'));
        }
    }
}
