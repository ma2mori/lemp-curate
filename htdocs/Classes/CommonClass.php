<?php

namespace Classes;

use Dotenv\Dotenv;

class CommonClass
{
	public function __construct()
	{
		Dotenv::createImmutable(__DIR__ . '/../')->load();
		$this->_common_val = 'common ';
		require_once(__DIR__ . '/../_def.php');
	}
}
