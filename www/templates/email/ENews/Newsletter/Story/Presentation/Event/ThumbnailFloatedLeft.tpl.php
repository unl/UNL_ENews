<?php
$summary = $savvy->render($context, 'ENews/Newsletter/Story/Presentation/News/ThumbnailFloatedLeft.tpl.php');

if (strpos($context->website, 'http://events.unl.edu/') === 0) {
    // Ok, this is an event instance URL, add the ICS links to the end of the summary paragraph.
    $summary = str_replace(
                    '</p>',
                    ' <a href="'.$context->website.'?format=ics" class="icsformat">Add to my calendar (.ics)</a></p>',
                    $summary);
}

echo $summary;

?>