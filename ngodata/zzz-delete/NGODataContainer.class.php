<?php
require_once '../factories/NGOMetadataFactory.class.php';
class NGODataContainer {
	static protected $shared = array();
	
	private $dataFields = array('ngodata_username', 'ngodata_userpass', 'ngodata_dbname', 'ngodata_host');
	private $userFields = array('ngouser_username', 'ngouser_userpass', 'ngouser_dbname', 'ngouser_host');
	
	CONST DATA = 'data';
	CONST USER = 'user';
	
	public function __construct() {
		// leave empty
	}
	
	public function getNGODataConnection() {
		if (!isset(self::$shared['ngodataDB'])) {
			
			// connect to the database and store in $shared array
			if (!isset(self::$shared['dataDBType'])) {
				$dbType = new NGODataDBType();
				$dbType->setFields($this->dataFields);
				// need to inject this to get rid of dependencies
				//$dbType->setDBType(NGODataDBType::DATA);
				self::$shared['dataDBType'] = $dbType;
			}
			$db = new NGODataDB(self::$shared['dataDBType']);
			$db->setMetadataFactory(new NGOMetadataFactory());
		} else {
			return self::$shared['ngodataDB']; 
		}
	}
	
	// get connection to the user database
	public function getNGOUserConnection() {
		if (!isset(self::$shared['ngouserDB'])) {
			
			// connect and store
			if (!isset(self::$shared['userDBType'])) {
				$dbType = new NGODataDBType();
				$dbType->setFields($this->userFields);
				$dbType->setDBType($NGODataDBType->USER);
				self::$shared['userDBType'] = $dbtype;
			}
			$db = new NGODataDB(self::$shared['dataDBType']);
			$db->setMetadataFactory(new NGOMetadataFactory());
		}
		// if NULL there was an issue creating the connection, and it was reported
		return self::$shared['ngouserDB'];
	}
	
	
	public function saveObject() {
		
	}
	
	public function getSQLGenerator() {
		if (!isset(self::$shared['sqlGenerator'])) {
			self::$shared[] = new NGODataSQLGenerator();
		}
		return self::$shared['sqlGenerator'];
	}
}
?>