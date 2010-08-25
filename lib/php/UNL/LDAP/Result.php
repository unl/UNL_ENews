<?php
/**
 * LDAP result record
 *
 * PHP version 5
 * 
 * $Id$
 * 
 * @category  Default 
 * @package   UNL_LDAP
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/package/UNL_LDAP
 */
require_once 'UNL/LDAP/Entry.php';

/**
 * LDAP result record
 * 
 * @category  Default 
 * @package   UNL_LDAP
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/package/UNL_LDAP
 */
class UNL_LDAP_Result implements Countable, Iterator
{
    private $_link;

    private $_result;
    
    private $_valid = false;
    
    private $_currentEntry = false;
    
    /**
     * Resets the iterator to the first entry in the result set.
     *
     * @return void
     */
    function rewind()
    {
        $this->_currentEntry = ldap_first_entry($this->_link, $this->_result);
    }
    
    /**
     * returns the current entry in the result iteration
     *
     * @return UNL_LDAP_Entry
     */
    function current()
    {
        return new UNL_LDAP_Entry($this->_link, $this->_currentEntry);
    }
    
    /**
     * Advances the iterator to the next entry
     *
     * @return UNL_LDAP_Entry | false
     */
    function next()
    {
        if ($this->_currentEntry !== false 
            && $this->_currentEntry = ldap_next_entry($this->_link,
                                                      $this->_currentEntry)) {
            return $this->current();
        } else {
            $this->_valid = false;
            return false;
        }
    }
    
    /**
     * returns a key for this entry within the array
     *
     * @return unknown
     */
    function key()
    {
        //FIXME
        return $this->_currentEntry;
    }
    
    /**
     * returns whether this result is valid or not
     *
     * @return bool
     */
    function valid()
    {
        return $this->_valid;
    }
    
    /**
     * returns the size of the result
     *
     * @return int
     */
    public function count()
    {
        return ldap_count_entries($this->_link, $this->_result);
    }
    
    /**
     * Construct an LDAP Result object
     *
     * @param resource &$link   Connected ldap link
     * @param resource &$result Identifier for the result
     */
    public function __construct(&$link, &$result)
    {
        $this->_link   = $link;
        $this->_result = $result;
        $this->_valid  = true;
        
        $this->_currentEntry = ldap_first_entry($this->_link, $this->_result);
    }
    
    /**
     * frees the ldap result set
     *
     * @return void
     */
    function __destruct()
    {
        unset($this->_currentEntry);
        @ldap_free_result($this->_result);
    }
    
    /**
     * Sort the returned results by a specific attribute
     *
     * @param string $attr Attribute to sort by
     * 
     * @return void
     */
    public function sort($attr)
    {
        if (!ldap_sort($this->_link, $this->_result, $attr)) {
            throw new Exception('Failed to sort by '.$attr);
        }
    }
}
