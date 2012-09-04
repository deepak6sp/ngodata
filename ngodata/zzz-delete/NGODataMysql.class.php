<?php
require_once '../exceptions/NGODataMysqlException.class.php';
require_once '../factories/ExceptionFactory.class.php';
// should be singleton
class NGODataMysql {
	
	// model: 
	// this class interegates the metadata database for connection details to NGOData
	// once details are available attempt a connection
	// Sort of a factory...
	
	private $connectionId;
	private $host;
	private $user;
	private $password;
	private $database;
	
	private $result;
	
	private static $connection = FALSE;
	
	private function __construct() {
		$connection = $this->connect();
	}
	
	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new NGODataMysql();
		}
		return self::$instance;
	}
	
	/**
	 * 
	 */
	private function connect() {
		if (!$this->dbconnect) {
			// populate the variables...
			$result = NULL;
			$ef = new ExceptionFactory();
			try {
				if (!$result = $this->getMetadata()) {
				//if (!$result) {
					// no data returned error occurred - but chances are it has already been caught...
					if (!$ef->isUserMessageSet()) {
						throw new NGODataMysqlException('The system cannot complete your request at this time. This has been logged and the site sdministrator notified.');
						//$this->userMessage(parent::EMESS, 'The system cannot complete your request at this time. This has been logged and the site sdministrator notified.');
					}
					$this->dbconnect = false;
					self::$instance = false;
					//return false;
				}
				if (!$this->getDatabaseAuthDetails()) {
					// error occurred
					if (!$ef->isUserMessageSet()) {
						throw new NGODataMysqlException('Unable to get the database authorisation details.');
						//$this->userMessage(parent::EMESS, 'The system cannot complete your request at this time. This has been logged and the site sdministrator notified.');
					}
					self::$instance = false;
					//return false;
				}
			} catch (NGODataMysqlException $nme) {
				$ef->raiseError($nme);
				return false;
			}
			
			// ok - so try connecting to database
			try {
				if (!$this->dbconnect = mysql_connect($this->dbhost, $this->dbusername, $this->dbuserpass)) {
					// error - handle better...
					throw new NGODataMysqlException('Unable to connect to the database.');
					//echo 'Could not connect to the database server. Error: '.mysql_error().'<br />';
    				//exit;
				}
			} catch (NGODataMysqlException $nme){
				$ef->raiseError($nme);
				return false;
			}
			// ok - connected!
			
			// select the relavent database
			try {
				if (!mysql_select_db($this->dbname, $this->dbconnect)) {
    				throw new NGODataMysqlException('Unable to select the database');
					// error - handle better...
					//echo 'Could not select the NGOData database. Error: '.mysql_error($this->dbconnect).'<br />';
    				//exit;
				}
			} catch (NGODataMysqlException $nme) {
				$ef->raiseError($nme);
				return false;
			}
			// database selected, so carry on
		}
	} // end connect
	
	
	//Looking for details to get to NGOData database
	/**
	 * Function: getMetadata()
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * Date: Feb 2011
	 * @return Array() Returns an array of Metadatum objects if database returns no
	 * errors; FALSE is return otherwise.
	 */
	private function getMetadata() {
		$this->mddb = NGOMetadataDB::getInstance();
		// check for error
		if (!$this->mddb) {
			// error
			return false;
		}
		
		$sql = 'select variable, value, is_encrypted from metadata';
		$data = $this->mddb->select($sql);
		if (!$data) {
			// error
			return false;
		}
			
		$dataArray = Array();
		// there will be a pair - variable and value
		foreach ($data as $key=>$value) {
			$metaDataum = new NGOMetaDatum();
			foreach ($value as $key2=>$value2) {
				if ($key2 =='variable') $metaDataum->setVariable($value2);
				if ($key2 =='value') $metaDataum->setValue($value2);
			}
			$dataArray[] = $metaDataum;
		}
		$this->metadata = $dataArray;
		return true;
	}
	
	
	/**
	 * Funciton: getDatabaseAuthData()
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * Date: Feb 2011
	 * @return boolean TRUE is returned if the required authorisation variables
	 * are found in the metadata; FALSE is returned otherwise.
	 */
	private function getDatabaseAuthDetails() {
		if ($this->getMetadata()) {
			// all ok - get the authorisation variables
			for ($i=0; $i<count($this->metadata); $i++) {
				switch ($this->metadata[$i]->getVariable()) {
					case self::MDHOST:
						$this->dbhost = $this->metadata[$i]->getValue();
						break;
					
					case self::MDUSERNAME:
						$this->dbusername = $this->metadata[$i]->getValue();
						break;
					
					case self::MDPASS:
						$this->dbuserpass = $this->metadata[$i]->getValue();
						break;
					
					case self::MDNAME:
						$this->dbname = $this->metadata[$i]->getValue();
						break;
				}
			}
			// Check system was able to find auth data
			$check = $this->checkAuthData();
			
			try {
				if (!$check) {
					// error - not all auth data present
					throw new NGODataMysqlException('Not all authorisation data have been found.');
					//parent::userMessage(parent::EMESS, 'The system is currently unable to complete your request. This has been logged, and notification sent to the site administrator. ');
					//parent::logMessage(parent::EMESS, 'NGODataDB', 'getDatabaseAuthDetails()', 'Not all authorisation data is available.');
				}
			} catch (NGODataMysqlException $nme) {
				$ef = new ExceptionFactory();
				$ef->raiseError($nme);
				return false;
			}
			// all ok...
			return true;
 		} else {
			// error - an error message would have already been generated
			return false;
		}
		return false;
	}
	
	
	/**
	 * Function: checkAuthData()
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * Date: Feb 2011
	 * @return boolean Returns TRUE if all authorisation data is present;
	 * FALSE returned otherwise.
	 */
	private function checkAuthData() {
		if ($this->dbhost == null) {
			return false;
		} else if ($this->dbusername == null) {
			return false;
		} else if ($this->dbuserpass == null) {
			return false;
		} else if ($this->dbname == null) {
			return false;
		}
		return true;
	}
}
?>