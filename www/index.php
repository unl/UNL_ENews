<?php
if (file_exists('config.inc.php')) {
    require_once 'config.inc.php';
} else {
    require 'config.sample.php';
}

$enews = new UNL_ENews_Controller($_GET);
/*
?><pre><?php var_dump($enews);?></pre><?php 
*/
Savvy_ClassToTemplateMapper::$classname_replacement = 'UNL_';
$savvy = new Savvy();
$savvy->setTemplatePath(dirname(__FILE__).'/templates/default');


if ($enews->options['format'] != 'html') {
    switch($enews->options['format']) {
        case 'rss':
            $savvy->addTemplatePath(dirname(__FILE__).'/templates/'.$enews->options['format']);
            break;
        default:
    }
}


if ($enews->actionable[0] instanceof UNL_ENews_File) {
    // pass through without any outer template
    echo $savvy->render($enews->actionable);
} else {
    echo $savvy->render($enews);
}