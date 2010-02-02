<?php
require_once 'config.inc.php';

$pf = new UNL_Peoplefinder();
$uid = $pf->getUID('bbieber2');
echo 'uid:' . $uid->uid;
echo ' cn:' . $uid->cn;