<?php
require_once '../factories/NGOMetadataDBFactory.class.php';
require_once '../metadata/MetadatumVO.class.php';

/**
 * Class: NGOMetadatum
 * @author Steve Cooke <sa_cooke@internode.on.net>
 * This class represents an item of metadata. Used to store the NGOData db info,
 * but may have other uses as well.
 */
class MetadatumDAO  {
	
	// field data - associative array keyed on field name
	private $valueObject = NULL;

	// table name - for automated query preparation
	private $tablename = 'metadata';
	
	// DB factory
	private $dbFactory = NULL;
	
	
	
	public function __construct () {
	}
	
	/**
	 * Getters and setters
	 */
	public function getVariable() {
		return $this->valueObject->getVariable();
	}
	
	public function getValue() {
		return $this->valueObject->getValue();
	}
	
	public function getIsEncrypted() {
		return $this->valueObject->getIsEncrypted();
	}
	
	/**
	 *
	 * @return the query helper object
	 */
	public function getQueryObject() {
		// $data object should always be set, even if empty
		if (isset($this->valueObject)) {
			return $this->valueObject->getQueryObject();
		}
		return false;
	}
		
	
	public function __toString() {
		return '<p>Variable: '.$this->getVariable().' - Value: '.$this->getValue().' - Encrypted: '.$this->getIsEncrypted().'</p>';
	}


	/**
	 * Function: encrypted()
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * @return boolean Returns TRUE if the metadatum value is encrypted; FALSE returned otherwise.
	 */
	public function isEncrypted() {
		if ($this->getIsEncrypted() < 1) return FALSE;
		return TRUE;
	}
	
	
/**
	 * Method created automatically by NGOData system
	 */
	public function setData(MetadatumVO $valueObject) {
		$this->valueObject = $valueObject;
	}

	/**
	 * Method created automatically by NGOData system
	 */
	public function getData() {
		return $this->valueObject;
	}
	
	
/**
	 * Method created automatically by NGOData system
	 */
	public function save() {
		// if it exists in database update, else insert
		if ($this->varExists()) return $this->doUpdate();
		else return $this->doInsert();
	}

	/**
	 * Method: insert()
	 * Method created automatically by NGOData system
	 */
	private function doInsert() {
		//$sql = $this->getSQLFactory()->doInsert($this->valueObject);
		return $this->getDBFactory()->doInsert($this->valueObject);
	}

	/**
	 * Method: doUpdate()
	 * Method created automatically by NGOData system
	 */
	public function doUpdate() {
		// need to set the where clause
		$v = $this->valueObject->getvariable();
		$where[] = array('variable'=>$v);
		$this->valueObject->getQueryObject()->setWhere($where);

		//$sql = $this->getSQLFactory()->prepUpdateStatement($this->valueObject);
		$result = $this->getDBFactory()->doUpdate($this->valueObject);

	}

	/**
	 * Method: doUpdate()
	 * Method created automatically by NGOData system
	 */
	public function doSelect() {
		$v = $this->valueObject->getVariable();
		$where[] = array('variable'=>$v);
		$this->valueObject->getQueryObject()->setWhere($where);
		$result = $this->getDBFactory()->doSelect($this->valueObject);
	}

	/**
	 * Method: doDelete()
	 * May need to change delete...
	 * Method created automatically by NGOData system
	 */
	public function doDelete(&$db) {
		//$sql = "update user set active = 0 where id=".$this->data['id'];
	}
	
	
	private function varExists() {
		if (!$this->doSelect()) return false;
		return true;
	}
	
	
/**
	 * Method: getDBFactory()
	 * Method created automatically by NGOData system
	 */
	private function getDBFactory() {
		if (is_null($this->dbFactory)) {// $dbf;
			$this->setDBFactory(); //= new NGODataDBFactory();
		}
		return $this->dbFactory;
	}

	/**
	 * Method: setDBFactory()
	 * - lazy load the dbFactory
	 * Method created automatically by NGOData system
	 */
	public function setDBFactory() {
		if (is_null($this->dbFactory)) {// $dbf;
			$this->dbFactory = new NGOMetadataDBFactory();
		}
		//return $this->dbFactory;
	}
}
?>