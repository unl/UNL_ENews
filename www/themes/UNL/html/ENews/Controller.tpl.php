<?php

use UNL\Templates\Templates;

Templates::setCachingService(new UNL\Templates\CachingService\NullService());
$page = Templates::factory('Fixed', Templates::VERSION_5);
$wdnIncludePath = dirname(dirname(dirname(dirname(__DIR__))));

if (file_exists($wdnIncludePath . '/wdn/templates_5.0')) {
    $page->setLocalIncludePath($wdnIncludePath);
}

$page->doctitle = '<title>' . $savvy->render($context, 'ENews/PageTitle.tpl.php') . ' | Announce | University of Nebraska–Lincoln</title>';
$page->titlegraphic = '<a class="dcf-txt-h5" href="/">Announce</a>';

$page->head .= '
    <link rel="stylesheet" type="text/css" media="screen" href="' . UNL_ENews_Controller::getURL() . 'css/all.css" />
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
        <li><a href="http://www.unl.edu/" title="University of Nebraska-Lincoln Home">UNL</a></li>
        <li><a href="http://ucomm.unl.edu/" title="Office of University Communications">UComm</a></li>
        <li><a href="http://newsroom.unl.edu/" title="UNL Newsroom">Newsroom</a></li>
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
  <h3 class="dcf-txt-md dcf-bold dcf-uppercase dcf-lh-3 unl-ls-2 unl-cream" id="dcf-footer-group-1-heading">UNL Announce</h3>
  <p>1400 R Street<br />
     Lincoln, NE 68588 <br />
     402-472-7211</p>
</nav>
<nav id="dcf-footer-group-2" role="navigation" aria-labelledby="dcf-footer-group-2-heading">
  <h3 class="dcf-txt-md dcf-bold dcf-uppercase dcf-lh-3 unl-ls-2 unl-cream" id="dcf-footer-group-2-heading">Related Links</h3>
  <ul class="dcf-list-bare dcf-mb-0">
    <li><a href="http://www.unl.edu/ucomm/chancllr/" title="Welcome, statements, initiatives of the Office of the Chancellor">Office of the Chancellor</a></li>
    <li><a href="http://www.unl.edu/ucomm/ucomm/speakers/" title="UNL Speakers Bureau">Speakers Bureau</a></li>
    <li><a href="http://www.unl.edu/is/" title="Computing and telecommunications services for the university community">Information Technology Services</a></li>
    <li><a href="http://iris.unl.edu/" title="Catalogs, resources, services and information from University Libraries">Libraries</a></li>
    <li><a href="http://www.unl.edu/regrec/calendar/calendar_main.shtml" title="Academic Calender">Academic Calendar</a></li>
    <li><a href="http://events.unl.edu/" title="Upcoming UNL events in a searchable database">UNL Events</a></li>
  </ul>
</nav>
';

$savvy->applyScriptDeclarations($page);
$savvy->applyScripts($page);

$html = $page->toHtml();
unset($page);

echo $html;
