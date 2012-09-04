<?php
require_once '../exceptions/NGODataDBConnectionException.class.php';
require_once '../factories/ExceptionFactory.class.php';
require_once '../factories/NGOMetadataFactory.class.php';
require_once '../database/NGOMetadataDB.class.php';
require_once '../classes/NGOMetadatum.class.php';


class NGODataDBConnection {	
	
	private static $dbconnection;
	private static $instance;
	
	private $dbusername ;
	private $dbuserpass;
	private $dbname;
	private $dbhost;
	
	private $authFields = array('ngodata_username', 'ngodata_userpass', 'ngodata_dbname', 'ngodata_host');
	
	private function __construct() {
		$this->connect();
	}
	
	
	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new NGODataDBConnection();
		}
		return self::$instance;
	}
	
	
	/**
	 * 
	 */
	private function connect() {
	
		if (!isset(self::$dbconnection)) {
			// populate the variables...
			if (!$this->getDatabaseAuthDetails()) {
				self::$instance = false;
				return false;
			}
			
			// all auth details should be there...
			echo 'Auth details - username: '.$this->dbusername.'; host: '.$this->dbhost.'; password: '.$this->dbuserpass.'...';
			
			try {
				// ok - so try connecting to database
				if (!$this->dbconnection = mysql_connect($this->dbhost, $this->dbusername, $this->dbuserpass))
					throw new NGODataDBConnectionException('Could not connect to the database server. Error: '.mysql_error());
				
			} catch (NGODataDBConnectionException $ndbce) {
				$ef = new ExceptionFactory();
				$ef->raiseError($ndbce);
				return false;
			}
			// select the relavent database
			try {
				if (!mysql_select_db($this->dbname, $this->dbconnection)) {
    				throw NGODataDBConnectionException('Error: Cannot select the database - '.mysql_error());
					//echo 'Could not connect to the NGOData database. Error: '.mysql_error($this->dbconnect).'<br />';
    				//exit;
				}
			} catch (NGODataDBConnectionException $ndbce) {
				$ef = new ExceptionFactory();
				$ef->raiseError($ndbce);
				return false;
			}
		}
		echo '...in NGODataDBConnection->connect()...';
		echo '<pre>';
		print_r($this->dbconnection);
		echo '</pre>';
		$this->instance = $this;
		return true;
	} // end connect
	
	/**
	 * 
	 * Gets the database authorisation details from Metadata
	 */
	private function getMetadata() {
		$mf = new NGOMetadataFactory();
		
		$fields = array('variable'=>$this->authFields);
		
		/*
		echo '<pre>';
		print_r($fields);
		echo '</pre>';
		*/
		
		//$metadataObjects = $mf->getNGODataAuthMetadata($fields);
		// get array of metadatum objects
		return $mf->getNGODataAuthMetadata($fields);
		
		/*
		foreach ($metadataObjects as $key=>$value) {
			echo '<pre>';
			print_r($value);
			echo '</pre>';
		}
		*/
		//return $metadataObjects;
	}
	
	
	/**
	 * Method: getDatabaseAuthData()
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * Date: Feb 2011
	 * @return boolean TRUE is returned if the required authorisation variables
	 * are found in the metadata; FALSE is returned otherwise.
	 */
	private function getDatabaseAuthDetails() {
		$metadata = NULL;
		
		// get an array of metadatum objects
		if ($metadataObjects = $this->getMetadata()) {
			//print '<pre>';
			//print '...in NGODataDBConnection->getDatabaseAuthDetails...';
			//print_r($metadataObjects);
			//print '<pre>';
			
			foreach ($metadataObjects as $key=>$value) {
			// all ok - get the authorisation variables
			//for ($i=0; $i<count($metadata); $i++) {
				//switch ($metadata[$i]->getVariable()) {
				echo '...in NGODataDBConnection->getDatabaseAuthDetails...';
				print '<pre>';
				print_r($value);
				print '</pre>';
				
				
				//foreach ($value as $k2=>$v2) {
					switch ($value->getVariable()) {
						case 'ngodata_host':
							$this->dbhost = $value->getValue();
							//$this->dbhost = $metadata[$i]->getValue();
							break;
					
						case 'ngodata_username':
							$this->dbusername = $value->getValue();
							//$this->dbusername = $metadata[$i]->getValue();
							break;
					
						case 'ngodata_userpass':
							$this->dbuserpass = $value->getValue();
							//$this->dbuserpass = $metadata[$i]->getValue();
							break;
						
						case 'ngodata_dbname':
							$this->dbname = $value->getValue();
							//$this->dbname = $metadata[$i]->getValue();
							echo '...in dbname...';
							break;
					}
				//}
			}
			// Check system was able to find auth data
			$check = $this->checkAuthData();
			
			try {
				if (!$check) {
					// error - not all auth data present
					throw new NGODataDBException('Not all authorisation data is available.');
					//parent::userMessage(parent::EMESS, 'The system is currently unable to complete your request. This has been logged, and notification sent to the site administrator. ');
					//parent::logMessage(parent::EMESS, 'NGODataDB', 'getDatabaseAuthDetails()', 'Not all authorisation data is available.');
					//return false;
				//} else {
					//return true;
				}
			} catch (NGODataDBException $nde) {
				return false;
			}
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
	
	
	//Looking for details to get to NGOData database
	/**
	 * ###   DEPRECATED   ###
	 * Method: getMetadata()
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * Date: Feb 2011
	 * @return Array() Returns an array of Metadatum objects if database returns no
	 * errors; FALSE is return otherwise.
	 */
	/*
	private function getMetadata() {
		$this->mddb = NGOMetadataDB::getInstance();
		// check for error
		if (!$this->mddb) {
			// error
			return false;
		}
		// this should be in the factory...
		$sql = 'select variable, value, is_encrypted from metadata 
			where variable in (
				\'ngodata_username\',\'ngodata_userpass\',\'ngodata_host\',\'ngodata_dbname\'
			)';
		$data = $this->mddb->select($sql);
		if (!$data) {
			// error
			return false;
		}
		$dataArray = Array();
		//$metaDataum = new NGOMetadatum();
		// there will be a pair - variable and value
		foreach ($data as $item) {
			$metaDataum = new NGOMetadatum();
			$dataArray[] = $item;
		}
		$this->metadata = $dataArray;
		return true;
	}
	*/
}
?>