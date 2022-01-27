<?php

use UNL\Templates\Templates;

Templates::setCachingService(new UNL\Templates\CachingService\NullService());
$page = Templates::factory('Local', Templates::VERSION_5_3);
$wdnIncludePath = dirname(dirname(dirname(dirname(__DIR__))));

if (file_exists($wdnIncludePath . '/wdn/templates_5.3')) {
    $page->setLocalIncludePath($wdnIncludePath);
}

$page->doctitle = '<title>' . $savvy->render($context, 'ENews/PageTitle.tpl.php') . ' | Announce | University of Nebraska–Lincoln</title>';
$page->titlegraphic = '<a class="dcf-txt-h5" href="' . UNL_ENews_Controller::$url . '">Announce</a>';

$page->head .= '
    <link rel="stylesheet" type="text/css" media="screen" href="' . UNL_ENews_Controller::getURL() . 'css/all.css?cb" />
    <link rel="home" href="' . UNL_ENews_Controller::getURL() . '" title="UNL Announce" />
    <link rel="logout" href="' . UNL_ENews_Controller::getURL() . '?logout" title="Log out" />
';

$page->addScriptDeclaration('var ENEWS_HOME = "'. UNL_ENews_Controller::getURL() . '"');

if (isset($context->options['q']) || isset($context->options['cn']) || isset($context->options['sn'])) {
    // Don't let search engines index these pages
    $page->head .= $savvy->render(null, 'static/meta-robots.tpl.php');
}

$page->breadcrumbs = '
    <ol>
        <li><a href="https://www.unl.edu/">Nebraska</a></li>
        <li><a href="https://ucomm.unl.edu/">UComm</a></li>
        <li><a href="' . UNL_ENews_Controller::getURL() . '">Announce</a></li>
        <li>' . $savvy->render($context, "ENews/PageTitle.tpl.php") . '</li>
    </ol>
';
$page->affiliation = '';
$page->navlinks = $savvy->render($context, 'ENews/Navigation.tpl.php');
$page->pagetitle = '<h1>' . $savvy->render($context, 'ENews/PageTitle.tpl.php') . '</h1>';

$page->maincontentarea = '<div class="dcf-bleed"><div class="dcf-wrapper dcf-pt-0 dcf-pb-8">' . $savvy->render($context->actionable) . '</div></div>';

$page->contactinfo = '
  <nav id="dcf-footer-group-1" role="navigation" aria-labelledby="dcf-footer-group-1-heading">
  <h3 class="dcf-txt-md dcf-bold dcf-uppercase dcf-lh-3 unl-ls-2 unl-cream" id="dcf-footer-group-1-heading">Announce</h3>
  <p>1400 R Street<br />
     Lincoln, NE 68588 <br />
     402-472-7211</p>
</nav>
<nav id="dcf-footer-group-2" role="navigation" aria-labelledby="dcf-footer-group-2-heading">
  <h3 class="dcf-txt-md dcf-bold dcf-uppercase dcf-lh-3 unl-ls-2 unl-cream" id="dcf-footer-group-2-heading">Related Links</h3>
  <ul class="dcf-list-bare dcf-mb-0">
    <li><a href="https://its.unl.edu/">Information Technology Services</a></li>
    <li><a href="https://events.unl.edu/">UNL Events</a></li>
  </ul>
</nav>
';

if (isset(UNL_ENews_Controller::$siteNotice) && UNL_ENews_Controller::$siteNotice->display) {
    $page->displayDCFNoticeMessage(UNL_ENews_Controller::$siteNotice->title,
        UNL_ENews_Controller::$siteNotice->message,
        UNL_ENews_Controller::$siteNotice->type,
        UNL_ENews_Controller::$siteNotice->noticePath,
        UNL_ENews_Controller::$siteNotice->containerID);
}

$page->addScriptDeclaration("WDN.initializePlugin('datepickers');");
$savvy->applyScriptDeclarations($page);
$savvy->applyScripts($page);

$html = $page->toHtml();
unset($page);

echo $html;
