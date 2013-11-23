<?php

class Image {

    public $imageDir;
    private $info = '';
    public $newWidth;
    public $newHeight;
    public $top = 0;
    public $left = 0;
    public $quality = 60;
    public $imageFileName;
    public $pre;

    public function __construct($imageDir) {
        $this->imageDir = $imageDir;
        $this->pre = "other";
    }

    /**
     * 
     * @param type $imageFileName
     * @return type
     */
    public function miniThumbnail($imageFileName) {
        $this->pre = "thumbnail_";
        return $this->resize($imageFileName, 75, 75);
    }

    /**
     * 
     * @param type $imageFileName
     * @return type
     */
    public function smallThumbnail($imageFileName) {
        $this->pre = "thumbnail_";
        return $this->resize($imageFileName, 100, 100);
    }

    /**
     * 
     * @param type $imageFileName
     * @return type
     */
    public function mediumThumbnail($imageFileName) {
        $this->pre = "thumbnail_";
        return $this->resize($imageFileName, 125, 125);
    }

    /**
     * 
     * @param type $imageFileName
     * @return type
     */
    public function largeThumbnail($imageFileName) {
        $this->pre = "thumbnail_";
        return $this->resize($imageFileName, 150, 150);
    }

    /**
     * 
     * @param type $file
     * @param type $width
     * @param type $height
     * @param type $top
     * @param type $left
     * @return type
     */
    public function crop($imageFileName = '', $width = '', $height = '', $top = '', $left = '') {
        if ($imageFileName != "") {
            $this->imageFileName = $imageFileName;
        }
        if ($width != '')
            $this->newWidth = $width;
        if ($height != '')
            $this->newHeight = $height;
        if ($top != '')
            $this->top = $top;
        if ($left != '')
            $this->left = $left;
        return $this->process(true);
    }

    /**
     * 
     * @param type $file
     * @param type $width
     * @param type $height
     * @param type $fixed
     * @return type
     */
    public function resize($imageFileName = '', $width = '', $height = '', $fixed = 'width') {
        if ($imageFileName != "") {
            $this->imageFileName = $imageFileName;
        }
        if ($width != '')
            $this->newWidth = $width;
        if ($height != '')
            $this->newHeight = $height;
        return $this->process(false, $fixed);
    }

    /**
     * 
     * @param type $crop
     * @param type $fixed
     * @return type
     */
    private function process($crop, $fixed) {
        $ext = strtolower(end(explode(".", $this->imageFileName)));
        list($width, $height) = getimagesize($this->imageDir . $this->imageFileName);
        if (!$crop) {
            $ratio = $width / $height;
            if ($fixed == "width") {
                if ((int)$this->newHeight > 0 && $this->newWidth / $this->newHeight > $ratio) {
                    $this->newWidth = $this->newHeight * $ratio;
                } else {
                    $this->newHeight = round($this->newWidth / $ratio);
                }
            } else {
                if ((int)$this->newHeight > 0 && $this->newWidth / $this->newHeight > $ratio) {
                    $this->newHeight = $this->newWidth * $ratio;
                } else {
                    $this->newWidth = round($this->newHeight / $ratio);
                }
            }
        }
        $normal = imagecreatetruecolor($this->newWidth, $this->newHeight);
        if ($ext == "jpg") {
            $src = imagecreatefromjpeg($this->imageDir . $this->imageFileName);
        } else if ($ext == "gif") {
            $src = imagecreatefromgif($this->imageDir . $this->imageFileName);
        } else if ($ext == "png") {
            $src = imagecreatefrompng($this->imageDir . $this->imageFileName);
        }
        if ($crop) {
            if (imagecopy($normal, $src, 0, 0, $this->top, $this->left, $this->newWidth, $this->newHeight)) {
                $this->info .= '<div>image was cropped and saved.</div>';
            }
            $dir = $this->imageDir;
        } else {
            if (imagecopyresampled($normal, $src, 0, 0, 0, 0, $this->newWidth, $this->newHeight, $width, $height)) {
                $this->info .= '<div>image was resized and saved.</div>';
            }
            $dir = $this->imageDir;
        }
        if ($ext == "jpg" || $ext == "jpeg") {
            imagejpeg($normal, $dir . $this->pre . $this->imageFileName, $this->quality);
        } else if ($ext == "gif") {
            imagegif($normal, $dir . $this->pre . $this->imageFileName);
        } else if ($ext == "png") {
            imagepng($normal, $dir . $this->pre . $this->imageFileName, 0);
        }
        imagedestroy($src);

        return $src;
    }

}