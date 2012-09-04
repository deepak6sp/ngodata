<?php
require_once '../model/TitleVO.class.php';
require_once '../model/TitleQO.class.php';
require_once '../model/TitleDAO.class.php';
require_once '../model/TitleValidationHandler.class.php';
require_once '../interfaces/iValueObject.class.php';
require_once '../interfaces/iQueryObject.class.php';
require_once '../interfaces/iDataAccessObject.class.php';
require_once '../database/iPostData.class.php';
require_once '../database/SQLFactory.class.php';

/**
 * Factory: Title
 * This is the factory for Title
 * - this creates and sets Title objects.
 * DO NOT forget to inject the dbFactory into the objects.
 * @author ngodata <ngodata@me.com>
 * DO NOT ALTER - this class is created automatically by the NGOData system.
 */

class TitleFactory {
	// SQL factory
	private $sqlFactory = null;

	/**
	 * Method created automatically by NGOData system
	 * This object may be constructed with no data
	 * Do we need to inject the db and sql factories?
	 */
	public function __construct(iSQLFactory $sqlf) {
		//$this->setDBFactory($dbf);
		$this->setSQLFactory($sqlf);
	}

	/**
	 * Method: setSQLFactory()
	 * Injects the sqlFactory attribute.
	 * Method created automatically by NGOData system
	 */
	private function setSQLFactory(iSQLFactory $sqlf) {
		$this->sqlFactory = $sqlf;
	}

	/**
	 * Method: getSQLFactory()
	 * Method created automatically by NGOData system
	 */
	public function getSQLFactory() {
		return $this->sqlFactory;
	}

	/**
	 * Method created automatically by NGOData system
	 */
	public function createTitleById($id) {
		$qo = new TitleQO();
		// set the where
		$item = array('id'=>$id);
		$qo->setWhere($item);
		$vo = new TitleVO();
		$vo->setId($id);
		$vo->setQueryObject($qo);
		$dao = new TitleDAO();
		$dao->setValueObject($vo);
		// No data set apart from id
		return $dao;
	}

	/**
	 * Method created automatically by NGOData system
	 */
	public function createTitle(iPostData $data) {
		echo '<pre>';
		print 'in createTitle: ';
		echo '</pre>';
		echo '<pre>The ssql factory in createTitle: ';
		print_r($this->getSQLFactory());
		echo '</pre>';
		$qo = new TitleQO();
		$vo = new TitleVO();
		$vo->setData($data);
		$vo->setQueryObject($qo);
		$dao = new TitleDAO($this->getSQLFactory());
		$dao->setValueObject($vo);
		return $dao;
	}

	/**
	 * Method created automatically by NGOData system
	 */
	public function getAllTitleData() {
		// select the data from the database
		// prepare the sql statement
		$vo = new TitleVO();
		$qo = new TitleQO();
		$vo->setQueryObject($qo);
		$results = $this->doSelect($vo);
		// iterate through the data to create the value objects and daos
		$daos = array();
		if ($results !== null) { // if data is returned...
			foreach ($results as $key=>$data) {
				$vo = new TitleVO();
				$pd = new PostData($data);
				$vo->setData($pd);
				$qo = new TitleQO();
				$vo->setQueryObject($qo);
				$dao = new TitleDAO($this->getSQLFactory());
				$dao->setValueObject($vo);
				$daos[] = $dao;
			}
		}
		return $daos;
	}

	/**
	 * Method created automatically by NGOData system
	 * @return Returns a Data access object containing the selected data
	 */
	public function getTitleById($id) {
		$vo = new TitleVO();
		$vo->setId($id);
		// also set QO, and set the where to the id
		$qo = new TitleQO();
		// set the where
		$item = array('id'=>$id);
		$qo->setWhere($item);
		$vo->setQueryObject($qo);
		// create the select sql
		$data = $this->getSQLFactory()->doSelect($vo);
		foreach ($data as $item) {
			$vo->setData($item);
		}
		$dao = new TitleDAO();
		$dao->setValueObject($vo);
		return $dao;
	}

	/**
	 * Method created automatically by NGOData system
	 * Validates the data a user may enter.
	 */
	public function validate($validationArray) {
		// I do not need to know whether all fields to be validated are present in the array...
		$rvh = new RegistrationValidationHandler($validationArray);
		$rvh->validate();
		return $rvh->getMessages();

		//title needs validating.
		//required are required tests.
		//description needs validating.
		//required are required tests.
	}


	/**
	 * Method created automatically by NGOData system
	 * Process $_POST array.
	 * @return Returns a properly formatted array of elements for validation.
	 */
	public function processPostArray(array $data) {
		$valArray = null;
		foreach($data as $key=>$item) {
			$valArray[$key] = array('value'=>$item);
		};
		return $valArray;
	}


	/**
	 * Method created automatically by NGOData system
	 * Inserts $obj into persistent storage.
	 * - Assumes $obj DOES NOT exist in persistent storage.
	 */
	private function doInsert(iDataAccessObject $dao) {
		// get dbtype from $obj
		return null;

	}


	/**
	 * Method created automatically by NGOData system
	 * Updates $obj in persistent storage.
	 * - Assumes $obj exists in persistent storage.
	 */
	public function doSelect(iValueObject $obj) {
		// The SQLFactory should handle all the sql stuff, and return the data from the database
		$data = $this->getSQLFactory()->doSelect($obj);
		$objectData = array();
		if (!is_null($data)) {
			foreach ($data as $item) {
				$objectData[] = array($item);
			}
		}

		return $objectData;
	}

	/**
	 * Method created automatically by NGOData system
	 * Updates $obj in persistent storage.
	 * - Assumes $obj exists in persistent storage.
	 */
	private function doUpdate(iDataAccessObject $obj) {
		// get dbtype from $obj
		// need to get the sql factory
		$sqlf = new NGODataSQLFactory();
		$sql = $sqlf->prepUpdateStatement($vo);
		$db = $this->getDB($dbt);
		return $db->update($vo);
	}

	/**
	 * Method created automatically by NGOData system
	 * Updates $obj in persistent storage.
	 * - Assumes $obj exists in persistent storage.
	 */
	public function doDelete(TitleDAO $obj) {
		// do some magic...


	}


}
