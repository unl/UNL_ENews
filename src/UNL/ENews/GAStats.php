<?php
require dirname(__FILE__).'/../../../lib/php/gapi.class.php';

class UNL_ENews_GAStats extends UNL_ENews_LoginRequired
{
    public static $ga_email;
    public static $ga_password;
    public static $ga_profile_id;
    
    public $ga;

    function __postConstruct()
    {
        $this->ga = gapiClientLogin::authenticate(self::$ga_email, self::$ga_password);
        $newsletter_id = $this->options['newsletter'];
        $newsletter = UNL_ENews_Newsletter::getByID($newsletter_id);
        $newsroom = $newsletter->getNewsroom();
        $shortname = $newsroom->shortname;
        $filter = 'pagePath=~^newsroom.unl.edu\/announce\/'.$shortname.'\/'.$newsletter_id.'\/';
        $start_date = $this->options['start_date'];
        $end_date = $this->options['end_date'];
        $max_results = 20;

        $this->ga->requestReportData(self::$ga_profile_id,
            array('pagePath','pageTitle'),
            array('pageviews', 'visits'),
            '-pageviews',$filter,$start_date,$end_date, null, $max_results);
    }
}