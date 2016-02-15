<?php

use UNL\Templates\Templates;

Templates::setCachingService(new UNL\Templates\CachingService\NullService());
$page = Templates::factory('Fixed', Templates::VERSION_4_1);
$wdnIncludePath = dirname(dirname(__DIR__));

if (file_exists($wdnIncludePath . '/wdn/templates_4.1')) {
    $page->setLocalIncludePath($wdnIncludePath);
}

$page->doctitle = '<title>' . $savvy->render($context, 'ENews/PageTitle.tpl.php') . 'Announce | University of Nebraska–Lincoln</title>';
$page->titlegraphic = 'UNL Announce';

$page->head .= '
    <link rel="stylesheet" type="text/css" media="screen" href="' . UNL_ENews_Controller::getURL() . 'css/all.css" />
    <link rel="home" href="' . UNL_ENews_Controller::getURL() . '" title="UNL Announce" />
    <link rel="logout" href="' . UNL_ENews_Controller::getURL() . '?logout" title="Log out" />
    <script type="text/javascript">
    var ENEWS_HOME = "'. UNL_ENews_Controller::getURL() .'";
    </script>
';

if (isset($context->options['q']) || isset($context->options['cn']) || isset($context->options['sn'])) {
    // Don't let search engines index these pages
    $page->head .= $savvy->render(null, 'static/meta-robots.tpl.php');
}

$page->breadcrumbs = '
    <ul>
        <li><a href="http://www.unl.edu/" title="University of Nebraska-Lincoln Home">UNL</a></li>
        <li><a href="http://ucomm.unl.edu/" title="Office of University Communications">UComm</a></li>
        <li><a href="http://newsroom.unl.edu/" title="UNL Newsroom">Newsroom</a></li>
        <li><a href="' . UNL_ENews_Controller::getURL() . '">Announce</a></li>
        <li>' . $savvy->render($context, "ENews/PageTitle.tpl.php") . '</li>
    </ul>
';
$page->affiliation = '';
$page->navlinks = $savvy->render($context, 'ENews/Navigation.tpl.php');
$page->pagetitle = '<h1>' . $savvy->render($context, 'ENews/PageTitle.tpl.php') . '</h1>';
$page->leftcollinks = '';

$page->maincontentarea = '<div class="wdn-band"><div class="wdn-inner-wrapper wdn-inner-padding-no-top">' . $savvy->render($context->actionable) . '</div></div>';

$page->contactinfo = '
    <div class="wdn-grid-set">
        <div class="bp960-wdn-col-one-half">
            <div class="wdn-footer-module">
                <span class="wdn-footer-heading" role="heading">UNL Announce</span>
                <p>         1400 R Street<br />
                            Lincoln, NE 68588 <br />
                            402-472-7211</p>
            </div>
        </div>
        <div class="bp960-wdn-col-one-half">
            <div class="wdn-footer-module">
                <span class="wdn-footer-heading" role="heading">Related Links</span>
                <ul class="wdn-related-links-v2">
                    <li><a href="http://www.unl.edu/ucomm/chancllr/" title="Welcome, statements, initiatives of the Office of the Chancellor">Office of the Chancellor</a></li>
                    <li><a href="http://www.unl.edu/ucomm/ucomm/speakers/" title="UNL Speakers Bureau">Speakers Bureau</a></li>
                    <li><a href="http://www.unl.edu/is/" title="Computing and telecommunications services for the university community">Information Technology Services</a></li>
                    <li><a href="http://iris.unl.edu/" title="Catalogs, resources, services and information from University Libraries">Libraries</a></li>
                    <li><a href="http://www.unl.edu/regrec/calendar/calendar_main.shtml" title="Academic Calender">Academic Calendar</a></li>
                    <li><a href="http://events.unl.edu/" title="Upcoming UNL events in a searchable database">UNL Events</a></li>
                </ul>
            </div>
        </div>
    </div>
';

$html = $page->toHtml();
unset($page);

echo $html;
