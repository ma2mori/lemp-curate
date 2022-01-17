<?php

function debug($message)
{
	ini_set('log_errors', 'On');
	ini_set('error_log', __DIR__.'/logs/debug.html');
	error_log("/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/"."<br>");
	error_log($message."<br>");
}

//コンテンツ関連
define('URL_LIST',[
	1 => 'https://girlschannel.net/topics/category/cosme/',
	2 => 'https://girlschannel.net/topics/category/diet/',
	3 => 'https://girlschannel.net/topics/category/family/',
	4 => 'https://girlschannel.net/topics/category/cook/',
	5 => 'https://girlschannel.net/topics/category/fashion/',
	6 => 'https://girlschannel.net/topics/category/love/',
]);

define('CATRGORY_LIST',[
	1 => '美容',
	2 => 'ダイエット',
	3 => '育児',
	4 => '料理',
	5 => 'ファッション',
	6 => '恋愛',
]);

define('TAG_LIST',[
	1 => [
		'コスメ',
		'スキンケア',
		'ネイル',
		'すっぴん',
		'化粧',
		'メイク',
		'美肌',
		'モデル',
		'肌',
		'女子力',
	],
	2 => [
		'太る',
		'カロリー',
		'美人',
		'痩せ',
		'体重',
		'運動',
		'ダイエット',
		'健康',
		'野菜',
		'女子力',
		'サプリ',
		'食べ物',
		'彼氏',
		'結婚',
		'産後',
		'旦那',
		'辛い',	
	],
	3 => [
		'夫',
		'旦那',
		'離婚',
		'主婦',
		'家事',
		'子供',
		'育児',
		'子育て',
		'ママ友',
		'夫婦',
		'結婚',
		'出産',
		'妻',
		'保育園',
		'家族',
		'妊娠',
		'家庭',
		'離婚',
		'浮気',	
	],
	4 => [
		'カフェ',
		'スイーツ',
		'居酒屋',
		'お菓子',
		'ランチ',
		'ご飯',
		'外食',
		'レシピ',
		'お弁当',
		'朝ごはん',
		'レストラン',
		'グルメ',
		'コンビニ',
		'料理',
		'食事',
		'食べ物',
		'肉',
		'丼',	
	],
	5 => [
		'コーデ',
		'帽子',
		'ブランド',
		'ワンピ',
		'アクセ',
		'メガネ',
		'ファッション',
		'コーデ',
		'バッグ',
		'モデル',
		'靴',
		'服',
		'春',
		'夏',
		'秋',
		'冬',
		'GU',	
	],
	6 => [
		'男女',
		'カップル',
		'デート',
		'恋愛',
		'同棲',
		'モテ',
		'恋人',
		'旦那',
		'結婚',
		'妊娠',
		'失恋',
		'彼氏',
		'出会い',
		'恋',
		'独身',
		'婚約',
		'片思い',
		'記念日',	
	],
]);


//メタ情報
define("DESCRIPTION",'レディースまとめは女性向けキュレーションメディアです。仕事、ファッション、美容、食事などに関する情報を発信しています。');
define("SITENAME",'レディースまとめ');