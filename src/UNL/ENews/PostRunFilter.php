<?php
class UNL_ENews_PostRunFilter
{
    static $data;

    static function setReplacementData($field, $data)
    {
        self::$data[$field] = $data;
    }

    public static function postRun($data)
    {
        if (isset(self::$data['pagetitle'])) {
            $data = str_replace('<title> Announce | University of Nebraska-Lincoln</title>',
                                '<title>'.self::$data['pagetitle'].' | Announce | University of Nebraska-Lincoln</title>',
            $data);
            $data = str_replace('<h2></h2>',
                                '<h2>'.self::$data['pagetitle'].'</h2>',
            $data);
        }
        if (isset(self::$data['sitetitle'])) {
            $data = str_replace('<h1>UNL Announce</h1>',
                                '<h1>'.self::$data['sitetitle'].'</h1>',
            $data);
        }
        return $data;
    }
}
