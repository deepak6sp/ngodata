<?php
require_once '../factories/ExceptionFactory.class.php';
require_once '../factories/NGODataSQLFactory.class.php';
require_once '../exceptions/NGOMetadataDBException.class.php';

// require exceptions here...
class NGOMetadataDB {

	const MDHOST = 'localhost';
	//private $mdhost = '<database>';
	const MDUSERNAME = 'ngodata';
	//const MDUSERNAME = 'meta';
	const MDPASS = 'tLp_1039';
	//const MDPASS = 'n%g#o!_d4t4';
	const MDNAME = 'NGOMetaData';
	
	private $dbconnection;
	private static $instance;
	
	// cache globalSalt here
	private $globalSalt;
	
	
	/*
	 * Constructor
	 */
	public function __construct() {
		$this->connect();
	}
	
	
	/*
	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new NGOMetadataDB();
		}
		// if this is FALSE there was an error - so HANDLE IT!
		return self::$instance;
	}
	*/
	
	
	
	/**
	 * Function: connect()
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * Date: Feb 2011
	 * @return Returns TRUE if connection achieved; FALSE is returned otherwise.
	 */
	private function connect() {
		if (!$this->dbconnection) {
			
			//$con = mysql_connect(self::MDHOST, self::MDUSERNAME, self::MDPASS);
			//if (!$con) {
			try {
				if (!$this->dbconnection = mysql_connect(self::MDHOST, self::MDUSERNAME, self::MDPASS)) {
					throw new NGOMetadataDBException('Error: Cannot connect to the metadata database - '.mysql_error());
				}
			} catch (NGOMetadataDBException $nmdbe) {
				$ef = new ExceptionFactory();
				$ef->raiseError($nmdbe);
				return false;
			}
			
			// ok so far - select the database
			try {
				if (!mysql_select_db(self::MDNAME, $this->dbconnection)) {
					throw new NGOMetadataDBException('Error: cannot select metadata database - '.mysql_error());
				}
			} catch (NGOMetadataDBException $nmdbe) {
				$ef = new ExceptionFactory();
				$ef->raiseError($nmdbe);
				return false;
			}
			//$this->dbconnect = $con;
			return true;
		}
		// need to return something - decide how to handle errors!
		//parent::userMessage(parent::EMESS, 'The system is currently off-line. This has been logged, and notification sent to the site administrator. ');
		//parent::logMessage(parent::EMESS, 'NGOMetadataDB', 'connect()', 'The system encountered an unexpected error.');
		//self::$instance = false;
		
		// also need to get and cache global salt so always available.
		
		return false;
			
	}
	
	
	/**
	 * Function: array[] select()
	 * Author: Steve Cooke
	 * Date: 13 Dec 2007
	 * Returns an associative array, each cell of which represents a returned row
	 * On error or warning returns false.
	 */
	public function select($query) {
		if (!$this->dbconnection) $this->connect();
		
		$resultSet = mysql_query($query, $this->dbconnection);
		
		// check for sql errors
		$error = mysql_error($this->dbconnection);
		
		//print $error;
		if ($error) {
			//log error and tell user
			$message = "Error: ". $error.". The query: ".$query."\r\n";
			//parent::logMessage(parent::EMESS, 'NGOMetadataDB', 'select()', $message);
			//parent::userMessage(parent::EMESS); //, 'An internal error occurred. This has been logged, and notification sent to the site administrator for immediate attention.');
			//parent::userMessage(parent::EMESS, 'An internal error occurred. This has been logged, and notification sent to the site administrator for immediate attention. Please try again shortly.');
			
			return false;
		} else {
			// no error, so do something with the result set
			if (!$resultSet) {
				// report error - I don't think we will ever end up here...
				$message = 'Error: sql returned no error, but there is no result set. Query: '.$query."\r\n";
				//parent::logMessage(parent::EMESS,'NGOMetadataDB','select()',$message);
				//parent::userMessage(parent::EMESS); //, 'An internal error occurred. This has been logged, and notification sent to the site administrator for immediate attention.');
				
				return false;
			} else {
				//all ok...
				$rows = mysql_num_rows($resultSet);
				if ($rows == 0) {
					// no rows returned...
					// Log the error and pass error through to context - let that determine how to handle this
					//$message = "Warning - No rows returned by query: ".$query."\r\n";
					//parent::logMessage(parent::WMESS,'NGOMetadataDB','select()',$message);
					//parent::userMessage(parent::WMESS); //, 'Warning: no data was returned. This has been logged for verification.');
					return false;
				} else {
					$resultArray = array();
					while ($row = mysql_fetch_assoc($resultSet)) {
						$resultArray[] = $row;
					}
					/*
					print '...in NGOMetadataDB::select...';
					echo '<pre>';
					print_r($resultArray);
					echo '</pre>';
					*/
					return $resultArray;
				}
			}
			//return false;
		}
		
	}
	
	
	/**
	 * Function: array[] insert()
	 * Author: Steve Cooke
	 * Date: 13 Dec 2007
	 * Returns an associative array, each cell of which represents a returned row
	 * On error or warning returns false.
	 */
	
