<?php
require_once(__DIR__ . '/vendor/autoload.php');

use Classes\FrontClass;
use Classes\recommendClass;

$f = new FrontClass;
$temp_file = __DIR__.'/temp/'.basename($_SERVER['PHP_SELF'],'php').'html';
$category_list = CATRGORY_LIST;
$tag_list = TAG_LIST;

$redis = new redis();
$redis->connect('redis', 6379);
$recommend = new recommendClass($redis);
