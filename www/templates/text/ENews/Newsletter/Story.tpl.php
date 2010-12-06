<?php
	echo $context->title . "\n";

	echo $context->description . "\n\n";

	echo $context->website . "\n";
	
	echo "Full Story: " . $context->getURL() . "\n";
	
	echo "-------------------------------------------------\n\n";
?>