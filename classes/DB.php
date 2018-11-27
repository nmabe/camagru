<?php
/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/5/2018
 * Time: 12:50 AM
 */

class DB
{
	private static $_instance;
	private $_error = false,
			$_pdo,
			$_query,
			$_result,
			$_count;

	private function __construct(){
		try {
			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host'). ';dbname=' . Config::get('mysql/database'),
				Config::get('mysql/username'), Config::get('mysql/password'));
		} catch (PDOException $e) {
            throw new pdoException($e->getMessage(), (int)$e->getCode());
		}
	}

	public static function getInstance(){
		if (!isset(self::$_instance)) {
			self::$_instance = new DB();
		}
		return (self::$_instance);
	}

	public function query($sql, $params = array()){
		$this->_error = false;
		if ($this->_query = $this->_pdo->prepare($sql)) {
			$x = 1;
			if (count($params)) {
				foreach ($params as $param) {
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}
			if ($this->_query->execute()) {
				$this->_result = $this->_query->fetchAll(5);
				$this->_count = $this->_query->rowCount();
			} else {
				foreach ($this->_query->errorInfo() as $err)
					echo $err;
				echo "<br>";
				$this->_error = true;
			}
		}
		return ($this);
	}

	public function action($action, $table, $where = array()){
		if (count($where) == 3) {
			$operators = array('<', '=', '>', '>=', '<=');

			$field = $where[0];
			$operator = $where[1];
			$value = $where[2];

			if (in_array($operator, $operators)) {
				$sql = "{$action} FROM  {$table}  WHERE {$field} {$operator} ?";
				if (!$this->query($sql, array($value))->error()) {
					return ($this);
				}
			}
		}
		return (false);
	}

	public function update($table, $id, $fields = array()){
		$set ='';
		$x = 1;
		foreach ($fields as $name => $value) {
			$set .= $name." = ? ";
			if ($x < count($fields)) {
				$set .= ', ';
				$x++;
			}
		}
		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
		if (!$this->query($sql, $fields)->error()) {
			return (true);
		}
		return (false);
	}

	public function insert($table, $fields = array()){
		if (count($fields)) {
			$keys = array_keys($fields);
			$i = 1;
			$values = '';
			foreach ($fields as $field) {
				$values .= '?';
				if ($i < count($fields)) {
					$values .= ', ';
				}
				$i++;
			}
			$sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) ."`) VALUES ({$values})";
			if (!$this->query($sql, $fields)->error()) {
				return (true);
			}
		}
		return (false);
	}

	public function search($searchTerm)
	{
		$searchResult = $this->get('users', array('username', '=', $searchTerm));
		if($searchResult)
		{
			return($this);
		}
		return (false);
	}

	public function get($table, $where){
		return ($this->action('SELECT *', $table, $where));
	}

	public function delete($table, $where){
		return ($this->action('DELETE', $table, $where));
	}

	public function result(){
		return ($this->_result);
	}

	public function first(){
		return($this->result()[0]);
	}

	public function error(){
		return ($this->_error);
	}

	public function count(){
		return ($this->_count);
	}

}