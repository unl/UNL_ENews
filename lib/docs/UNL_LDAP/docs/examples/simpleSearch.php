<?php
/**
 * This file conducts a simple ldap search
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
chdir(dirname(__FILE__).'/../../');
require_once 'UNL/LDAP.php';
require_once 'config.inc.php';

$ldap   = UNL_LDAP::getConnection($options);
$results = $ldap->search('dc=unl,dc=edu', '(|(sn=ryan lim)(cn=ryan lim)(&(| (givenname=ryan) (sn=ryan) (mail=ryan) (unlemailnickname=ryan) (unlemailalias=ryan))(| (givenname=lim) (sn=lim) (mail=lim) (unlemailnickname=lim) (unlemailalias=lim))))');

$results->sort('uid');

echo count($results).' results found.'.PHP_EOL;

foreach ($results as $entry) {
    echo '<pre>';
    print_r($entry);
    
    echo $entry->givenName.' '.$entry->sn.' is '.$entry->uid.PHP_EOL;
    echo $entry->cn;
    if (count($entry->objectClass)) {
        echo $entry->givenName.' is a member of:';
        foreach ($entry->objectClass as $class) {
            echo $class.',';
        }
        echo PHP_EOL.'<br>';
    }
}
highlight_file(__FILE__);