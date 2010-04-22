<?php
header('Content-type: '.$context->type);
header('Content-Disposition:filename="'.$context->name.'"');
echo $context->getRaw('data');