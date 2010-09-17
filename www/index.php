<?php
if (file_exists('config.inc.php')) {
    require_once 'config.inc.php';
} else {
    require 'config.sample.php';
}

$enews = new UNL_ENews_Controller(UNL_ENews_Router::route($_SERVER['REQUEST_URI']) + $_GET);

$savvy = new UNL_ENews_OutputController();


if ($enews->options['format'] != 'html') {
    switch($enews->options['format']) {
        case 'partial':
            Savvy_ClassToTemplateMapper::$output_template['UNL_ENews_Controller'] = 'ENews/Controller-partial';
            break;
        case 'rss':
            $savvy->addTemplatePath(dirname(__FILE__).'/templates/'.$enews->options['format']);
            break;
        default:
            header('Content-type:text/html;charset=UTF-8');
    }
}

// Always escape output, use $context->getRaw('var'); to get the raw data.
$savvy->setEscape('htmlentities');

if ($enews->actionable[0] instanceof UNL_ENews_File) {
    // pass through without any outer template
    echo $savvy->render($enews->actionable[0], 'ENews/File.tpl.php');
} else {
    echo $savvy->render($enews);
}