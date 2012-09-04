<?php
require_once '../db/iDBFactory.int.php';
require_once '../db/MySQLiHandler.class.php';

class DBFactory implements iDBFactory
{
	public static function createDatabaseHandler(iDBConfigReader $conf)
	{
		try
		{
			return new MySQLiHandler($conf);
		}
		catch (RuntimeException $e)
		{
			// not sure what will happen here yet...
			//return new MySQL_Handler($conf);
			print 'An error occurred getting a DB handle.  Check connection details: '.$e;
		}
	}
}
?>