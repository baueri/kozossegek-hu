<?php

namespace App\Storage;

use Exception;
use InvalidArgumentException;

class Base64Image
{
    private string $imageSource = '';

    /**
     * @param string $imageData
     */
    public function __construct(string $imageData)
    {
        $source = base64_decode(substr($imageData, strpos($imageData, ',')));

        if (preg_match('/(eval\(|base64_encode|base64_decode|#!\/bin\/)/', $source)) {
            throw new InvalidArgumentException('érvénytelen kép tartalom');
        }

        $this->imageSource = $source;
    }

    /**
     * @throws Exception
     */
    public function saveImage(string $path)
    {
        $this->createDirIfMissing($path);

        return file_put_contents($path, $this->imageSource);
    }

    /**
     * @throws Exception
     */
    public function saveThumbnail(string $path): void
    {
        $this->createDirIfMissing($path);

        $thumbnail = imagecrop(
            imagecreatefromstring($this->imageSource),
            ['x' => 0, 'y' => 350, 'width' => 400, 'height' => 250]
        );

        ob_start();
        imagejpeg($thumbnail);

        $thumnailSource = ob_get_clean();

        file_put_contents($path, $thumnailSource);
    }

    protected function createDirIfMissing(string $path): void
    {
        $dirname = dirname($path);

        if (!is_dir($dirname) && !file_exists($dirname)) {
            if (!@mkdir($dirname, 0775, true)) {
                throw new Exception("Cannot create image dir: $dirname");
            }
        }
    }
}
