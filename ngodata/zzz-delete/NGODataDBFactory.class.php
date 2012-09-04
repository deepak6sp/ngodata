<?php
require_once '../exceptions/NGODataDBFactoryException.class.php';
require_once '../database/NGODataDB.class.php';
require_once '../database/NGODataDBType.class.php';
//require_once '../factories/NGOMetadataDBFactory.class.php';

/**
 * Class NGODataDBFactory
 * Determine which database is required, and delegate requests to that database 
 * - pass in a ValueObject to acheive this
 * @author Steve Cooke <sa_cooke@me.com>
 * Date: March 2012
 *
 */
class NGODataDBFactory {
	
	//private $dataAuthFields = array('ngodata_username', 'ngodata_userpass', 'ngodata_dbname', 'ngodata_host');
	//private $userAuthFields = array('ngouser_username', 'ngouser_userpass', 'ngouser_dbname', 'ngouser_host');
	
	
	// create new objects! DO NOT USE constants!
	//CONST DATA = 'ngodata';
	//CONST USER = 'ngouser';
	//CONST GUEST = 'ngoguest';
	
	private $metadataFactory;
	
	private $db = null;
	
	
	/**
	 * Inject the metadata factory. This will have the required authorisation structures.
	 * @param unknown_type $mf
	 */
	public function setMetadataFactory($mf) {
		$this->metadataFactory = $mf;
	}
	
	
	/**
	 * Method: doSelect(NGOValueObjectInterface $vo)
	 * Prepares the correct database for the select operation
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: May 2012
	 * @param NGOValueObjectInterface $vo
	 */
	public function doSelect($dbname, $sql) {
		$dbt = $this->determineDBType($dbname);
		$db = $this->getDB($dbt);
		return $db->select($sql);
	}
	
	
	/**
	 * Method: doInsert(NGOValueObjectInterface $vo)
	 * Prepares the correct database for the insert operation
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: May 2012
	 * @param NGOValueObjectInterface $vo
	 */
	public function doInsert($dbname, $sql) {
		$dbt = $this->determineDBType($dbname);
		$this->db = $this->getDB($dbt);
		return $this->db->insert($sql);
	}
	
	
	/**
	 * Method: doUpdate(NGOValueObjectInterface $vo)
	 * Prepares the correct database for the update operation
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: May 2012
	 * @param NGOValueObjectInterface $vo
	 */
	public function doUpdate(iValueObject $vo) {
		//echo 'Now in NGODataDBFactory->doUpdate()...';
		
		$dbt = $this->determineDBType($vo);
		
		
		// need to get the sql factory
		$sqlf = new NGODataSQLFactory();
		$sql = $sqlf->prepUpdateStatement($vo);
		$db = $this->getDB($dbt);
		//$db->setDatabaseName();
		
		//echo '<pre>';
		//print_r($db->update($vo));
		//echo '</pre>';
		
		return $db->update($vo);
		//$db->up
		//return 'In NGODataDBFactory->doUpdate()...'.$sql;
		//return 'In NGODataDBFactory->doUpdate()...';
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param NGOValueObjectInterface $vo
	 */
	public function doDelete(NGOValueObjectInterface $vo) {
		$dbt = $this->determineDBType($vo);
		return 'In NGODataDBFactory->doDelete()...';
	}
	
	
	/**
	 * @author Steve Cooke <sa_cooke@me.com>
	 * @param unknown_type $sql
	 * @param unknown_type $dbname
	 */
	public function runTableSQL($sql, $dbname) {
		$mdf = new NGOMetadataDBFactory();
		$dbt = new NGODataDBType();
		if ($dbname == self::DATA) {
			$dbt->setDBType(self::DATA);
			//$dbt->setFields($mdf->getAuthDetails($dbt));
		} elseif ($dbname == self::USER) {
			$dbt->setDBType(self::USER);
			//$dbt->setFields($this->userAuthFields);
		} else 
			$dbt->setDBType(self::GUEST);
			
		$dbt->setFields($mdf->getAuthFields($dbt));
		$this->db = $this->getDB($dbt);
		return $this->db->runTableSQL($sql);
	}
	
	
	
	private function getDB($dbtype) {
		//echo 'Now in NGODataDBFactory->getDB()...';
		
		// Lazy load the database connection
		//$this->db = null;
		if (is_null($this->db)) {
			// create an instance of the database connection
			$this->db = new NGODataDB();
			// need to set the dbtype so system knows which db to select
			$this->db->setDBType($dbtype);
			$this->db->setMetadataFactory($this->metadataFactory);
			// metadata factory should have been injected...
			//$this->db->setMetadataFactory(new NGOMetadataDBFactory());
		}
		
		//echo '<pre>The database: ';
		//print_r($this->db);
		//echo '</pre>';
		
		// check to see whether a different database is being asked
		if (!$this->db->checkDBTypeEquality($dbtype)) {
			// different database required
			$this->db->setDBType($dbtype);
		}
		//echo '<pre>';
		//print '...about to leave getDB...';
		//echo '</pre>';
		return $this->db;
	}
	
	
	private function determineDBType($dbname) {
		print 'dbname: '.$dbname;
		
		$dbt = new NGODataDBType();
		if ($dbname == self::DATA) {
			$dbt->setDBType(self::DATA);
			//$dbt->setFields($this->metadataFactory->getAuthFields);
		} elseif ($dbname == self::USER) {
			$dbt->setDBType(self::USER);
			//$dbt->setFields($this->metadataFactory->userAuthFields);
		} elseif ($dbname == self::GUEST) {
			$dbt->setDBType(self::GUEST);
			//$dbt->setFields($this->metadataFactory->guestAuthFields);
		}
		$dbt->setFields($this->metadataFactory->getAuthFields($dbname));
		return $dbt; 
	} 
}
?>