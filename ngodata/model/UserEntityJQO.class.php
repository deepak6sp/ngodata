<?php
require_once '../interfaces/NGODataQueryObjectInterface.class.php';

/**
 * Query Object: UserEntityQO
 * This is a helper class - holds field names, tablename, and database name for the entity.
 * This object DOES NOT hold data.
 * @author ngodata <ngodata@me.com>
 * DO NOT ALTER - this class is created automatically by the NGOData system.
 * Access this object ONLY through a factory object.
 */

class UserEntityJQO implements NGODataQueryObjectInterface {
	// legal fields array
	private $fields = array('user_id', 'entity_id');

	// table name - for automated query preparation
	private $tablename = 'user_entity';

	// where parameters - for automated query preparation
	private $where = array();

	// The database the object data is in
	private $dbname = 'ngouser';

	public function __construct() {
	}

	public function getFields() {
		return $this->fields;
	}

	public function getTablename() {
		return $this->tablename;
	}

	public function getDBName() {
		return $this->dbname;
	}

	public function getWhere() {
		return $this->where;
	}

	/**
	 * Method created automatically by NGOData system
	 * @param array $args Holds the parameters for constructing the where clause of the query
	 */
	public function setWhere(array $where) {
		$this->where = $where;
	}
}
