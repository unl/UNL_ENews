<?php

interface UNL_ENews_PostRunReplacements
{
    static function setReplacementData($field, $data);
    public function postRun($data);
}

?>