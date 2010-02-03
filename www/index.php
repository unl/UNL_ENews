<?php
if (file_exists('config.inc.php')) {
    require_once 'config.inc.php';
} else {
    require 'config.sample.php';
}

$enews = new UNL_ENews_Controller($_GET);


Savvy_ClassToTemplateMapper::$classname_replacement = 'UNL_';
$savvy = new Savvy();
$savvy->setTemplatePath(dirname(__FILE__).'/templates');

if ($enews->actionable[0] instanceof UNL_ENews_File) {
    // pass through without any outer template
    echo $savvy->render($enews->actionable);
} else {
    echo $savvy->render($enews);
}