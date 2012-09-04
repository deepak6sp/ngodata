<?php
//require_once '../database/NGODataDB.class.php';
require_once '../database/SQLGenerator.class.php';
require_once '../database/SQLHandler.class.php';
require_once '../database/iSQLFactory.class.php';
require_once '../database/DBConfigurationFactory.class.php';
require_once '../database/DBHandler.class.php';
require_once '../interfaces/iValueObject.class.php';

/**
 * 
 * This factory delegates tasks to the sql generator, passing the sql back for later processing
 * @author Steve Cooke <sa_cooke@me.com>
 * Date: March 2012
 */
class SQLFactory implements iSQLFactory {
	
	protected $dbFactory = null;
	
	public function __construct(iDBFactory $dbf) {
		$this->setDBFactory($dbf);
	}
	
	private function setDBFactory($dbf) {
		$this->dbFactory = $dbf;
		return $this->dbFactory;
	}
	
	private function getDBFactory() {
		return $this->dbFactory;
	}
	
	public function doSelect(iValueObject $vo) {
		$qo = $vo->getQueryObject();
		$statementObject = $this->prepSelectStatement($vo);
		$config = DBConfigurationFactory::getDatabaseConfiguration($qo->getDBName());// $config is MySQLConfiguation object
		
		$dbh = $this->getDBFactory()->createDatabaseHandler($config);
		$data = $dbh->doSelect($statementObject);
		return $data;
	}
	
	
	public function doSave(iValueObject $vo){
		if (is_null($vo->getDataItem('id')) || $vo->getDataItem('id') === '0') {
			// insert the record - it is new
			return $this->doInsert($vo);
		} else {
			// update the record - it is not new
		}
	}
	
	private function doInsert(iValueObject $vo) {
		$qo = $vo->getQueryObject();
		$statementObject = $this->prepInsertStatement($vo);
		
		echo '<pre>The statement object: ';
		print_r($statementObject);
		echo '</pre>';
		
		$config = DBConfigurationFactory::getDatabaseConfiguration($qo->getDBName());// $config is MySQLConfiguation object
		
		$dbh = $this->getDBFactory()->createDatabaseHandler($config);
		//echo '<pre>The database handler pre execute: ';
		//print_r($dbh);
		//echo '</pre>';
		$res = $dbh->doInsert($statementObject);
		//echo '<pre>The database handler post execute: ';
		///print_r($dbh);
		//echo '</pre>';
		return $res;

	}
	
	
	public function doDelete(iValueObject $vo) {
		
	}
	
	public function prepSelectStatement(iValueObject $vo) {
		$gen = new SQLGenerator();
		$stmtObject = $gen->createSelectStatement($vo);
		//$result = $this->db->select($sql);
		//return $result;
		return $stmtObject;
	}
	
	public function prepInsertStatement(iValueObject $vo) {
		$gen = new SQLGenerator();
		$stmtObject = $gen->createInsertStatement($vo);
		
		echo '<pre>The bind in the insert statement object just prepared in SQLFactory:';
		print_r($stmtObject->getBindParams()->getBindResult());
		echo '</pre>';
		//$sql = $gen->createInsertStatement($tableName, $fields, $data);
		//return $this->db->insert($sql);
		return $stmtObject;
	}
	

	public function prepUpdateStatement(iValueObject $data) {
		$gen = new SQLGenerator();
		$stmtObject = $gen->createUpdateStatement($data);
		//$result = $this->db->update($sql);
		//return $result;
		return $stmtObject;
	}
	
	public function prepDeleteStatement(iValueObject $data) {
		$gen = new SQLGenerator();
		$stmtObject = $gen->createDeleteStatement($data);
		//$result = $this->db->update($sql);
		//return $result;
		return $stmtObject;
	}
	
	
	/**
	 * @author Steve Cooke <sa_cooke@me.com>
	 * @param string $sql
	 * @param string $dbname
	 */
	public function runTableSQL($sql, $dbname) {
		$config = DBConfigurationFactory::getDatabaseConfiguration($dbname);// $config is MySQLConfiguation object
		$dbh = $this->getDBFactory()->createDatabaseHandler($config);
		$dbh->runTableSQL($sql);
		//$data = $dbh->doSelect($statementObject);
		//return $data;
	}
}
?>