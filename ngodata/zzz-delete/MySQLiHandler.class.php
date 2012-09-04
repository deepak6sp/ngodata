<?php
require_once '../database/iDBHandler.class.php';

/**
 * Class adapted from:
 * 
 */
class MySQLiHandler implements iDBHandler {
	protected $connection = NULL;
	
	public function __construct(iDBConfigReader $conf) {
		$host = $conf->getHost();
		$user = $conf->getUser();
		$pass = $conf->getPassword();
		$db   = $conf->getDatabase();
		//$port = $conf->getPort();
		//$sock = $conf->getSocket();
		
		$cnx  = new MySQLi($host, $user, $pass, $db);
		//$cnx  = new MySQLi($host, $user, $pass, $db, $port, $sock);
		
		// on error...
		if (mysqli_connect_error()) {
			throw new RuntimeException(mysqli_connect_error(), mysqli_connect_errno());
		}
		$this->connection = $cnx;
	}
	
	public function __destruct() {
		$this->connection->close();
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
			while ($stmt->fetch()) {
				printf ('%s'."<br />", $description);
			}

			/* close statement */
			$stmt->close();
		}

		/* close connection */
		//$this->connection->close();
	}
}
?>