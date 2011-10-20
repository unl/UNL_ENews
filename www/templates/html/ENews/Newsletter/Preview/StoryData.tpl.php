<?php if ($context->getRawObject() instanceof UNL_ENews_Newsletter_Story): ?>
<script type="text/javascript">
WDN.jQuery("#story_<?php echo $context->story_id; ?>").data(<?php echo json_encode(array(
    'id' => $context->story_id,
    'title' => $context->story->title,
    'presentation_id' => $context->getPresentation()->id,
    'default_presentation_id' => $context->story->presentation->id,
    'type' => $context->getPresentation()->type,
    'request_publish_start' => date('M j', strtotime($context->story->request_publish_start)),
    'request_publish_end' => isset($context->story->request_publish_end) ? date('M j', strtotime($context->story->request_publish_end)) : ''
)); ?>);
</script>
<?php elseif ($context->getRawObject() instanceof UNL_ENews_Story): ?>
<script type="text/javascript">
WDN.jQuery("#story_<?php echo $context->id; ?>").data(<?php echo json_encode(array(
    'id' => $context->id,
    'title' => $context->title,
    'presentation_id' => $context->presentation->id,
    'default_presentation_id' => $context->presentation->id,
    'type' => $context->presentation->type,
    'request_publish_start' => date('M j', strtotime($context->request_publish_start)),
    'request_publish_end' => isset($context->request_publish_end) ? date('M j', strtotime($context->request_publish_end)) : ''
)); ?>);
</script>
<?php endif; ?>