<?php
require_once '../database/iDBFactory.class.php';
//require_once '../database/SQLHandler.class.php';
require_once '../database/DBHandler.class.php';

/**
 * Class: DBFactory
 * @author Steve Cooke <sa_cooke@me.com>
 * Date: August 2 2012
 * Adapted from:
 * Not really a Factory in this context, as its only returning one db type... So really a helper
 */
class DBFactory implements iDBFactory {
	
	public static function createDatabaseHandler(iDBConfigReader $conf) { // $conf is a MySQLConfiguration object
		try {
			
			return new DBHandler($conf);
		
		} catch (RuntimeException $e) {
			// not sure what will happen here yet...
			//return new MySQL_Handler($conf);
			// handle this error...
			print 'An error occurred getting a DB handle.  Check connection details: '.$e;
		}
	}
}
?>