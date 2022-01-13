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

<table class="dcf-table dcf-table-bordered" id="gaStats">
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
    <?php
    $title = trim(str_ireplace('| Announce | University of Nebraska–Lincoln', '', $result[1]));
    
    //If title is missing, try to find it (this shouldn't happen, but there was a bug preventing some story titles from being shown)
    if (empty($title)) {
        preg_match('/.*\/([\d]+)$/', $result[0], $matches);
        if (isset($matches[1]) && $story = UNL_ENews_Story::getByID($matches[1])) {
            $title = $story->title;
        }
    }
    
    //If the title is still empty... let the user know we don't know what it is
    if (empty($title)) {
        $title = 'unknown story';
    }
    ?>
    <td><?php echo '<a href="http://'.$result[0].'">'.$title.'</a>' ?></td>
    <td><?php echo $result[2] // pageviews ?></td>
    <td><?php echo $result[3] // visits ?></td>
</tr>
<?php
endforeach
?>
</table>
