<?php
require_once '../database/iDBType.class.php';

/**
 * 
 * This class represents the database to connect to, as well as the
 * metadata fields required for the connection authentication.
 * @author Steve Cooke <sa_cooke@me.com>
 *
 */
class NGODataDBType implements iDBType {
	
	// the db to connect to
	private $dbTypeName;
	
	// holds the metadata field names specific to the db type
	private $fields = array();

	public function __construct() {	
	}
	
	public function setFields(array $fields) {
		$this->fields = $fields;
	}
	
	public function setDBType($dbTypeName) {
		$this->dbTypeName = $dbTypeName;
	}
	
	public function getFields() {
		return $this->fields;
	}
	
	public function getDBTypeName() {
		return $this->dbTypeName;
	}
	
	public function checkEquality($dbtype) {
		return $this->getDBTypeName() == $dbtype->getDBTypeName();
	}
}
?>