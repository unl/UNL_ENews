<?php
/* @var $savvy Savvy */
$savvy->setEscape('htmlspecialchars');

echo '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL;
echo $savvy->render($context->actionable);