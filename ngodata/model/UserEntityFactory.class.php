<?php
require_once '../factories/ExceptionFactory.class.php';
require_once '../exceptions/UserEntityException.class.php';
require_once '../messages/UserEntityMessage.class.php';
require_once '../factories/NGODataSQLFactory.class.php';

/**
 * Join Object Factory: UserEntityFactory
 * This is the factory dealing with join objects between User and Entity
 * @author ngodata <ngodata@me.com>
 * DO NOT ALTER - this class is created automatically by the NGOData system.
 */

class UserEntityFactory {
	// the value object - includes the query object
	private $valueObject = NULL;

	// SQL factory
	private $sqlFactory = NULL;

	// DB factory
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
	public function getEntitysByUserId($userId) {

	}

	/**
	 * Method created automatically by NGOData system
	 * This object may be constructed with no data
	 */
	public function getUsersByEntityId($entityId) {

	}


}
