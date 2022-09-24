<?php
require_once('_header.php');

$now_page        = isset($_GET['page']) ? $_GET['page'] : 1;
$category_id     = isset($_GET['category_id']) ? $_GET['category_id'] : '';
$tag_id          = isset($_GET['tag_id']) ? $_GET['tag_id'] : '';
$word            = isset($_GET['word']) ? $_GET['word'] : '';
$limit           = 20;
$current_min_num = ($now_page - 1)*$limit;
$articles        = $f->getIndexList($current_min_num);
$last_page       = ceil($articles['item_num']/$limit);

if(!$category_id && $tag_id){
	foreach($tag_list as $key => $tag_names){
		if(in_array($f->getTagName($tag_id),$tag_names)){
			$category_id = $key;
		}
	}
}

$page_title = SITENAME;

include($temp_file);