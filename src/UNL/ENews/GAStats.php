<?php
require dirname(__FILE__).'/../../../lib/php/gapi.class.php';

use Widop\GoogleAnalytics\Query;
use Widop\GoogleAnalytics\Client;
use Widop\HttpAdapter\CurlHttpAdapter;
use Widop\GoogleAnalytics\Service;


class UNL_ENews_GAStats extends UNL_ENews_LoginRequired
{
    public static $ga_profile_id;
    public static $ga_client_id;
    
    public $response;

    function __postConstruct()
    {
        $newsletter_id = $this->options['newsletter'];
        $newsletter = UNL_ENews_Newsletter::getByID($newsletter_id);
        $newsroom = $newsletter->getNewsroom();
        $shortname = $newsroom->shortname;

        $filter = 'pagePath=~^'.str_replace('/', '\/', str_replace(array('http://', 'https://'), '', UNL_ENews_Controller::$url)).$shortname.'\/'.$newsletter_id.'\/';
        $max_results = 100;

        $profileId = 'ga:'.self::$ga_profile_id;
        $query = new Query($profileId);

        $query->setStartDate(new \DateTime($this->options['start_date']));
        $query->setEndDate(new \DateTime($this->options['end_date']));

        // See https://developers.google.com/analytics/devguides/reporting/core/dimsmets
        $query->setMetrics(array('ga:pageviews' ,'ga:visits'));
        $query->setDimensions(array('ga:pagePath', 'ga:pageTitle'));

        // See https://developers.google.com/analytics/devguides/reporting/core/v3/reference#sort
        $query->setSorts(array('ga:pageviews'));

        // See https://developers.google.com/analytics/devguides/reporting/core/v3/reference#filters
        $query->setFilters(array('ga:'.$filter));
        
        // Default values :)
        $query->setStartIndex(1);
        $query->setMaxResults($max_results);
        $query->setPrettyPrint(false);
        $query->setCallback(null);
        
        //Set up the client
        $cert_file = dirname(__FILE__).'/../../../ga_cert.p12';
        
        if (!file_exists($cert_file)) {
            throw new \Exception('Cert file does not exist', 500);
        }
        
        $httpAdapter = new CurlHttpAdapter();

        $client = new Client(self::$ga_client_id, $cert_file, $httpAdapter);
        
        //Get results
        $service = new Service($client);
        $this->response = $service->query($query);
    }
}
