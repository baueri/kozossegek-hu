<?php

namespace App\Storage;

class Base64Image
{
    
    private $imageSource = '';
    
    /**
     * 
     * @param string $imageData
     */
    public function __construct(string $imageData)
    {
        $this->imageSource = base64_decode(substr($imageData, strpos($imageData,',')));
    }
    
    public function saveImage(string $path)
    {
        $this->createDirIfMissing($path);
        
        return file_put_contents($path, $this->imageSource);
    }
    
    public function saveThumbnail(string $path)
    {
        $this->createDirIfMissing($path);
        
        $thumbnail = imagecrop(imagecreatefromstring($this->imageSource), ['x' => 0, 'y' => 350, 'width' => 400, 'height' => 250]);
        
        ob_start();
        imagejpeg($thumbnail);
        
        $thumnailSource = ob_get_clean();
        
        file_put_contents($path, $thumnailSource);
    }
    
    protected function createDirIfMissing($path)
    {
        $dirname = dirname($path);
        
        if(!is_dir($dirname) && !file_exists($dirname)) {
            if(!mkdir($dirname, 0775, true)) {
                throw new \Exception("Cannot create image dir: $dirname");
            }
        }
    }
   
}
