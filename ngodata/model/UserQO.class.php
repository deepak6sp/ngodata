<?php
require_once '../interfaces/iQueryObject.class.php';

/**
 * Query Object: UserQO
 * This is a helper class - holds field names, tablename, and database name for the entity.
 * This object DOES NOT hold data.
 * @author ngodata <ngodata@me.com>
 * DO NOT ALTER - this class is created automatically by the NGOData system.
 * Access this object ONLY through a factory object.
 */

class UserQO implements iQueryObject {
	// legal fields array, and type of each field for binding purposes
	private $fields = array('id'=>'i','username'=>'s','password'=>'s','preferred_name'=>'s','title_id'=>'i','firstname'=>'s','middlename'=>'s','surname'=>'s','user_creation_date'=>'s');

	// legal insert fields array, and type of each field for binding purposes
	private $userDataFields = array('username'=>'s','password'=>'s','preferred_name'=>'s','title_id'=>'i','firstname'=>'s','middlename'=>'s','surname'=>'s','user_creation_date'=>'s');

	// table name - for automated query preparation
	private $tablename = 'user';

	// where parameters - for automated query preparation
	private $where = array();

	// The database the object data is in
	private $dbname = 'ngouser';

	public function __construct() {
	}


	/**
	 * Method created automatically by NGOData system
	 * 
	 */
	public function getFields() {
		return $this->fields;
	}


	/**
	 * Method created automatically by NGOData system
	 * @param array $args Holds the parameters for constructing the where clause of the query
	 */
	public function getUserDataFields() {
		return $this->userDataFields;
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
