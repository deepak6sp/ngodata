<?php
//require_once '../model/NGOMetadatum.class.php';
require_once '../metadata/MetadatumDAO.class.php';
require_once '../metadata/MetadatumVO.class.php';
require_once '../metadata/MetadatumQO.class.php';
require_once '../database/NGODataSQLGenerator.class.php';
require_once '../database/NGOMetadataDB.class.php';

/**
 * 
 * This class caches information the database classes associated with NGOData
 * need to connect with the persistent data store. There will be one array
 * for each database class.
 * The class also stores information related to the NGOData application as a whole.
 * @author Steve Cooke <sa_cooke@me.com>
 * Date: April 2012
 *
 */
class NGOMetadataDBFactory {
	// authorisation metadata array for the ngodata business data
	private $dataMetadata = null;
	
	// authorisation metadata array for the ngodata user data
	private $userMetadata = null;
	
	
	// authorisation metadata array for the ngodata user data
	private $guestMetadata = null;
	
	private $tablename;
	private $db = null;
	
	private $dataAuthFields = array('ngodata_username', 'ngodata_userpass', 'ngodata_dbname', 'ngodata_host');
	private $userAuthFields = array('ngouser_username', 'ngouser_userpass', 'ngouser_dbname', 'ngouser_host');
	private $guestAuthFields = array('ngoguest_username', 'ngoguest_userpass', 'ngoguest_dbname', 'ngoguest_host');
	
	CONST DATA = 'ngodata';
	CONST USER = 'ngouser';
	CONST GUEST = 'ngoguest';
	
	
	
	public function __construct() {
		//$this->tablename = 'metadata';
		if (is_null($this->db)) {
			$this->db = new NGOMetadataDB();
		}
	}
	
	
	public function doSelect(NGOValueObjectInterface $vo) {
		//$dbt = $this->determineDBType($vo);
		return 'In NGODataDBFactory->doSelect()...';
		// set the auth fields array
	}
	
	
	
