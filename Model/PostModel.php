<?php 

namespace Model;

use Model\Model;

/**
 * Class PostModel
 * This model contains methods for posts
 */
class PostModel extends Model {

	public function __construct() {
		parent::__construct();

		$this->table = 'posts';
	}

	//select rows with order
	public function get(String $orderBy = 'id DESC') {
		$sql = 'SELECT * FROM `' . $this->table . '`
            ORDER BY ' . $orderBy;

        $this->db->querySql($sql);
        return $this->db->getAll();
	}
}
?>