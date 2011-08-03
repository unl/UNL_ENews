"stories":
<?php
$stories = array($context->getURL() => $context->toExtendedArray());

echo json_encode($stories);