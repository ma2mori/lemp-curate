<?php
require_once('_header.php');

if(!$f->checkArticleView()){
	$f->addArticleView();
}

$article       = $f->getDetailInfo()[0];
$date          = DateTime::createFromFormat('Y-m-d H:i:s', $article['created_at']);

$contents_key = 'contents'.$article['topic_id'];
if($redis->hGetAll($contents_key)){
	$base_contents = $f->formatCacheToArray($redis->hGetAll($contents_key),$f->_contents_properties);
}else{
	$f->cachingDatas($redis,$f->getDetailList(),$contents_key);
	$base_contents = $f->formatCacheToArray($redis->hGetAll($contents_key),$f->_contents_properties);
}

$contents      = $f->optDetailList($base_contents);
$comments      = $f->getCommentList();
$relations     = $f->getRelationArticleList($article['category_id']);
$all_topic_ids = $f->getAllArticleIds();

if($f->_comment_body){
	$f->storeComment();
	header('Location: '.$_ENV['APP_URL'].'detail.php?id='.$article['topic_id'].'#comment_area');
	exit;
}

$recommend->setRating($f->createId(), $article['topic_id']);
$recommend->calcJaccard($all_topic_ids);
$recommend_topic_ids = $recommend->getItems($article['topic_id']);
$recommend_articles  = $f->getRecommendArticleList($recommend_topic_ids);

include($temp_file);