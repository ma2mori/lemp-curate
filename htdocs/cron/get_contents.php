<?php
require_once(__DIR__.'/../vendor/autoload.php');

use Classes\CommonClass;
use Classes\ModelClass;
use Classes\GetContentsClass;

$c = new CommonClass;
$m = new ModelClass;
$g = new GetContentsClass;

$category_list = CATRGORY_LIST;
$tag_list      = TAG_LIST;
$category_id   = $g->getCategoryId();
$articles      = $g->getArticleList($category_id);
$base_tags     = $tag_list[$category_id];
$topic_id      = $articles['topic_id'];
$contents      = $g->getContentsList($topic_id);

//記事情報登録
$articles_columns = $g->_articles_fillable;
$a_sql = $m->create($articles_columns,'articles');
$m->postParams($a_sql,$articles);

sleep(1);

//コンテンツ登録
$contents_columns = $g->_contents_fillable;
$c_sql = $m->create($contents_columns,'contents');
foreach($contents as $content){
	$m->postParams($c_sql,$content);
}

debug('Success get contents : ' .$topic_id);


//タグ登録
$match_tags = [];
foreach($base_tags as $tag){
	if(str_contains($articles['title'],$tag)){
		$match_tags[] = $tag;
	}
}
if(empty($match_tags)){
	$match_tags[] = $category_list[$category_id];
}

$set_tags_sql = "
	INSERT INTO
		tags
		(name)
		VALUES
		(:name)
";

foreach($match_tags as $tag){
	if(!in_array($tag,$g->getRegisteredTagsName())){
		$stmt = $m->_pdo->prepare($set_tags_sql);
		$stmt->bindValue(":name",$tag);
		$stmt->execute();
	}
}

$registered_tags_id = $g->getRegisteredTagsId($match_tags);

$set_article_to_tag_sql = "
	INSERT INTO
		article_to_tag
		(topic_id,tag_id)
		VALUES
		(:topic_id,:tag_id)
";

foreach($registered_tags_id as $tag){
	$stmt = $m->_pdo->prepare($set_article_to_tag_sql);
	$stmt->bindValue(":topic_id",$topic_id);
	$stmt->bindValue(":tag_id",$tag);
	$stmt->execute();
}

