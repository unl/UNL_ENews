--TEST--
Basic announcement submission test
--SKIPIF--
<?php
if (!class_exists('mysqli')) {
    echo 'skip';
}
?>
--POST--
storyid=&_type=story&presentation_id=1&fileID=&fileDescription=&thumbX1=-1&thumbX2=-1&thumbY1=-1&thumbY2=-1&title=The+title+goes+here&description=This+is+the+summary+text&full_article=Full+article+goes+here&request_publish_start=2011-01-06&request_publish_end=2011-01-27&website=http%3A%2F%2Fgo.unl.edu%2Fpfy&sponsor=Office+of+University+Communications&newsroom_id%5B%5D=1&submit=Submit
--FILE--
<?php
require dirname(__FILE__) . '/../setup.inc.php';

$controller = new UNL_ENews_Controller(array('model'=>'UNL_ENews_Submission'));

var_dump(headers_list());
?>
--EXPECTF--
array(2) {
  [0]=>
  string(27) "X-Powered-By: PHP/5.3.4-dev"
  [1]=>
  string(%d) "Location: %s?view=thanks&_type=story&id=%d&newsroom=%d"
}