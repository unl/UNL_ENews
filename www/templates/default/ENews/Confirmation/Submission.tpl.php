<?php
if (isset($context->options['id']) && $story = UNL_ENews_Story::getById($context->options['id'])) {
    echo '<div class="grid7">';
    echo '<h3>Saved Story Summary:</h3>';
    echo $savvy->render($story);
    echo '</div>';
    echo '<a href="'.$story->getEditURL().'" title="Edit the story details">Re-edit Story&hellip;</a>';
}
?>