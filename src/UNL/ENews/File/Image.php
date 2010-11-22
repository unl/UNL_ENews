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
     * @param int $left   X coordinate offset start
     * @param int $right  X coordinate offset end
     * @param int $top    Y coordinate offset start
     * @param int $bottom Y coordinate offset end
     * @param int $width  Final image width
     * @param int $height Final image height
     * 
     * @return UNL_ENews_File_Image
     */
    function saveThumbnail($left=0, $right=0, $top=0, $bottom=0, $width=self::THUMB_WIDTH, $height=self::THUMB_HEIGHT)
    {
        // Crop the image ***************************************************************
        // Get dimensions of the original image
        list($current_width, $current_height) = getimagesize('data://'.$this->type.';base64,' . base64_encode($this->data));
        
        if ($left < 0) {
            // User did not select a cropping area
            $left   = 0;
            $top    = 0;
            $right  = $current_width;
            $bottom = $current_width*(self::THUMB_HEIGHT/self::THUMB_WIDTH);
        } else {
            // Needs to be adjusted to account for the scaled down 410px-width size that's displayed to the user
            if ($current_width > self::MAX_WIDTH) {
                $left   = ($current_width/self::MAX_WIDTH)*$left;
                $top    = ($current_height/(self::MAX_WIDTH*$current_height/$current_width))*$top;
                $right  = ($current_width/self::MAX_WIDTH)*$right;
                $bottom = ($current_height/(self::MAX_WIDTH*$current_height/$current_width))*$bottom;
            }
        }
        
        if ($thumb = $this->resizeImage($left, $right, $top, $bottom, $width, $height)) {
            $thumb->use_for = 'thumbnail';
            $thumb->save();
            return $thumb;
        }
        return false;
        
    }

    function resizeImage($left=0, $right=0, $top=0, $bottom=0, $width, $height)
    {
        $file = 'data://'.$this->type.';base64,' . base64_encode($this->data);
        list($current_width, $current_height) = getimagesize($file);
        // This will be the final size of the cropped image
        $crop_width  = $right-$left;
        $crop_height = $bottom-$top;
        
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
        
        imagecopy($croppedimage, $current_image, 0, 0, $left, $top, $current_width, $current_height);
        
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
