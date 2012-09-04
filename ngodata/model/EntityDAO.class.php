<?php
require_once '../interfaces/iValueObject.class.php';
require_once '../interfaces/iDataAccessObject.class.php';
require_once '../factories/ExceptionFactory.class.php';
require_once '../database/iSQLFactory.class.php';
require_once '../exceptions/EntityException.class.php';
require_once '../messages/EntityMessage.class.php';

/**
 * Data Access Object: EntityDAO
 * This is the Gateway object for Entity. See p.144 PofEAA.
 * @author ngodata <ngodata@me.com>
 * DO NOT ALTER - this class is created automatically by the NGOData system.
 * Access this object ONLY through a factory object.
 */

class EntityDAO implements iDataAccessObject {
	// the value object - includes the query object
	private $valueObject = null;

	// SQL factory
	private $sqlFactory;


	/**
	 * Method created automatically by NGOData system
	 * This object may be constructed with no data
	 */
	public function __construct(iSQLFactory $sqlf) {
		$this->setSQLFactory($sqlf);
	}


	/**
	 * Method created automatically by NGOData system
	 */
	public function setValueObject(iValueObject $valueObject) {
		$this->valueObject = $valueObject;
	}


	/**
	 * Method created automatically by NGOData system
	 */
	public function getValueObject() {
		return $this->valueObject;
	}


	/**
	 * Method created automatically by NGOData system
	 */
	public function saveObject() {
		$data = $this->getSQLFactory()->doSave($this->getValueObject());
	}


	/**
	 * Method: insertObject()
	 * Method created automatically by NGOData system
	 * Perhaps inject the db/sql dependency in the call to the method?
	 */
	public function insertObject() {
		$data = $this->getSQLFactory()->doSave($this->getValueObject());
	}


	/**
	 * Method: updateObject()
	 * Method created automatically by NGOData system
	 */
	public function updateObject() {
		// need to set the where clause
		$id = $this->valueObject->getDataItem('id');
		$where[] = array('id'=>$id);
		$this->valueObject->getQueryObject()->setWhere($where);

		//$sql = $this->getSQLFactory()->prepUpdateStatement($this->valueObject);
		return $this->getDBFactory()->doUpdate($this->valueObject);

	}

	/**
	 * Method: doSelect()
	 * Not sure why there is a select method - should be handled by the factory
	 * Method created automatically by NGOData system
	 */
	/**
	 * Method: deleteObject()
	 * May need to change delete...
	 * Method created automatically by NGOData system
	 */
	public function deleteObject() {
		// To be implemented
	}

	/**
	 * Method: getSQLFactory()
	 * Method created automatically by NGOData system
	 */
	private function getSQLFactory() {
		return $this->sqlFactory;
	}

	/**
	 * Method: setSQLFactory()
	 * Method created automatically by NGOData system
	 * The SQLFactory is to be injected by creation process
	 */
	public function setSQLFactory(iSQLFactory $sqlf) {
		$this->sqlFactory = $sqlf;
	}

}
?>
