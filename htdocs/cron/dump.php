<?php
/****
cron実行時にdockerの外に出力するにあたり、
dockerを載せているマシン側にcomposerが無いため.env.phpを使用
※出力がdocker環境内であればautoloadでdotenv使える
****/
require_once(__DIR__.'/.env.php');

/**
	* @todo shファイルに外だし、conf使ってスクリプト内に直接dbpassを書かない、外だしすれば.env.phpも不要になる
	* shell_exec("sh ./hoge.sh");
**/
$command = 'docker exec -it '.SERVICE_NAME.' mysqldump '.DB_NAME.' -u'.DB_USER.' -p'.DB_PASS.' > '.OUTPUT_PATH;
shell_exec($command);