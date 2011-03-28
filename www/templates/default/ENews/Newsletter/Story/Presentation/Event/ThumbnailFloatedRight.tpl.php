<?php
if (strpos($context->website, 'http://events.unl.edu/') === 0) {
    $tok = '?';
    if (strpos($context->website, '?') !== false) {
        $tok = '&';
    }
    $context->ics = $context->website . $tok . 'format=ics';
}

echo $savvy->render($context, 'ENews/Newsletter/Story/Presentation/News/ThumbnailFloatedRight.tpl.php');
