<?php if (!isset($context->options) || !$context->options['preview']) : ?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title><?php echo $context->subject ?></title>

    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat:500,600');
    </style>
    <style type="text/css">
        .ExternalClass {width: 100%;}
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
        body {-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;}
        body {margin:0; padding:0;}
        table td {border-collapse:collapse}

        @media screen {
            td[class="unltoday-head"] {
                font-family:'Montserrat',Verdana,sans-serif!important;
                font-weight: 500;
            }
            td[class="unltoday-mast"] {
                font-family:'Montserrat',Verdana,sans-serif!important;
                font-weight: 500;
            }
            td[class="wdn-logo-lockup"] {
                font-family:'Montserrat',Verdana,sans-serif!important;
                font-weight: 600;
            }
        }

        @media screen and (max-width: 650px) {
            body[yahoo] .wrapper{
                width:100% !important;}
            body[yahoo] .mobile-hide{
                display:none;}
            body[yahoo] .mobile-show{
                display:block!important;}
            body[yahoo] .wdn-header-top img{
                height:30px!important;
                width:auto!important;
            }
            body[yahoo] .wdn-logo{
                width:66px!important;
            }
            body[yahoo] .wdn-logo img{
                height:71px!important;
                width:auto!important;
            }
            body[yahoo] .wdn-logo-lockup img{
                height:71px!important;
                width:auto!important;
            }
            body[yahoo] table.button{
                width:85%!important;
                height:auto}
            body[yahoo] td.button {
                font-size: 20px!important;
                padding: 10px!important;}
            body[yahoo] .img-full{
                width:100% !important;
                max-width: 100%;
                height:auto;}
            body[yahoo] .responsive-table{
                float: none!important;
                width:100%!important;}
            body[yahoo] .padding {
                padding: 10px 8% 10px 8% !important;
                text-align: center !important;}
            body[yahoo] .section-padding{
                padding: 50px 15px 50px 15px !important;}


            body[yahoo] td.wdn-logo-lockup {
                font-size: 22px !important;
                white-space: normal !important;}
            body[yahoo] td[class="unltoday-padding"]{
                padding: 20px 15px 20px 15px !important;}
            body[yahoo] td[class="unltoday-mast"]{
                display:block!important;
                text-align:left!important;}
            body[yahoo] td[class="unltoday-head"]{
                font-size:20px!important;
                padding-bottom:14px!important;}
            body[yahoo] td[class="unltoday-body"]{
                display:block!important;
                width:100%!important;
                padding:0!important;
                font-size:15px!important;
                line-height:20px!important;}
            body[yahoo] td.responsive-column {
                display: block;}
            body[yahoo] td.separator-column {
                display:none;}
        }

        #newsColumnIntro {padding-bottom:10px;margin-bottom:25px;}
        p {margin-top:0!important;padding-top:0!important;}
    </style>
</head>
<body yahoo="fix" style="margin: 0; padding: 0; color:#5b5b5a; font-size:16px; line-height:1.777; font-family:Georgia, serif;" bgcolor="#f6f6f5" link="#d00000">
<?php endif; ?>

<?php echo $savvy->render($context); ?>

<?php if (!isset($context->options) || !$context->options['preview']) : ?>
<!-- optout -->
</body>
</html>
<?php endif; ?>
