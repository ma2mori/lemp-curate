<?php
require_once(__DIR__ . '/vendor/autoload.php');
$temp_file = __DIR__.'/temp/'.basename($_SERVER['PHP_SELF'],'php').'html';
use Classes\FrontClass;
$f = new FrontClass;
$category_list = CATRGORY_LIST;
$tag_list = TAG_LIST;
