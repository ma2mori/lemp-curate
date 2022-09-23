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
	1 => '',
]);

define('CATRGORY_LIST',[
	1 => '',
]);

define('TAG_LIST',[
	1 => [
		'',
	],
]);


//メタ情報
define("DESCRIPTION",'');
define("SITENAME",'');