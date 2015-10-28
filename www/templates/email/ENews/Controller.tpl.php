<?php if (!$context->options['preview']) : ?><!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $context->subject; ?></title>
    <style type="text/css">
        #outlook a{padding:0;}
        .ReadMsgBody {width: 100%;}
        .ExternalClass {width: 100%;}
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
        body, table, td, a{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;}
        table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;}
        img{-ms-interpolation-mode:bicubic;}
        body{margin:0; padding:0;}
        img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
        table{border-collapse:collapse !important;}
        body{height:100% !important; margin:0; padding:0; width:100% !important;}
        .appleBody a {color:#999999; text-decoration: none;}
        .appleFooter a {color:#ffffff;}
        .appleEvents a {color: #abadb3; font-weight:bold; text-decoration: none;}
        .appleEventsBody a {color: #81261d;; text-decoration: none;}
        blockquote .original-only, .WordSection1 .original-only {display: none !important;}
        blockquote table.forwarded-only, .WordSection1 table.forwarded-only {display: block !important;}
        p {margin-top:0!important;padding-top:0!important;}
        td.responsive-column {display: table-cell;}

        table[class="wrapper-table"] {
            min-width:100% !important;
        }

        @media screen and (max-width: 650px) {
            table[class="wrapper"]{
                width:100% !important;}
            td[class="mobile-hide"]{
                display:none;}
            td[class="mobile-show"]{
                display:block!important;}
            td[class="mobile-logo"] img{
                height:54px!important;
                width:54px!important;}
            td[class="mobile-unl"]{
                height:23px;}
            td[class="mobile-unl"] img{
                height:23px!important;
                width:266px!important;}
            td[class="mobile-header"]{
                vertical-align: top;
                font-size: 23px!important;
                line-height: 1.1!important;}
            td[class="head1"]{
                font-size: 30px!important;
                padding: 0 10px 20px!important;}
            td[class="head2"]{
                font-size: 16px!important;
                padding: 25px 20px 20px 20px!important;}
            td[class="head3"]{
                font-size: 15px!important;
                padding: 0 0 25px 0!important;}
            td[class="head4"]{
                padding: 0 20px 25px 20px !important;}
            table[class="button"]{
                width:85%!important;
                height:auto}
            td[class="button"]{
                font-size: 20px!important;
                padding: 10px!important;}
            img[class="img-full"]{
                width:100% !important;
                max-width: 100%;
                height:auto;}
            table[class="responsive-table"]{
                float: none!important;
                width:100%!important;}
            td[class="padding"]{
                padding: 10px 8% 10px 8% !important;
                text-align: center !important;}
            td[class="section-padding"]{
                padding: 50px 15px 50px 15px !important;}

            td[class="unltoday-padding"]{
                padding: 20px 15px 20px 15px !important;}
            td[class="unltoday-mast"]{
                display:block!important;
                text-align:left!important;}
            td[class="unltoday-head"]{
                font-size:20px!important;
                padding-bottom:14px!important;}
            td[class="unltoday-body"]{
                display:block!important;
                width:100%!important;
                padding:0!important;
                font-size:15px!important;
                line-height:20px!important;}
            td.responsive-column {
                display: block;
            }
            td.separator-column {
                display:none;
            }
        }

        #newsColumnIntro {padding-bottom:10px;margin-bottom:25px;}
    </style>
</head>
<body>
<?php endif; ?>

<?php echo $savvy->render($context); ?>

<?php if (!$context->options['preview']) : ?>
</body>
</html>
<?php endif; ?>
