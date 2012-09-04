<?php
require_once '../factories/ExceptionFactory.class.php';
require_once '../exceptions/UserEntityException.class.php';
require_once '../messages/UserEntityMessage.class.php';
require_once '../factories/NGODataSQLFactory.class.php';

/**
 * Data Access Join Object: UserEntityDAJO
 * This is the join object between User and Entity
 * @author ngodata <ngodata@me.com>
 * DO NOT ALTER - this class is created automatically by the NGOData system.
 * Access this join object ONLY through a factory object.
 */

class UserEntityJDAO {
	// the value object - includes the query object
	private $valueObject = NULL;

	// SQL factory
	private $sqlFactory = NULL;

	// DB factory - inject this, NOT to be created by the object
	private $dbFactory = NULL;


	/**
	 * Method created automatically by NGOData system
	 * This object may be constructed with no data
	 */
	public function __construct() {
	}


	/**
	 * Method created automatically by NGOData system
	 * This object may be constructed with no data
	 */
	public function getUserId() {

	}

	/**
	 * Method created automatically by NGOData system
	 * This object may be constructed with no data
	 */
	public function getEntityId() {

	}

	/**
	 * Method created automatically by NGOData system
	 * Set the database factory by injection.
	 */
	public function setDBFactory($dbFactory) {
		$this->dbFactory = $dbFactory;
	}
	/**
	 * Method created automatically by NGOData system
	 * @return The database factory .
	 */
	public function getDBFactory() {
		return $this->dbFactory;
	}
}
