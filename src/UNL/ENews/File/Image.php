<?php
class UNL_ENews_File_Image extends UNL_ENews_File
{
    /**
     * Save a thumbnail and return the object
     * 
     * @return UNL_ENews_File_Image
     */
    function saveThumbnail($x1=0,$x2=0,$y1=0,$y2=0,$thumb_width=96,$thumb_height=72)
    {
        // Crop the image ***************************************************************
        // Get dimensions of the original image
        $filename = UNL_ENews_Controller::getURL().'?view=file&id='.(int)$this->id;
        list($current_width, $current_height) = getimagesize($filename);
        
        if ($x1 < 0) {
            // User did not select a cropping area
            $left   = 0;
            $top    = 0;
            $right  = $current_width;
            $bottom = $current_width*(3/4);
        } else {
            // Needs to be adjusted to account for the scaled down 410px-width size that's displayed to the user
            if ($current_width > 410) {
                $left   = ($current_width/410)*$x1;
                $top    = ($current_height/(410*$current_height/$current_width))*$y1;
                $right  = ($current_width/410)*$x2;
                $bottom = ($current_height/(410*$current_height/$current_width))*$y2;
            } else {
                $left   = $x1;
                $top    = $y1;
                $right  = $x2;
                $bottom = $y2;
            }
        }
        
        // This will be the final size of the cropped image
        $crop_width = $right-$left;
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
        $current_image = $create_method($filename);
        
        imagecopy($croppedimage, $current_image, 0, 0, $left, $top, $current_width, $current_height);
        
        // Resize the image ************************************************************
        $current_width = $crop_width;
        $current_height = $crop_height;
        $canvas = imagecreatetruecolor($thumb_width, $thumb_height);
        imagecopyresampled($canvas, $croppedimage, 0, 0, 0, 0, $thumb_width, $thumb_height, $current_width, $current_height);
        
        ob_start();
        $output_method($canvas);
        imagedestroy($canvas);

        $thumb = new self();
        $thumb->name = $this->name;
        $thumb->type = $this->type;
        $thumb->size = ob_get_length();
        $thumb->data = ob_get_clean();

        // Save the thumbnail **********************************************************
        $thumb->use_for = 'thumbnail';
        if ($thumb->save()) {
            return $thumb;
        }
        return false;
    }
}
?>