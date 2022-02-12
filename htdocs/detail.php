<?php
require_once('_header.php');

if(!$f->checkArticleView()){
	$f->addArticleView();
}

$article   = $f->getDetailInfo()[0];
$date      = DateTime::createFromFormat('Y-m-d H:i:s', $article['created_at']);
$contents  = $f->getDetailList();
$relations = $f->getRelationArticleList($article['category_id']);
include($temp_file);