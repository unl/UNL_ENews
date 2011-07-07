<?php
/* @var $context Exception */
if (false == headers_sent()
    && $code = $context->getCode()) {
    header('HTTP/1.1 '.$code.' '.$context->getMessage());
    header('Status: '.$code.' '.$context->getMessage());
}

UNL_ENews_Controller::setReplacementData('pagetitle', 'Uhoh, we got probrem.');
UNL_ENews_Controller::setReplacementData('sitetitle', 'Uhoh, we got probrem');
?>

<script type="text/javascript">
WDN.initializePlugin('notice');
</script>
<div class="wdn_notice alert">
    <div class="close">
        <a href="#" title="Close this notice">Close this notice</a>
    </div>
    <div class="message">
        <h4>Whoops! Sorry, there was an error:</h4>
        <p><?php echo $context->getMessage(); ?></p>
    </div>
    <!-- <?php echo $context; ?> -->
</div>