	public function insert($valueObject) {
		// lazy load the db connection
		//if (!$this->dbconnection) $this->connect();
		
		//$this->setDBType($dbType);
		//print '... database name - '.$this->getDBTypeName();
		
		
		// also need to connect to the correct database!
		// error has already been caught 
		//if (!$this->selectDB()) return false;
		/*
		try {
			if (!$this->selectDB()) throw new NGODataDBException('Error: database cannot be selected - trying to connect to (need to determine what)');
		} catch (NGODataDBException $dbe) {
			$ef = new ExceptionFactory();
			$ef->raiseError($dbe);
			return false;
		}
		*/
		$sqlf = new NGODataSQLFactory();
		$sql = $sqlf->prepInsertStatement($valueObject);
		echo '<pre>';
		print $sql;
		echo '</pre>';
		
		$result = null;
		try {
			if (!$result = mysql_query($sql, $this->dbconnection)) {
		//if (!$result) {
			// error - deal with it
			//if (!$this->isUserMessageSet()) {
				//parent::userMessage(parent::EMESS, 'The system cannot complete your request at this time. This has been logged and the site administrator notified.');
				//parent::logMessage(parent::EMESS, 'NGODataDB', 'update()', 'Update query failed: '.mysql_error($this->dbconnect).'. Query: '.$query.'.');
			//}
				throw new NGOMetadataDBException('Error: Query failed - '.mysql_error($this->dbconnection));
			}
				//return false;
		} catch (NGOMetadataDBException $dbe) {
			$ef = new ExceptionFactory();
			$ef->raiseError($dbe);
			return;
		}
		return $result;
	}
	
	
	
	/**
	 * Function: truncate()
	 * @Author Steve Cooke <sa_cooke@me.com>
	 * Date: April 2012
	 * @return boolean False is returned on error, else true is returned.
	 * Breaks encapsulation rules!
	 */
	
	public function truncate($tablename) {
		//print 'Here...query: '.$query;
		$sql = 'truncate table '.$tablename;
		
		echo '<pre>';
		print $sql;
		echo '</pre>';
		
		
		if (!$this->dbconnection) $this->connect();
		if (!$this->dbconnection) return FALSE;
		
		try {
			$result = mysql_query($sql, $this->dbconnection);
		
			if (!$result) {
				throw new NGOMetadataDBException('Query failed - '.$sql.': '.mysql_error($this->dbconnection));
			}	
		} catch (NGOMetadataDBException $dbe) {
			$ef = new ExceptionFactory();
			$ef->raiseError($dbe);
			return false;
		}
		return true;	
	}
	
	
	
		
	//************************//
	// SESSION handling stuff //
	//************************//
	public function closeSessionHandle() {
		if (!$this->dbconnection) $this->connect();
		return @mysql_close($this->dbconnection);
	}
	
	public function selectSessionRow($id) {
		if (!$this->dbconnection) $this->connect();
		$sql = "SELECT data FROM sessions
			WHERE id = '".mysql_real_escape_string($id)."'";
		
		if ($row = mysql_fetch_assoc(mysql_query($sql))) {
			return $row['data'];
		}
		return "";
	}
	
	public function writeSessionRow($id,$data){
		if (!$this->dbconnection) $this->connect();
		mysql_query("REPLACE INTO sessions SET
			id = '".mysql_real_escape_string($id)."', 
			lastaccess = '".time()."',
			data = '".mysql_real_escape_string($data)."'",$this->dbconnection);

		if(mysql_affected_rows($this->dbconnection)){
			return true;
		}
		return false;
	}
	
	
	//remove session record from the database and return result
	public function destroySession($id) {	
		mysql_query("DELETE FROM sessions 
			WHERE id = '".mysql_real_escape_string($id)."'",$this->dbh);
		if(mysql_affected_rows($this->dbh)){
			return true;
		}else{
			return false;
		}
	}
	
	
	public function gcSession($maxLifeTime) {
		$timeout = time() - $maxLifeTime;
		mysql_query("DELETE FROM sessions 
			WHERE lastaccess < '".$timeout."'",$this->dbconnection);
		return mysql_affected_rows($this->dbconnection);
	}
	
	
	/**
	 * Function: sanitizeString($string)
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: Dec 2011
	 * @param string $string
	 * @return string Returns the sanitized string.
	 */
	public function sanitizeString($string) {
		return mysql_real_escape_string(trim($string));
	}
}
?>