<?php
/**
 * This page provides the peoplefinder service to applications.
 *
 */

require_once 'config.inc.php';

// Specify domains from which requests are allowed
header('Access-Control-Allow-Origin: *');

// Specify which request methods are allowed
header('Access-Control-Allow-Methods: GET, OPTIONS');

// Additional headers which may be sent along with the CORS request
// The X-Requested-With header allows jQuery requests to go through
header('Access-Control-Allow-Headers: X-Requested-With');

// Set the ages for the access-control header to 20 days to improve speed/caching.
header('Access-Control-Max-Age: 1728000');

// Set expires header for 24 hours to improve speed caching.
header('Expires: '.date('r', strtotime('tomorrow')));

// Exit early so the page isn't fully loaded for options requests
if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
    exit();
}

$peepObj = new UNL_Peoplefinder();

$format = 'html';

$renderer_options = array('uid_onclick' => 'pf_getUID',
                          'uri'         => UNL_PEOPLEFINDER_URI);
if (isset($_GET['chooser'])) {
    $renderer_options['choose_uid'] = true;
}

if (isset($_GET['renderer']) || isset($_GET['format'])) {
    $format = isset($_GET['renderer'])?$_GET['renderer']:$_GET['format'];
}
switch($format) {
case 'vcard':
    $renderer_class = 'vCard';
    break;
case 'serialized':
case 'php':
    $renderer_class = 'Serialized';
    break;
case 'xml':
    $renderer_class = 'XML';
    break;
case 'json':
    $renderer_class = 'JSON';
    break;
default:
case 'hcard':
case 'html':
    $renderer_class = 'HTML';
    break;
}

$method = false;

if (isset($_GET['method'])) {
    switch ($_GET['method']) {
    case 'getLikeMatches':
    case 'getExactMatches':
    case 'getPhoneMatches':
        $method = $_GET['method'];
        break;
    }
}

$renderer_class = 'UNL_Peoplefinder_Renderer_'.$renderer_class;
$renderer = new $renderer_class($renderer_options);
if (isset($_GET['q']) && !empty($_GET['q'])) {
    // Basic query, build filter and display results
    if (strlen($_GET['q']) > 3) {
        if (is_numeric(str_replace('-','',str_replace('(','',str_replace(')','',$_GET['q']))))) {
            $records = $peepObj->getPhoneMatches($_GET['q']);
            $renderer->renderSearchResults($records);
        } else {
            if ($method) {
                $records = $peepObj->$method($_GET['q']);
                if (count($records) > 0) {
                    $renderer->renderSearchResults($records);
                } else {
                    $renderer->renderError();
                }
            } else {
                $records = $peepObj->getExactMatches($_GET['q']);
                if (count($records) > 0) {
                    if ($renderer instanceof UNL_Peoplefinder_Renderer_HTML) {
                        echo "<div class='cMatch'>Exact matches:</div>\n";
                    }
                    $renderer->renderSearchResults($records);
                } else {
                    if ($renderer instanceof UNL_Peoplefinder_Renderer_HTML) {
                        echo 'No exact matches found.';
                    }
                }
                if (count($records) < UNL_Peoplefinder::$displayResultLimit) {
                    // More room to display LIKE results
                    UNL_Peoplefinder::$displayResultLimit = UNL_Peoplefinder::$displayResultLimit-$peepObj->lastResultCount;
                    $records = $peepObj->getLikeMatches($_GET['q'], $records);
                    if (count($records) > 0) {
                        if ($renderer instanceof UNL_Peoplefinder_Renderer_HTML) {
                           echo "<div class='cMatch'>Possible matches:</div>\n";
                        }
                        $renderer->renderSearchResults($records);
                    }
                }
            }
        }
    } else {
        $renderer->renderError();
    }
} elseif (isset($_GET['uid']) && !empty($_GET['uid'])) {
    $renderer->renderRecord($peepObj->getUID($_GET['uid']));
} elseif (isset($_GET['d'])) {
    try {
        $department = new UNL_Peoplefinder_Department($_GET['d']);
        foreach ($department as $employee) {
            $renderer->renderRecord($employee);
        }
    } catch(Exception $e) {
        $renderer->renderError($e->getMessage());
    }
} else {
    $renderer->renderError();
}
