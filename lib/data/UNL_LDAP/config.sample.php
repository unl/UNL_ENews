<?php
/**
 * This is a sample configuration file for the LDAP connection
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
require_once 'UNL/LDAP.php';

$options['bind_dn']       = 'uid=youruidhere,ou=service,dc=unl,dc=edu';
$options['bind_password'] = 'yourpasswordhere';
