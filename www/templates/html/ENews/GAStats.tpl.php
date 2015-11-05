<h3>UNL Announce Story Views</h3>
<?php
$start_date = strtotime($context->options['start_date']);
$end_date = strtotime($context->options['end_date']);
if ($start_date==$end_date){
    echo '<h4>' . date('F d, Y', $start_date) . '</h4>';
} else {
    echo '<h4>' . date('F d, Y', $start_date) . ' - ' . date('F d, Y', $end_date) . '</h4>';
}
?>

<table id="gaStats">
<tr>
    <th>Story</th>
    <th>Pageviews</th>
    <th>Visits</th>
</tr>
<?php

/**
 * @var \Widop\GoogleAnalytics\Response $context->response 
 */

foreach($context->response->getRows() as $result):
?>
<tr>
    <td><?php echo '<a href="http://'.$result[0].'">'.$result[1].'</a>' ?></td>
    <td><?php echo $result[2] // pageviews ?></td>
    <td><?php echo $result[3] // visits ?></td>
</tr>
<?php
endforeach
?>
</table>
