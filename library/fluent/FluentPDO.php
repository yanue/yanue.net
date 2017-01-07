<?php
namespace Library\Fluent;

use Library\Fluent\SelectQuery;
use Library\Fluent\InsertQuery;
use Library\Fluent\UpdateQuery;


class FluentPDO {

    /**
     * @var \PDO
     */
	public static $pdo;
	/**
	 * @var FluentStructure
	 */
	public $structure;
	public $primaryKey = 'id';
	public $foreignKey = null;

	/** @var boolean|callback */
	public $debug;
	
	public function __construct() {
	    if(method_exists($this, 'init')) {
	        $this->init();
	    }
	    else {
	        throw new \PDOException("You didn't write an init method to initialize this class", '500', '');
	    }
	    
	    $this->structure = new FluentStructure($this->primaryKey, $this->foreignKey);
	}
	
	/** Create SELECT query from $table
	 * @param string $table  db table name
	 * @param integer $id  return one row by primary key
	 * @return SelectQuery
	 */
	public function from($table, $id = null) {
		$query = new SelectQuery($this, $table);
		if ($id) {
			$tableTable = $query->getFromTable();
			$tableAlias = $query->getFromAlias();
			$primary = $this->structure->getPrimaryKey($tableTable);
			$query = $query->where("$tableAlias.$primary = ?", $id);
		}
		return $query;
	}

	/** Create INSERT INTO query
	 *
	 * @param string $table
	 * @param array $values  you can add one or multi rows array @see docs
	 * @return InsertQuery
	 */
	public function insertInto($table, $values = array()) {
		$query = new InsertQuery($this, $table, $values);
		return $query;
	}

	/** Create UPDATE query
	 *
	 * @param string $table
	 * @param array|string $set
	 * @param string $where
	 * @param string $whereParams one or more params for where
	 *
	 * @return UpdateQuery
	 */
	public function update($table, $set = array(), $where = '', $whereParams = '') {
		$query = new UpdateQuery($this, $table, $set, $where);
		$query->set($set);
		$args = func_get_args();
		if (count($args) > 2) {
			array_shift($args);
			array_shift($args);
			if (is_null($args)) {
				$args = array();
			}
			$query = call_user_func_array(array($query, 'where'), $args);
		}
		return $query;
	}

	/** Create DELETE query
	 *
	 * @param string $tables
	 * @param string $where
	 * @param string $whereParams one or more params for where
	 * @return DeleteQuery
	 */
	public function delete($tables, $where = '', $whereParams = '') {
		$query = new DeleteQuery($this, $tables);
		$args = func_get_args();
		if (count($args) > 1) {
			array_shift($args);
			if (is_null($args)) {
				$args = array();
			}
			$query = call_user_func_array(array($query, 'where'), $args);
		}
		return $query;
	}

	/** Create DELETE FROM query
	 *
	 * @param string $table
	 * @param string $where
	 * @param string $whereParams one or more params for where
	 * @return DeleteQuery
	 */
	public function deleteFrom($table, $where = '', $whereParams = '') {
		$args = func_get_args();
		return call_user_func_array(array($this, 'delete'), $args);
	}

	/** @return \PDO
	 */
	public function getPdo() {
		return self::$pdo;
	}

	/** @return FluentStructure
	 */
	public function getStructure() {
		return $this->structure;
	}
}