	public function doInsert(NGOValueObjectInterface $vo) {
		//$dbt = $this->determineDBType($vo);
		
		// this cannot work! - no getDB, and no $dbt...
		//$this->db = $this->getDB($dbt);
		//$this->db = $this->getDB($vo->getQueryObject()->getDBType());
		
		//return 'In NGODataDBFactory->doInsert()...';
		return $this->db->insert($vo);
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param NGOValueObjectInterface $vo
	 */
	public function doUpdate(NGOValueObjectInterface $vo) {
		//echo 'Now in NGODataDBFactory->doUpdate()...';
		
		//$dbt = $this->determineDBType($vo);
		
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
	 * Special method that simply takes a string
	 * @param unknown_type $sql
	 * @param unknown_type $dbname
	 */
	public function runTableSQL($sql, $dbname) {
		$dbt = new NGODataDBType();
		if ($dbname == self::DATA) {
			$dbt->setDBType(self::DATA);
			$dbt->setFields($this->getDataAuthFields());
		} elseif ($dbname == self::USER) {
			$dbt->setDBType(self::USER);
			$dbt->setFields($this->getUserAuthFields());
		}
		$this->db = $this->getDB($dbt);
		return $this->db->runTableSQL($sql);
	}
	
	
	private function getDB($dbtype) {
		echo 'Now in NGOMetadataDBFactory->getDB()...';
		
		// Lazy load the database connection
		//$this->db = null;
		if (is_null($this->db)) {
			// create an instance of the database connection
			if (is_null($this->db)) {
				$this->db = new NGOMetadataDB();
			}
			$this->db->setDBType($dbtype);
			//$this->db->setMetadataFactory(new NGOMetadataDBFactory());
		}
		
		echo '<pre>The database: ';
		print_r($this->db);
		echo '</pre>';
		
		return $this->db;
	}
	
	
	/**
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: April 2012
	 * @return Returns the relevant database authorisation credential fields
	 * @param NGODataDBTypeInterface $dbtype
	 */
	public function getAuthFields($dbtypename) {
		if ($dbtypename == self::DATA) return $this->getDataAuthFields();
		elseif ($dbtypename == self::GUEST) return $this->getGuestAuthFields();
		else return $this->getUserAuthFields();
	}
	
	
	private function getUserAuthFields() {
		return $this->userAuthFields;
	}
	
	private function getDataAuthFields() {
		return $this->dataAuthFields;
	}
	
	private function getGuestAuthFields() {
		return $this->guestAuthFields;
	}
	
	

	/**
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Manages the retrieval of database authorisation details
	 * @param unknown_type $fields
	 * @param unknown_type $dbtype
	 */
	public function manageGetAuthDetails(NGODataDBTypeInterface $dbtype) {
		// get the database stuff
		//$dbn = $vo->getQueryObject()->getDBName();
		
		if ($dbtype->getDBTypeName() == self::DATA) 
			return $this->getDataMetadata();
		elseif ($dbtype->getDBTypeName() == self::GUEST)
			return $this->getGuestMetadata();
		else 
			return $this->getUserMetadata();
	}
	
	
	public function truncateMetadatumTable() {
		$mQO = new MetadatumQO();
		$mVO = new MetadatumVO();
		$mVO->setQueryObject($mQO);
		
		$sqlGen = new NGODataSQLGenerator();
		$sql = $sqlGen->createSelectStatement($mVO);
		$data = $this->db->truncate($sql);
	}
	
	
	
	/**
	 * Method: getUserMetadata()
	 * @author Steve Cooke <sa_cooke@me.com>
	 * @return array of Metadatum objects holding database credentials
	 * Lazy loads and caches database credentials when required.
	 * Should get credentials from a file
	 */
	public function getUserMetadata() {
		if (is_null($this->userMetadata)) {
		
			$data[] = array(
				'variable' => 'ngouser_username',
				'value' => 'ngodata',
				'isEncrypted' => '0'
			);
			$nameQO = new MetadatumQO();
			$nameDAO = new MetadatumDAO();
			$nameVO = new MetadatumVO();
			$nameVO->setData($data);
			$nameVO->setQueryObject($nameQO);
			$nameDAO->setData($nameVO);
			$this->userMetadata[] = $nameDAO;
		
			/*
			if ($nameDAO->save()) {
				echo '<pre>';
				print 'User name success...';
				echo '<pre>';
			} else {
				echo '<pre>';
				print 'User name fail...';
				echo '<pre>';
			}
			*/

			$data[] = array(
				'variable' => 'ngouser_userpass',
				'value' => 'tLp_1039',
				'isEncrypted' => '0'
			);
			$passQO = new MetadatumQO();
			$passDAO = new MetadatumDAO();
			$passVO = new MetadatumVO();
			$passVO->setData($data);
			$passVO->setQueryObject($passQO);
			$passDAO->setData($passVO);
			$this->userMetadata[] = $passDAO;
		
			/*
			if ($passDAO->save()) {
				echo '<pre>';
				print 'User pass success...';
				echo '<pre>';
			} else {
				echo '<pre>';
				print 'User pass fail...';
				echo '<pre>';
			}
			*/


			$data[] = array(
				'variable' => 'ngouser_host',
				'value' => 'localhost',
				'isEncrypted' => '0'
			);
			$hostQO = new MetadatumQO();
			$hostDAO = new MetadatumDAO();
			$hostVO = new MetadatumVO();
			$hostVO->setData($data);
			$hostVO->setQueryObject($hostQO);
			$hostDAO->setData($hostVO);
			$this->userMetadata[] = $hostDAO;
		
			/*if ($hostDAO->save()) {
				echo '<pre>';
				print 'User host success...';
				echo '<pre>';
			} else {
				echo '<pre>';
				print 'User host fail...';
				echo '<pre>';
			}
			*/

			$data[] = array(
				'variable' => 'ngouser_dbname',
				'value' => 'ngouser',
				'isEncrypted'=>'0');
			$dbnameQO = new MetadatumQO();
			$dbnameDAO = new MetadatumDAO();
			$dbnameVO = new MetadatumVO();
			$dbnameVO->setData($data);
			$dbnameVO->setQueryObject($dbnameQO);
			$dbnameDAO->setData($dbnameVO);
			$this->userMetadata[] = $dbnameDAO;
		
			/*
			if ($dbnameDAO->save()) {
				echo '<pre>';
				print 'User dbname success...';
				echo '<pre>';
			} else {
				echo '<pre>';
				print 'User dbname fail...';
				echo '<pre>';
			}
			*/
		}
		return $this->userMetadata;
	}
	
	
	/**
	 * Method: getDataMetadata()
	 * @author Steve Cooke <sa_cooke@me.com>
	 * @return array of Metadatum objects holding database credentials
	 * Lazy loads and caches database credentials when required.
	 * Should get credentials from a file
	 */
	public function getDataMetadata() {
		if (is_null($this->dataMetadata)) {
		
			$data[] = array(
				'variable' => 'ngodata_username',
				'value' => 'ngodata',
				'isEncrypted' => '0'
			);
			$nameQO = new MetadatumQO();
				$nameDAO = new MetadatumDAO();
			$nameVO = new MetadatumVO();
			$nameVO->setData($data);
			$nameVO->setQueryObject($nameQO);
			$nameDAO->setData($nameVO);
			$this->dataMetadata[] = $nameDAO;
		
			/*
			if ($nameDAO->save()) {
				echo '<pre>';
				print 'User name success...';
				echo '<pre>';
			} else {
				echo '<pre>';
				print 'User name fail...';
				echo '<pre>';
			}
			*/

			$data[] = array(
				'variable' => 'ngodata_userpass',
				'value' => 'tLp_1039',
				'isEncrypted' => '0'
			);
			$passQO = new MetadatumQO();
			$passDAO = new MetadatumDAO();
			$passVO = new MetadatumVO();
			$passVO->setData($data);
			$passVO->setQueryObject($passQO);
			$passDAO->setData($passVO);
			$this->dataMetadata[] = $passDAO;
		
			/*
			if ($passDAO->save()) {
				echo '<pre>';
				print 'User pass success...';
				echo '<pre>';
			} else {
				echo '<pre>';
				print 'User pass fail...';
				echo '<pre>';
			}
			*/


			$data[] = array(
				'variable' => 'ngodata_host',
				'value' => 'localhost',
				'isEncrypted' => '0'
			);
			$hostQO = new MetadatumQO();
			$hostDAO = new MetadatumDAO();
			$hostVO = new MetadatumVO();
			$hostVO->setData($data);
			$hostVO->setQueryObject($hostQO);
			$hostDAO->setData($hostVO);
			$this->dataMetadata[] = $hostDAO;
		
			/*if ($hostDAO->save()) {
				echo '<pre>';
				print 'User host success...';
				echo '<pre>';
			} else {
				echo '<pre>';
				print 'User host fail...';
				echo '<pre>';
			}
			*/

			$data[] = array(
				'variable' => 'ngodata_dbname',
				'value' => 'ngodata',
				'isEncrypted'=>'0');
			$dbnameQO = new MetadatumQO();
			$dbnameDAO = new MetadatumDAO();
			$dbnameVO = new MetadatumVO();
			$dbnameVO->setData($data);
			$dbnameVO->setQueryObject($dbnameQO);
			$dbnameDAO->setData($dbnameVO);
			$this->dataMetadata[] = $dbnameDAO;
		
			/*
			if ($dbnameDAO->save()) {
				echo '<pre>';
				print 'User dbname success...';
				echo '<pre>';
			} else {
				echo '<pre>';
				print 'User dbname fail...';
				echo '<pre>';
			}
			*/
		}
		return $this->dataMetadata;
	}
	
	
/**
	 * Method: getGuestMetadata()
	 * @author Steve Cooke <sa_cooke@me.com>
	 * @return array of Metadatum objects holding database credentials
	 * Lazy loads and caches database credentials when required.
	 * Should get credentials from a file
	 */
	public function getGuestMetadata() {
		if (is_null($this->userMetadata)) {
		
			$data[] = array(
				'variable' => 'ngoguest_username',
				'value' => 'ngoguest',
				'isEncrypted' => '0'
			);
			$nameQO = new MetadatumQO();
			$nameDAO = new MetadatumDAO();
			$nameVO = new MetadatumVO();
			$nameVO->setData($data);
			$nameVO->setQueryObject($nameQO);
			$nameDAO->setData($nameVO);
			$this->guestMetadata[] = $nameDAO;

			$data[] = array(
				'variable' => 'ngoguest_userpass',
				'value' => 'n!G@o#D$a%T^a',
				'isEncrypted' => '0'
			);
			$passQO = new MetadatumQO();
			$passDAO = new MetadatumDAO();
			$passVO = new MetadatumVO();
			$passVO->setData($data);
			$passVO->setQueryObject($passQO);
			$passDAO->setData($passVO);
			$this->guestMetadata[] = $passDAO;

			$data[] = array(
				'variable' => 'ngoguest_host',
				'value' => 'localhost',
				'isEncrypted' => '0'
			);
			$hostQO = new MetadatumQO();
			$hostDAO = new MetadatumDAO();
			$hostVO = new MetadatumVO();
			$hostVO->setData($data);
			$hostVO->setQueryObject($hostQO);
			$hostDAO->setData($hostVO);
			$this->guestMetadata[] = $hostDAO;
		
			$data[] = array(
				'variable' => 'ngoguest_dbname',
				'value' => 'ngoguest',
				'isEncrypted'=>'0');
			$dbnameQO = new MetadatumQO();
			$dbnameDAO = new MetadatumDAO();
			$dbnameVO = new MetadatumVO();
			$dbnameVO->setData($data);
			$dbnameVO->setQueryObject($dbnameQO);
			$dbnameDAO->setData($dbnameVO);
			$this->guestMetadata[] = $dbnameDAO;
		
		}
		return $this->guestMetadata;
	}
	
	
	/**
	 * Function: getMetaDataByVariable($variable)
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * @param string/array $variable The variable(s) to retrieve. May be an array of a string
	 * @return MetaDatum Returns a MetaDataum object containing the requested metadata, or false is returned if not found.
	 * @desc Attempts to retrieve a variable from the MetaData db. If not found FALSE is returned, else a MetaDatum object is returned containing the value and related information.
	 */
	public function getMetadataByVariable($variable) {
		//echo '...IN getMetadataByVariable...';
		$data[] = array('variable'=>$variable);
		
		// these are place holders
		$mDAO = new MetadatumDAO();
		$mVO = new MetadatumVO();
		$mVO->setData($data);
		
		$mQO = new MetadatumQO();
		$where = array('variable'=>$variable);
		//echo '<pre>From NGOMetadataFactory->getMetadataByVariable $where: ';
		//print_r($where);
		//echo '</pre>';
		
		$mQO->setWhere($where);
		
		$mVO->setQueryObject($mQO);
		$mDAO->setData($mVO);
		
		$sqlGen = new NGODataSQLGenerator();
		
		$sql = $sqlGen->createSelectStatement($mVO);
		$data = $this->db->select($sql);
		if (!$data) return false; // error condition
		
		$mVO->setData($data);
		return $mDAO;
	}
	
	
	
/**
	 * Function: displayMetadata()
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * Date: Feb 2011
	 * @return string Returns an html formatted string representing the Metadatum
	 * objects; FALSE is returned on error.
	 */
	public function displayMetadataInTable() {
		$data = $this->getMetadata();
		
		// error
		if (!$data) return false;
	
		// else...
		$html = '';
		$html .= parent::displayPageStart();
		$html .= parent::displayClear();
		$html .= parent::startIndent();
		
		// columns
		//$html .= parent::displayOneThird('');

		$text = '<h3>Metadata</h3>';
		
		// table
		$headData[] = 'Variable';
		$headData[] = 'Value';
		$text .= parent::displayTableHead('', $headData);
		for ($i=0; $i<count($data); $i++) {
			if ($i % 2 == 0) $row = 'even';
			else $row = 'odd';
			$tableRow[] = $data[$i]->getVariable();
			$tableRow[] = $data[$i]->getValue();
			$text .= parent::displayTableRow('', $tableRow, $row);
			$tableRow = null;
		}
		$html .= $text;
		$html .= parent::closeIndent();
		//$html .= parent::displayTwoThirds($text);
		return $html;
	}
	
	
	/**
	 * Function: getMetadata()
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * Date: Feb 2011
	 * @return Array() Returns an array containing all Metadatum objects; FALSE on error.
	 */
	public function getMetadata($dbtype) {
		//$sqlGen = new NGODataSQLGenerator();
		//$sql = $sqlGen->createSelectStatement($this->tablename, NULL, NULL);
		//print $sql;
		print_r($dbtype);
		
		// get data
		// all ok
		/*
		$dataArray = Array();
		// there will be a pair - variable and value
		foreach ($data as $key=>$value) {
			$metaDataum = new NGOMetaDatum();
			foreach ($value as $key2=>$value2) {
				if ($key2 =='variable') $metaDataum->setVariable($value2);
				if ($key2 =='value') $metaDataum->setValue($value2);
				if ($key2 =='is_encrypted') $metaDataum->setValue($value2);
			}
			$dataArray[] = $metaDataum;
		}
			
		return $dataArray;
		*/
	}
	
	
	// These may be depricated...
	//private function getNGODataAuthMetadata(array $authFields) {
	private function getNGODataAuthMetadata() {
		return $this->ngodataAuth;
	}
	
	//private function getNGOUserAuthMetadata(array $authFields) {
	private function getNGOUserAuthMetadata() {
		return $this->ngouserAuth;
		
	}
	
	//private function getNGOUserAuthMetadata(array $authFields) {
	private function getNGOGuestAuthMetadata() {
		return $this->ngoGuestAuth;
		
	}

}
?>