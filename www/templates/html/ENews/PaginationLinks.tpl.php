<?php
    $savvy->loadScriptDeclaration("WDN.initializePlugin('pagination');");
    $cpFudge = $context->limit == 1 ? 2 : 1;
    $currentPage = intval(ceil(($context->offset  - 1) / $context->limit) + $cpFudge);
    $numberOfPages = intval(ceil($context->total / $context->limit));
?>
<nav class="dcf-pagination dcf-txt-center">
    <ol class="dcf-list-bare dcf-list-inline">
    <?php if ($context->offset != 0) :?>
        <li><a class="dcf-pagination-prev" href="<?php echo UNL_ENews_Controller::addURLParams($context->url, array('limit'=>$context->limit, 'offset'=>($context->offset-$context->limit))); ?>">Prev</a></li>
    <?php endif; ?>
    <?php
        $before_ellipsis_shown = false;
        $after_ellipsis_shown = false;
        for ($page = 1; $page <= $numberOfPages; $page++) {
	        $link = UNL_ENews_Controller::addURLParams($context->url, array('limit'=>$context->limit, 'offset'=>($page-1)*$context->limit));
    ?>
    <?php if ($page === $currentPage): ?>
        <li><span class="dcf-pagination-selected"><?php echo $page; ?></span></li>
    <?php elseif ($page <= 3 || $page >= $numberOfPages - 2 || $page == $currentPage - 1 ||
                  $page == $currentPage - 2 || $page == $currentPage + 1 || $page == $currentPage + 2): ?>
        <li><a href="<?php echo $link; ?>"><?php echo $page; ?></a></li>
    <?php elseif ($page < $currentPage && !$before_ellipsis_shown): ?>
        <li><span class="dcf-pagination-ellipsis">&mldr;</span></li>
        <?php $before_ellipsis_shown = true; ?>
    <?php elseif ($page > $currentPage && !$after_ellipsis_shown): ?>
        <li><span class="dcf-pagination-ellipsis">&mldr;</span></li>
        <?php $after_ellipsis_shown = true; ?>
    <?php endif; ?>
    <?php } // end for ?>
    <?php if (($context->offset+$context->limit) < $context->total) :?>
        <li><a class="dcf-pagination-next" href="<?php echo UNL_ENews_Controller::addURLParams($context->url, array('limit'=>$context->limit, 'offset'=>($context->offset+$context->limit))); ?>">Next</a></li>
    <?php endif; ?>
    </ol>
</nav>
