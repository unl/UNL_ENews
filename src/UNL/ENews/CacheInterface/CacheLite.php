<?php
/**
 * A caching service utilizing Cache_Lite
 * 
 * @author bbieber
 */
class UNL_ENews_CacheInterface_CacheLite implements UNL_ENews_CacheInterface
{
    /**
     * Cache_Lite object
     * 
     * @var Cache_Lite
     */
    protected $cache;
    
    public $options = array('lifeTime'=>3600);
    
    /**
     * Constructor
     */
    function __construct($options = array())
    {
        $this->options = array_merge($this->options, $options);
        include_once 'Cache/Lite.php';
        $this->cache = new Cache_Lite($this->options);
    }
    
    /**
     * Get an item stored in the cache
     * 
     * @see UNL/ENews/UNL_ENews_CacheInterface#get()
     */
    function get($key)
    {
        return $this->cache->get($key, 'ENews');
    }
    
    /**
     * Save an element to the cache
     * 
     * @see UNL/ENews/UNL_ENews_CacheInterface#save()
     */
    function save($data, $key)
    {
        return $this->cache->save($data, $key, 'ENews');
    }

}

?>