<?php
require_once '../database/iDBHandler.class.php';

/**
 * Class: DBHandler
 * @author Steve Cooke <sa_cooke@me.com>
 * Date: August 2012
 * TODO: Make the values safe: $city = $conn->real_escape_string($city);
 */
class DBHandler implements iDBHandler {
	
	protected $dbConnection = null;
	
	public function __construct(iDBConfigReader $config) { // $config is a MySQLConfiguraiton object
		$host = $config->getHost();
		$user = $config->getUser();
		$pass = $config->getPassword();
		$db   = $config->getDatabase();
		
		//echo '<pre>host: '.$host.'; user: '.$user.'; db: '.$db.'</pre>';
		
		$connection  = new MySQLi($host, $user, $pass, $db);
		//$cnx  = new MySQLi($host, $user, $pass, $db, $port, $sock);
		
		// on error...
		if (mysqli_connect_error()) {
			throw new RuntimeException('DBHandler (__construct) reported an exception: '.mysqli_connect_error(), mysqli_connect_errno());
		}
		// only do this if no error
		$this->setConnection($connection);
	}
	
	
	/**
	 * Method: setConnection
	 */
	private function setConnection($connection) {
		$this->dbConnection = $connection;
	}
	
	
	/**
	 * Method: getConnection
	 */
	public function getConnection() {
		return $this->dbConnection;
	}
	
	
	/**
	 * Method: doSelect
	 */
	public function doSelect(iSQLStatement $sqlOb) {
		//echo '<pre>in DBHandler->doSelect()</pre>';
		if ($stmt = $this->dbConnection->prepare($sqlOb->getSQLStatement())) {
			
			/* execute statement */
			if ($stmt->execute()) {
				$resultArray = $this->fetch($stmt);

				/* close statement */
				$stmt->close();
				return $resultArray;
			}
			return false;
		}
		return false;
	}
	
	
	/**
	 * Method: doInsert
	 * @author Steve Cooke <sa_cooke@me.com>
	 * The solution for binding variables: http://dashasalo.com/2011/03/17/mysqli-bind_param-and-passing-by-reference-issue/
	 */
	public function doInsert(iSQLStatement $sqlOb){
		//echo '<pre>Finally in DBHandler about to execute stmt...</pre>';
		if ($stmt = $this->getConnection()->prepare($sqlOb->getSQLStatement())) {
			/* bind result variables */
			$bindParams = $sqlOb->getBindParams();
			$types = $bindParams->getTypes();
			$values = $bindParams->getValues();
			$res = $this->bindParams(&$stmt, $types, $values);
			
			/* execute statement */
			$res2 = $stmt->execute();
//echo '<pre>Statement errors...'.print_r($stmt).'</pre>';

			if ($stmt->affected_rows <0) {
				// error - log and tell user
				throw new RegistrationException('Error in DBHandler->doInsert: '.$stmt->error);
				/* close statement */
				$stmt->close();
			} else {
				/* fetch values */
				$result = $stmt->affected_rows;
			
				/* close statement */
				$stmt->close();
				return $result;
			}
			/* fetch values */
			//$result = $stmt->affected_rows;
			//echo '<pre>DBHandler errors...'.printf("%s\n", $this->getConnection()->info).'</pre>';
			//$this->getConnection()->info();
			
			/* close statement */
			//$stmt->close();
			//return $result;			
		}
	}
	public function doUpdate(iSQLStatement $sqlOb) {
		
	}
	public function doCreate(iSQLStatement $sqlOb) {
		
	}
	public function doDelete(iSQLStatement $sqlOb) {
	
	}
	
	
	private function bindParams(&$stmt, $types, $valuesArray) {
		if (count($valuesArray) > 0) {
			$params = array_merge(array($types), $valuesArray);
			$tmpArray = array();
			foreach ($params as $i => $value) {
				$tmpArray[$i] = &$params[$i];
			}
			$bindOK = call_user_func_array(array($stmt,'bind_param'), $tmpArray);
		} else {
			$bindOK = true;
		}
		return $bindOK;
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
	
	
	/**
	 * At this stage, ONLY use this to create tables required by the architecture
	 */
	public function runTableSQL($sql) {
		//echo '<pre>in DBHandler->doSelect()</pre>';
		
		//return $this->dbConnection->query($sql);
		
		if ($stmt = $this->dbConnection->prepare($sql)) {
			/* execute statement */
			$stmt->execute();
			/* close statement */
			$stmt->close();
			//return $resultArray;
		}
	}
}
?>