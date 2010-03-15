<?php
    $newsletter_id = $parent->context->id;

	echo $context->title . "\n";

	echo $context->description . "\n\n";

	echo $context->website . "\n";
	
	echo "Full Story: " . UNL_ENews_Controller::$url . "?view=story&id=" . $context->id . "\n";
	
	echo "-------------------------------------------------\n\n";
?>