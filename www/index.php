<?php
if (file_exists('config.inc.php')) {
    require_once 'config.inc.php';
} else {
    require 'config.sample.php';
}

$routes = include __DIR__ . '/../data/routes.php';

$router = new RegExpRouter\Router(array('baseURL' => UNL_ENews_Controller::$url));

$router->setRoutes($routes);

if (isset($_GET['model'])) {
    unset($_GET['model']);
}

$enews = new UNL_ENews_Controller($router->route($_SERVER['REQUEST_URI'], $_GET));

$savvy = new UNL_ENews_OutputController();

if (!isset($theme)) {
    $theme = 'MockU';
}

if ($enews->options['format'] != 'html') {
    switch($enews->options['format']) {
        case 'partial':
            $savvy->sendCORSHeaders();
            Savvy_ClassToTemplateMapper::$output_template['UNL_ENews_Controller'] = 'ENews/Controller-partial';
            break;
        case 'json':
            $savvy->sendCORSHeaders();
            header('Content-type:application/json');
            $savvy->setTemplatePath(dirname(__FILE__).'/templates/'.$enews->options['format']);
            break;
        case 'email':
            header('Content-type:text/html;charset=UTF-8');
            $savvy->addTemplatePath(dirname(__FILE__).'/templates/'.$enews->options['format']);
            break;
        case 'rss':
            header('Content-type:text/xml;charset=UTF-8');
            $savvy->addTemplatePath(dirname(__FILE__).'/templates/'.$enews->options['format']);
            break;
        case 'text':
            header('Content-type:text/plain;charset=UTF-8');
            $savvy->addTemplatePath(dirname(__FILE__).'/templates/'.$enews->options['format']);
            break;
        default:
            header('Content-type:text/html;charset=UTF-8');
    }
} elseif (isset($theme)) {
    header('Content-type:text/html;charset=UTF-8');
    $savvy->addTemplatePath(__DIR__ . '/themes/'.$theme);
}

// Always escape output, use $context->getRaw('var'); to get the raw data.
$savvy->setEscape('htmlentities');

if ($enews->actionable[0] instanceof UNL_ENews_File) {
    // pass through without any outer template
    echo $savvy->render($enews->actionable[0], 'ENews/File.tpl.php');
} else {
    echo $savvy->render($enews);
}