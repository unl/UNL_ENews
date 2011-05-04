<?php
/**
 * Class for building and rendering cacheable images.
 * 
 * 
 * @author bbieber
 *
 */
class UNL_ENews_File_Cacheable extends UNL_ENews_File implements UNL_ENews_CacheableInterface
{
    protected $options = array();

    function __construct($options)
    {
        $this->options = $options;
    }

    function preRun($cached)
    {
        if (isset($this->options['content-type'])) {
            $type = 'image/jpeg';
            switch($this->options['content-type']) {
                case 'png':
                    $type ='image/png';
                    break;
                case 'jpg':
                    $type = 'image/jpeg';
                    break;
                case 'gif':
                    $type = 'image/gif';
                    break;
            }
            header('Content-type: '.$type);
        }
    }

    function getCacheKey()
    {
        if (isset($this->options['id'], $this->options['content-type'])) {
            return __CLASS__.$this->options['id'].$this->options['content-type'];
        }

        // No clue what key to give for this file
        return false;
    }

    function run()
    {
        parent::__construct($this->options);
    }
}