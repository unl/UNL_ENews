<?php
/**
 * Interface cacheable objects must implement.
 * 
 * @author bbieber
 */
interface UNL_ENews_CacheableInterface
{
    public function getCacheKey();
    public function run();
    public function preRun();
}
