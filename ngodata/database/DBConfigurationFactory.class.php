<?php
require_once '../database/MySQLConfiguration.class.php';

/**
 * this class takes requests for a database connection, and 
 * passes the connection back to the calling class - I would
 * envisage the calling class to be a factory of some sort.
 */
class DBConfigurationFactory {
	
	//public function __construct() {
	//}
	
	public function getDatabaseConfiguration($dbname) {
		switch ($dbname) {
			case 'ngouser': 
				return self::getNGOUserConfiguration();
    		case 'ngodata': 
				return self::getNGODataConfiguation();
    		case 'ngoguest': 
				return self::getNGOGuestConfiguation();
			// default returns the error case - need the requesting object to test for this
			default:
				throw new Exception('A non-recognised database name was passed to DBConnectionFactory.');
				break;
		}
	}
	
	private function getNGOUserConfiguration() {
		return new MySQLConfiguration('localhost', 'ngouser', 'tLp_1039', 'ngouser');
	}
	
	private function getNGODataConfiguation() {
		return new MySQLConfiguration('localhost', 'ngodata', 'tLp_1039', 'ngodata');
	}
	
	private function getNGOGuestConfiguation() {
		return new MySQLConfiguration('localhost', 'ngoguest', 'tLp_1040', 'ngoguest');
		//return new MySQLConfiguration('localhost', 'ngoguest', 'n!G@o#D$a%T^a', 'ngoguest');
	}
}
?>