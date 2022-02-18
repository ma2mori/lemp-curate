<?php

namespace Classes;

use Classes\CommonClass;
use PDO;

class ModelClass extends CommonClass
{
	public function __construct()
	{
		parent::__construct();
		$this->_model_val = $this->_common_val . 'model ';
		$this->_pdo = $this->dbConnect();
	}

	/**
	 * @return object $pdo
	 */
	public function dbConnect()
	{
		$pdo = new PDO('mysql:host=' . $_ENV['HOST_NAME'] . ';dbname=' . $_ENV['DB_NAME'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
		return $pdo;
	}

	/**
	 * @param string $sql
	 * @param array $params
	 * @return object $query
	 */
	public function postParams($sql, $params)
	{
		$query = $this->_pdo->prepare($sql);

		foreach ($params as $key => $val) {
			$query->bindValue($key, $val);
		}

		$query->execute();
		return $query;
	}

	/**
	 * @param array $columns
	 * @param string $table_name
	 * @return string $sql
	 */
	public function create($columns, $table_name)
	{

		$sql = "
		INSERT INTO
			" . $table_name . "
			(
		";

		foreach ($columns as $key => $column) {
			$sql .= !$key ? $column : ',' . $column;
		}

		$sql .= ") VALUES (";

		foreach ($columns as $key => $column) {
			$sql .= !$key ? ':' . $column : ',:' . $column;
		}

		$sql .= ")";

		return $sql;
	}
}
