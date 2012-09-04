<?php
require_once '../database/iDBConfigReader.class.php';

/**
 * Class: Registration
 * This is the SQL handler for Registration
 * - this provides access to the dbs, and issues various queries using Registration objects.
 * @author ngodata <ngodata@me.com>
 * DO NOT ALTER - this class is created automatically by the NGOData system.
 */

class RegistrationSQLHandler {
	// The live db connection
	protected $connection = NULL;

	public function __construct(iDBConfigReader $conf) {
		$host = $conf->getHost();
		$user = $conf->getUser();
		$pass = $conf->getPassword();
		$db   = $conf->getDatabase();
		
		$connection = new MySQLi($host, $user, $pass, $db);
		// on error - need to check return value...
		if (mysqli_connect_error()) {
			throw new RuntimeException(mysqli_connect_error(), mysqli_connect_errno());
		}
	}


	public function __destruct() {
		$this->connection->close();
	}


}

