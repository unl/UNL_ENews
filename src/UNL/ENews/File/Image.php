<?php
class UNL_ENews_File_Image extends UNL_ENews_File
{
    const THUMB_WIDTH    = 96;
    const THUMB_HEIGHT   = 72;

    const FULL_AD_WIDTH  = 556;
    const FULL_AD_HEIGHT = 212;

    const HALF_AD_WIDTH  = 273;
    const HALF_AD_HEIGHT = 104;

    // Max image size displayed to the user
    const MAX_WIDTH      = 410;

    /**
     * Save a thumbnail and return the object
     * 
     * Sample:
     * ------------------------------
     * |                            |
     * |      y1----------          |
     * |      |          |          |
     * |      |          |          |
     * |    x1,y2--------x2         |
     * |-----------------------------
     * 
     * @param int $x1  X coordinate offset start
     * @param int $x2  X coordinate offset end
     * @param int $y1  Y coordinate offset start
     * @param int $y2  Y coordinate offset end
     * @param int $width  Final image width
     * @param int $height Final image height
     * 
     * @return UNL_ENews_File_Image
     */
    function saveThumbnail($x1=0, $x2=0, $y1=0, $y2=0)
    {
        // Determine orientation
        if ($x2 - $x1 > $y2 - $y1) {
            $width=self::THUMB_WIDTH;
            $height=self::THUMB_HEIGHT;
        } else {
            $width=self::THUMB_HEIGHT;
            $height=self::THUMB_WIDTH;
        }

        // Crop the image ***************************************************************
        // Get dimensions of the original image
        list($current_width, $current_height) = getimagesize('data://'.$this->type.';base64,' . base64_encode($this->data));

        if ($x1 < 0) {
            // User did not select a cropping area
            $x1   = 0;
            $y1    = 0;
            $x2  = $current_width;
            $y2 = $current_width*($height/$width);
        } else {
            // Needs to be adjusted to account for the scaled down 410px-width size that's displayed to the user
            if ($current_width > self::MAX_WIDTH) {
                $x1   = ($current_width/self::MAX_WIDTH)*$x1;
                $y1    = ($current_height/(self::MAX_WIDTH*$current_height/$current_width))*$y1;
                $x2  = ($current_width/self::MAX_WIDTH)*$x2;
                $y2 = ($current_height/(self::MAX_WIDTH*$current_height/$current_width))*$y2;
            }
        }

        if ($thumb = $this->resizeImage($x1, $x2, $y1, $y2, $width, $height)) {
            $thumb->use_for = 'thumbnail';
            $thumb->save();
            return $thumb;
        }

        return false;
    }

    function resizeImage($x1=0, $x2=0, $y1=0, $y2=0, $width, $height)
    {
        $file = 'data://'.$this->type.';base64,' . base64_encode($this->data);
        list($current_width, $current_height) = getimagesize($file);
        // This will be the final size of the cropped image
        $crop_width  = $x2-$x1;
        $crop_height = $y2-$y1;

        // Resample the image
        $croppedimage = imagecreatetruecolor($crop_width, $crop_height);
        switch ($this->type) {
            case 'image/jpeg':
            case 'image/pjpeg':
                $create_method = 'imagecreatefromjpeg';
                $output_method = 'imagejpeg';
                break;
            case 'image/png':
            case 'image/x-png':
                $create_method = 'imagecreatefrompng';
                $output_method = 'imagepng';
                break;
            case 'image/gif':
                $create_method = 'imagecreatefromgif';
                $output_method = 'imagegif';
                break;
        }
        $current_image = $create_method($file);

        imagecopy($croppedimage, $current_image, 0, 0, $x1, $y1, $current_width, $current_height);

        // Resize the image ************************************************************
        $current_width = $crop_width;
        $current_height = $crop_height;
        $canvas = imagecreatetruecolor($width, $height);
        imagecopyresampled($canvas, $croppedimage, 0, 0, 0, 0, $width, $height, $current_width, $current_height);

        ob_start();
        $output_method($canvas);
        imagedestroy($canvas);

        $resized = new self();
        $resized->name = $this->name;
        $resized->type = $this->type;
        $resized->size = ob_get_length();
        $resized->data = ob_get_clean();
        

        // Save the thumbnail **********************************************************
        if ($resized->save()) {
            return $resized;
        }

        return false;
    }
}
