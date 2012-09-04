<?php
require_once '../database/iSQLHandler.class.php';

class SQLHandler implements iSQLHandler {
	protected $connection = NULL;
	
	//public function __construct(iDBHandler $connection) {
	public function __construct(iDBHandler $connection) {
		$this->connection = $connection;
		
	}
	
	public function doCreate(iSQLStatement $sqlObject) {
		return 'In SQLHandler->doCreate()...';
	}

/**
	 * Method: doSelect(NGOValueObjectInterface $vo)
	 * Prepares the correct database for the select operation
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: May 2012
	 * @param NGOValueObjectInterface $vo
	 */
	public function doSelect(iSQLStatement $sqlObject) {
		return 'In SQLHandler->doSelect()...';
		//$dbt = $this->determineDBType($dbname);
		//$db = $this->getDB($dbt);
		//return $db->select($sql);
	}
	
	
	/**
	 * Method: doInsert(NGOValueObjectInterface $vo)
	 * Prepares the correct database for the insert operation
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: May 2012
	 * @param NGOValueObjectInterface $vo
	 */
	public function doInsert(iSQLStatement $sqlObject) {
		return 'In SQLHandler->doInsert()...';
		//$dbt = $this->determineDBType($dbname);
		//$this->db = $this->getDB($dbt);
		//return $this->db->insert($sql);
	}
	
	
	/**
	 * Method: doUpdate(NGOValueObjectInterface $vo)
	 * Prepares the correct database for the update operation
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: May 2012
	 * @param NGOValueObjectInterface $vo
	 */
	public function doUpdate(iSQLStatement $sqlObject) {
		return 'In SQLHandler->doUpdate()...';
		//echo 'Now in NGODataDBFactory->doUpdate()...';
		
		//$dbt = $this->determineDBType($vo);
		
		
		// need to get the sql factory
		//$sqlf = new NGODataSQLFactory();
		//$sql = $sqlf->prepUpdateStatement($vo);
		//$db = $this->getDB($dbt);
		//$db->setDatabaseName();
		
		//echo '<pre>';
		//print_r($db->update($vo));
		//echo '</pre>';
		
		//return $db->update($vo);
		//$db->up
		//return 'In NGODataDBFactory->doUpdate()...'.$sql;
		//return 'In NGODataDBFactory->doUpdate()...';
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param NGOValueObjectInterface $vo
	 */
	public function doDelete(iSQLStatement $sqlObject) {
		//$dbt = $this->determineDBType($vo);
		return 'In SQLHandler->doDelete()...';
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



	
	/**
	 * Method: query
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: August 1 2012
	 * @param $sql string The sql to execute
	 * @param $description string A string holding the table columns 
	 */
	public function query($sql, $description) {
		// make the query using MySQLi
		//return $result = $this->connection->query($sql);
		//return 'The sql: '.$sql;
		
		if ($stmt = $this->connection->prepare($sql)) {
			print_r($stmt);
			
			/* execute statement */
			$stmt->execute();

			/* bind result variables */
			$stmt->bind_result($description);

			/* fetch values */
			$resultArray = $this->fetch($stmt);
			//while ($stmt->fetch()) {
			//	printf ('%s'."<br />", $description);
			//}

			/* close statement */
			$stmt->close();
			return $resultArray;
		}

		/* close connection */
		//$this->connection->close();
	}
	
	
	/**
	 * Adapted from: http://php.net/manual/en/mysqli-stmt.bind-result.php
	 */
	public function fetch($result) {   
		$array = array();
   
		if($result instanceof mysqli_stmt) {
			$result->store_result();

			$variables = array();
			$data = array();
			$meta = $result->result_metadata();
       
			while($field = $meta->fetch_field()) {
				$variables[] = &$data[$field->name]; // pass by reference
			}
			
			call_user_func_array(array($result, 'bind_result'), $variables);
       
			$i=0;
			while($result->fetch()) {
				$array[$i] = array();
				foreach($data as $k=>$v) {
					$array[$i][$k] = $v;
				}
				$i++;   
				// don't know why, but when I tried $array[] = $data, I got the same one result in all rows
			}
		} elseif($result instanceof mysqli_result) {
			while($row = $result->fetch_assoc()) {
				$array[] = $row;
			}
		}
   
		return $array;
	}
}
?>