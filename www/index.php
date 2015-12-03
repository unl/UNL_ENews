<?php
if (file_exists('config.inc.php')) {
    require_once 'config.inc.php';
} else {
    require 'config.sample.php';
}

require dirname(__FILE__).'/../vendor/autoload.php';

$routes = include __DIR__ . '/../data/routes.php';

$router = new RegExpRouter\Router(array('baseURL' => UNL_ENews_Controller::$url));

$router->setRoutes($routes);

if (isset($_GET['model'])) {
    unset($_GET['model']);
}

// Initialize ENews, and construct everything the user requested
$enews = new UNL_ENews_Controller($router->route($_SERVER['REQUEST_URI'], $_GET));


// Now render what the user has requested
$savvy = new UNL_ENews_OutputController();

if (isset($theme)) {
    $savvy->setTheme($theme);
}

if (isset($enews->options['shortname'])
    && is_dir(__DIR__ . '/themes/'.$enews->options['shortname'])) {
    $savvy->setTheme($enews->options['shortname']);
}

switch($enews->options['format']) {
    case 'json':
        $savvy->sendCORSHeaders();
        header('Content-type:application/json');
        $savvy->setTemplateFormatPaths($enews->options['format']);
        break;
    case 'email':
        header('Content-type:text/html;charset=UTF-8');
        $savvy->setTemplateFormatPaths('html');
        $savvy->addTemplateFormatPaths($enews->options['format']);
        break;
    case 'rss':
        $savvy->sendCORSHeaders();
        header('Content-type:text/xml;charset=UTF-8');
        $savvy->setTemplateFormatPaths($enews->options['format']);
        break;
    case 'text':
        header('Content-type:text/plain;charset=UTF-8');
        $savvy->setTemplateFormatPaths($enews->options['format']);
        break;
    case 'partial':
        $savvy->sendCORSHeaders();
        Savvy_ClassToTemplateMapper::$output_template['UNL_ENews_Controller'] = 'ENews/Controller-partial';
        // intentional no-break
    case 'html':
        header('Content-type:text/html;charset=UTF-8');
        $savvy->setTemplateFormatPaths('html');
        break;
    default:
        throw new Exception('Invalid/unsupported output format', 500);
}

// Always escape output, use $context->getRaw('var'); to get the raw data.
$savvy->setEscape('htmlentities');

if ($enews->actionable[0] instanceof UNL_ENews_File) {
    // pass through without any outer template
    StaticCache::autoCache();
    echo $savvy->render($enews->actionable[0], 'ENews/File.tpl.php');
} else {
    echo $savvy->render($enews);
}