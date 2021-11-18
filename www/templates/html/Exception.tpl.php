<?php
/* @var $context Exception */
if (false == headers_sent()
    && $code = $context->getCode()) {
    header('HTTP/1.1 '.$code.' '.$context->getMessage());
    header('Status: '.$code.' '.$context->getMessage());
}

UNL_ENews_PostRunFilter::setReplacementData('pagetitle', 'Sorry, an error occurred.');
UNL_ENews_PostRunFilter::setReplacementData('sitetitle', 'Sorry, an error occurred');
$savvy->loadScriptDeclaration("
WDN.initializePlugin('notice');
");
?>
<div class="dcf-notice dcf-notice-warning" hidden>
    <h2>Whoops! Sorry, there was an error:</h2>
    <div><p><?php echo $context->getMessage(); ?></p></div>
</div>
