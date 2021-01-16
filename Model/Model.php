<?php 

namespace Model;

use Service\Db;

/**
 * Class Model
 * This common model
 * contains common methods for all models
 */
abstract class Model {
	protected $db;
	protected $table = '';

	public function __construct() {
		$this->db = Db::getInstance();
	}

	//check table (exists?)
    public function checkTable(String $table = '') {
    	if( $table == '' ) {
			$table = $this->table;
		}

    	$sql = 'SELECT name FROM sqlite_master WHERE type=\'table\' AND name=\'' . $table . '\';';
        $this->db->querySql($sql);
        $res = $this->db->getAll();
        
        if(count($res) > 0) {
        	return true;
        }

        return false;
    }

    public function createTable(String $table = '', array $fields) {
    	if( $table == '' ) {
			$table = $this->table;
		}

		if( count($fields) > 0 ) {
			$sql = 'CREATE TABLE IF NOT EXISTS ' . $table . ' (' . implode(', ', $fields) . ')';
			$this->db->execSql($sql);
		}
    }

	//get by id
	public function list(String $sql) {
		$this->db->querySql($sql);
        $res = $this->db->getAll();

        if( count( $res ) < 1 ) {
            return false;
        }

        return $res;
	}

	//get by id
	public function getById($id, String $table = '') {
		if( $table == '' ) {
			$table = $this->table;
		}

		$sql = 'SELECT * FROM `' . $table . '`
            WHERE id = \'' . $id . '\'';

        $this->db->querySql($sql);
        $row = $this->db->getRow();

        if( empty( $row['id'] ) ) {
            return false;
        }

        return $row;
	}

	//save some data only
	public function update(array $arr, $id, String $table = '') {
		if( $table == '' ) {
			$table = $this->table;
		}

		$setFields = '';

		foreach($arr as $fld => $val) {
			if($setFields != '') {
				$setFields .= ', ';
			}
			$setFields .= '`' . $fld . '` = \'' . $val . '\'';
		}

		$sql = 'UPDATE `' . $table . '` 
			SET ' . $setFields . ' 
            WHERE id = \'' . $id . '\'';

        if( $this->db->execSql($sql) ) {
			return true;
		}

		return false;
	}

	//add row in table 
	public function insert(array $arr, String $table = '') {
		if( $table == '' ) {
			$table = $this->table;
		}

		$fields = '';
		$values = '';

		foreach($arr as $fld => $val) {
			if($fields != '') {
				$fields .= ', ';
				$values .= ', ';
			}
			$fields .= '`' . $fld . '`';
			$values .= '\'' . $val . '\'';
		}

		$sql = 'INSERT INTO `' . $table . '` 
			(' . $fields . ') 
            VALUES (' . $values . ')';

        if( $this->db->execSql($sql) ) {
			return $this->db->getLastId();
		}

		return false;
	}

	//check unique field
	public function isUniqueValue(String $field, $value, String $table = '') {
		if( $table == '' ) {
			$table = $this->table;
		}

		$sql = 'SELECT COUNT(*) AS count FROM `' . $table . '` 
			WHERE `' . $field . '` = \'' . $value . '\'';

		$this->db->querySql($sql);
        $row = $this->db->getRow();

        if( $row['count'] > 0 ) {
			return false;
		}

		return true;
	}

	//delete row from table 
	public function delete($id, String $table = '') {
		if( $table == '' ) {
			$table = $this->table;
		}

		$sql = 'DELETE FROM `' . $table . '` 
			WHERE id = \'' . $id . '\'';

        if( $this->db->execSql($sql) ) {
			return true;
		}

		return false;
	}
}
?>