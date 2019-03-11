<?php
if (isset($context->options['story_id']) && $story = UNL_ENews_Story::getById($context->options['story_id'])) {
    echo '<div class="dcf-mb-6">';
    echo '<div style="overflow: auto">';
    echo '<h3>Saved Story Summary:</h3>';
    echo $savvy->render($story);
    echo '</div>';
    echo '<a href="'.$story->getEditURL().'" title="Edit the story details">Re-edit Story&hellip;</a>';
    echo '</div>';
}
?>