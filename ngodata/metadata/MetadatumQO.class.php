<?php
require_once '../interfaces/NGODataQueryObjectInterface.class.php';

class MetadatumQO implements NGODataQueryObjectInterface {
	
	private $fields = array('variable','value','is_encrypted');

	// table name - for automated query preparation
	private $tablename = 'metadata';

	// where parameters - for automated query preparation
	private $where = array();

	// The database the object data is in
	private $dbname = 'NGOMetaData';

	
	
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
?>