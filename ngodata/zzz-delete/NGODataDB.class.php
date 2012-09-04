<?php
require_once '../factories/ExceptionFactory.class.php';
require_once '../exceptions/NGODataDBException.class.php';
require_once '../database/iDBType.class.php';
require_once '../database/NGODataDBType.class.php';
//require_once '../factories/NGOMetadataDBFactory.class.php';
require_once '../database/SQLFactory.class.php';

// #### IMPORTANT ####
// for each change or insert start and end the transaction with mysql BEGIN and COMMIT;

class NGODataDB {
	
	//private $instance = NULL;
	private $dbconnection;
	
	private $dbusername ;
	private $dbuserpass;
	private $dbname = NULL;
	private $dbhost;
	
	private $metadata = null;
	
	private $dbtype;
	private $metadataFactory;
	
	
	/**
	 * Constructor
	 */
	public function __construct() {	
		// don't connect on construction
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @author Steve Cooke <sa_cooke@me.com>
	 * @param NGODataDBTypeInterface $dbType
	 */
	public function setDBType(NGODataDBTypeInterface $dbType) {
		$this->dbtype = $dbType;
	}

	
	public function getDBTypeName() {
		try {
			if (!$this->dbtype->getDBTypeName()) {
				throw new NGODataDBException('Error: the database name is not set!');
			}
		} catch (NGODataDBException $dbe) {
			$ef = new ExceptionFactory();
			$ef->raiseError($dbe);
			return false;
		}
		return $this->dbtype->getDBTypeName();
	}
	
	
	private function setDatabaseName() {
		$this->getDBTypeName();
	}
	
	
	// Call this before trying to query databases
	private function selectDB() {
		//echo '<pre>Database connection: ';
		//print_r($this->dbconnection);
		//echo '</pre>';
		
		try {
			if (!isset($this->dbtype)) {
				throw new NGODataDBException('Error: database type object not set in NGODataDB.');
			}
			
			// ok - select the db...each time
			if (mysql_select_db($this->dbtype->getDBTypeName(), $this->dbconnection) == false) {
				throw new NGODataDBException('Error: Cannot select the database - '.mysql_error($this->dbconnection));
			}
			 
		} catch (NGODataDBConnectionException $ndbce) {
			$ef = new ExceptionFactory();
			$ef->raiseError($ndbce);
			return false;
		}
		return true;
	}
	
		
	/**
	 * Function: select($query)
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: March 2012
	 * Runs a command on the database using the $query string.
	 */
	public function select($sql) {
		// lazy load the db connection
		if (!$this->dbconnection) $this->connect();
		
		// might do this step in the factory...
		if (!$this->selectDB()) return false;
		
		$result = null;
		try {
			if (!$result = mysql_query($sql, $this->dbconnection)) {
				throw new NGODataDBException('Error: Query failed - '.mysql_error($this->dbconnection));
			}
		} catch (NGODataDBException $dbe) {
			$ef = new ExceptionFactory();
			$ef->raiseError($dbe);
			return;
		}
		$data = array();
		while ($row[] = mysql_fetch_assoc($result)) {
			//echo '<pre> In NGODataDB->select...';
			//print_r($row);
			//echo '</pre>';
   			$data[] = $row;
		}
		//echo ' The result set: <pre>';
		//print_r($data);
		//echo '</pre>';
		mysql_free_result($result);
		return $data;
	}
	
	/**
	 * Function: int insert($query)
	 * @author Steve Cooke
	 * Date: Oct 2011
	 * @param string $query the query string.
	 * @return int shows success, or otherwise, of insert query
	 * Inserts a row into the database.
	 */
	public function insert($sql) {
		// lazy load the db connection
		if (!$this->dbconnection) $this->connect();
		
		//$this->setDBType($dbType);
		if (!$this->selectDB()) return false;
		$result = null;
		try {
			if (!$result = mysql_query($sql, $this->dbconnection)) {
				throw new NGODataDBException('Error: Query failed - '.mysql_error($this->dbconnection));
			}
				//return false;
		} catch (NGODataDBException $dbe) {
			$ef = new ExceptionFactory();
			$ef->raiseError($dbe);
			return;
		}
		return $result;
	}
	
	/**
	 * Function: update(string $query)
	 * @author Steve Cooke
	 * @param string $query the query string.
	 * @return int shows success, or otherwise, of update query
	 */
	public function update($valueObject) {
		try {
			if (!$this->dbconnection) $this->connect();
			
			if (!isset($this->dbconnection)) {
				throw new NGODataDBException('Error: database connection failed...');
			}
		} catch (NGODataDBException $dbe) {
			$ef = new ExceptionFactory();
			$ef->raiseError($dbe);
			return false;
		}
		
		//$this->setDBType($dbType);
		print '... database name - '.$this->getDBTypeName();
		
		
		// also need to connect to the correct database!
		// error already caught
		if (!$this->selectDB()) return false;
		/*
		try {
			if (!$this->selectDB()) throw new NGODataDBException('Error: database cannot be selected - trying to connect to (need to determine what)');
		} catch (NGODataDBException $dbe) {
			$ef = new ExceptionFactory();
			$ef->raiseError($dbe);
			return false;
		}
		*/
		
		// all ok so far...
				
		echo '<pre>';
		print_r($valueObject);
		echo '</pre>';
		
		$sqlf = new NGODataSQLFactory();
		$sql = $sqlf->prepUpdateStatement($valueObject);
		
		print $sql;
		
		$result = null;
		try {
			if (!$result = mysql_query($sql, $this->dbconnection)) {
				throw new NGODataDBException('Error: Query failed - '.mysql_error($this->dbconnectio));
			}
				//return false;
		} catch (NGODataDBException $dbe) {
			$ef = new ExceptionFactory();
			$ef->raiseError($dbe);
			return;
		}
		return $result;
		//return mysql_query($query, $this->dbconnect) or die ('Error...: '. mysql_error($this->dbconnect).'<br />'.$query.'<br />');
	}
	
	
	/**
	 * Function: delete($query)
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: Oct 2011
	 * Deletes a row from the database using the $query string.
	 */
	public function delete($query) {
		if (!$this->dbconnection) $this->connect();
		$result = mysql_query($query, $this->dbconnection);
		
		if (!$result) {
			// error - deal with it
			//if (!$this->isUserMessageSet()) {
				//parent::userMessage(parent::EMESS, 'The system cannot complete your request at this time. This has been logged and the site administrator notified.');
				//parent::logMessage(parent::EMESS, 'NGODataDB', 'delete()', 'Delete query failed: '.mysql_error($this->dbconnect).'. Query: '.$query.'.');
			//}
			return false;
		} else {
			return $result;
		}
	}
	
	/**
	 * Function: int getID()
	 * Author: Steve Cooke
	 * Date: 13 Dec 2007
	 * Returns the ID auto-created by the latest insert
	 * NOTE: May create a race condition!!! Beware...
	 */
	public function getId() {
		return $this->result->getInsertId();
		mysql_insert_id($this->dbconnection);
		//return mysql_insert_id($this->dbconnect);
	}
	
	/**
	 * Function: string createTable()
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: March 2012
	 * @return string Returns the SQL required to create a table
	 */
	public function runTableSQL($sql) {
		// error handled earlier
		if (!isset($this->dbconnection)) {
			if (!$this->connect()) return false;
		}
		
		/*
		try {
			if (!$this->dbconnection) $this->connect();
			
			if (!isset($this->dbconnection)) {
				//throw new NGODataDBException('Error: database connection failed...');
			}
		} catch (NGODataDBException $dbe) {
			$ef = new ExceptionFactory();
			$ef->raiseError($dbe);
			return false;
		}
		*/
		
		//$this->setDBType($dbType);
		//print '... database name - '.$this->getDBTypeName();
		
		// also need to connect to the correct database!
		try {
			if (!$this->selectDB()) throw new NGODataDBException('Error: database cannot be selected - trying to connect to (need to determine what)');
		} catch (NGODataDBException $dbe) {
			$ef = new ExceptionFactory();
			$ef->raiseError($dbe);
			return false;
		}
		
		// all ok so far...
		
		
		echo '<pre>In NGODataDB->createTable - the db connection: '."\n";
		echo $sql;
		print_r($this->dbconnection);
		$r = mysql_query($sql, $this->dbconnection);
		if ($r) echo 'query worked...'."\n";
		else echo 'query DID NOT work...'."\n";
		echo '</pre>';
		return $r;
		
		/*
		$result = null;
		
		try {
			$result = mysql_query($sql, $this->dbconnection);
			//if ($result = mysql_query($sql, $this->dbconnection)) {
			if (!$result) {
				throw new NGODataDBException('Error: Query failed - '.mysql_error($this->dbconnection));
			}
		} catch (NGODataDBException $dbe) {
			$ef = new ExceptionFactory();
			$ef->raiseError($dbe);
			return;
		}
		return $result;
		*/
	}
	
	
	/**
	 * Allows the metadata factory to be injected - set by NGODataDBFactory
	 * The metadata factory caches the required database authorisation structures.
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: March/April 2012
	 * @param NGOMetadataFactory $mf
	 */
	public function setMetadataFactory(NGOMetadataDBFactory $mf) {
		$this->metadataFactory = $mf;
	}
	
	private function getMetadataFactory() {
		return $this->metadataFactory;
	}
	
	
	
	//####################//
	//  Connection Stuff  //
	//####################//
	/**
	 * Method : connect(array connection_fields)
	 * In order to connect, this object needs metadata. Inject it, rather than seekng it? Yes.
	 * Inject from from ngodataDBFactory perhaps...
	 */
	private function connect() {
		echo '<pre> in NGODataDB->connect...DBType: ';
		print_r($this->dbtype);
		echo '</pre>';
		
		if (!isset($this->dbconnection)) {
			// populate the variables...
			if (!$this->getDatabaseAuthDetails($this->dbtype)) {
				//self::$instance = false;
				return false;
			}
			
			// all auth details should be there...
			echo 'Auth details - username: '.$this->dbusername.'; host: '.$this->dbhost.'; password: '.$this->dbuserpass.'...';
			
			try {
				// ok - so try connecting to database
				if (!$this->dbconnection = mysql_connect($this->dbhost, $this->dbusername, $this->dbuserpass))
					throw new NGODataDBException('Could not connect to the database server. Error: '.mysql_error());
			} catch (NGODataDBException $ndbce) {
				$ef = new ExceptionFactory();
				$ef->raiseError($ndbce);
				return false;
			}
			
			// select the relavent database
			if ($this->dbname == NULL) {
				$this->dbname = 'ngodata';
			}
			// - leave this until later...
			
			try {
				if (!mysql_select_db($this->dbname, $this->dbconnection)) {
    				throw NGODataDBException('Error: Cannot select the database - '.mysql_error());
					//echo 'Could not connect to the NGOData database. Error: '.mysql_error($this->dbconnect).'<br />';
    				//exit;
				}
			} catch (NGODataDBConnectionException $ndbce) {
				$ef = new ExceptionFactory();
				$ef->raiseError($ndbce);
				return false;
			}
			
		}
		//echo '...in NGODataDB->connect()...';
		//echo '<pre>';
		//print_r($this->dbconnection);
		//echo '</pre>';
		//$this->instance = $this;
		return true;
	} // end connect
	
	
	
	
	
	/**
	 * Method: getDatabaseAuthData()
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * Date: Feb 2011
	 * @return boolean TRUE is returned if the required authorisation variables
	 * are found in the metadata; FALSE is returned otherwise.
	 */
	private function getDatabaseAuthDetails($dbtype) {
		echo '...in NGODataDB->getDatabaseAuthDetails...';
		
		// this should get the authorisation details from the metadata factory
		// it is cached there...
		//we know what database type is required, so get the metadata factory
		// to return the relevant metadata array...
		$metadata = $this->getMetadataFactory()->getMetadata($dbtype);
		
		//if (!isset($this->metadata)) {
		$metadata = $this->getMetadata($dbtype);
		//}
		//echo '<pre> The metadata is: ';
		//print_r($this->metadata);
		//echo '</pre>';
		
		// get an array of metadatum objects
		//if ($metadataObjects = $this->getMetadata()) {
		//foreach ($metadataObjects as $key=>$value) {
		foreach ($metadata as $key=>$value) {
			//echo '...in NGODataDB->getDatabaseAuthDetails...';
			//echo '<pre> The object is: ';
			//print_r($value);
			//echo '</pre>';
			
			echo '<pre> The variable is: ';
			echo $value->getVariable();
			echo '</pre>';
			echo '<pre> and the value is: ';
			echo $value->getValue();
			echo '</pre>';
				
			switch ($value->getVariable()) {
				case 'ngodata_host':
				case 'ngouser_host':
				case 'ngoguest_host':
					$this->dbhost = $value->getValue();
					break;
					
				case 'ngodata_username':
				case 'ngouser_username':
				case 'ngoguest_username':
					$this->dbusername = $value->getValue();
					break;
					
				case 'ngodata_userpass':
				case 'ngouser_userpass':
				case 'ngoguest_userpass':	
					$this->dbuserpass = $value->getValue();
					break;
		
				case 'ngodata_dbname':
				case 'ngouser_dbname':
				case 'ngoguest_dbname':	
					$this->dbname = $value->getValue();
					break;
			}
			//}
			// Check system was able to find auth data
			/*
			try {
				$check = $this->checkAuthData();
				if (!$check) {
					// error - not all auth data present
					throw new NGODataDBException('Not all authorisation data is available.');
				}
			} catch (NGODataDBException $nde) {
				$ef = new ExceptionFactory();
				$ef->raiseError($nde);
				return false;
			}
			*/
		}
		return true;
 		//} else {
			// error - an error message would have already been generated
			//return false;
		//}
		
		//return $this->metadata;
		//return false;
	}
	
	
	
	/**
	 * Method: getMetadata
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Gets the database authorisation details from Metadata
	 */
	private function getMetadata($dbtype) {
		
		//$mf = new NGOMetadataFactory();
		echo 'Now in NGODataDB->getMetadata()...';
		
		//$fields = array('variable'=>$this->dbtype->getFields());
		return $this->getMetadataFactory()->manageGetAuthDetails($dbtype);
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
	
	
	//###########################//
	//####  DBType Equality  ####//
	//###########################//
	
	/**
	 * Method: checkDBTypeEquality($dbytpe)
	 * Enter description here ...
	 * @param unknown_type $dbytpe
	 */
	public function checkDBTypeEquality($dbytpe) {
		return $this->getDBType()->checkEquality($dbytpe);
	}
	
	
	/**
	 * Method: getDBType()
	 * Enter description here ...
	 */
	private function getDBType() {
		return $this->dbtype;
	}
}
?>