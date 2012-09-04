<?php
require_once '../classes/NGODataSecurity.class.php';
require_once '../classes/NGODataDB.class.php';
require_once '../classes/NGOMetadataDB.class.php';

//class CreateDatabase{

/*
$con = NGODataDB::getInstance();
	// Create NGOMetaData database

// select database...
mysql_select_db('ngometadata', $con);


// Create table
$sql = 'create table metadata (
	variable varchar(16) not null,
	value varchar(128) not null)';


mysql_query($sql,$con) or die ('Error: '.mysql_error());
*/

// first need to create NGOData database
function createNGODataDatabase() {
	$con = NGOMetadataDB::getInstance();
	//$con = mysql_connect("localhost","peter","abc123");
	if (!$con) {
  		die('Could not connect: ' . mysql_error());
  	}
  	
  	/*
	# Connect to the local database server as user root
	# You will be prompted for a password.
	mysql -h localhost  -u root -p

	# Now we see the 'mysql>' prompt and we can run
	# the following to create a new database for Paul.
	mysql> create database pauldb;

	# Now we create the user paul and give him full 
	# permissions on the new database
	mysql> grant CREATE,INSERT,DELETE,UPDATE,SELECT on pauldb.* to paul@localhost;

	# Next we set a password for this new use
	mysql> set password for paul = password('mysecretpassword');

	# Cleanup and ext
	mysql> flush privileges;
	*/
	
  	$sql = 'create database ngodatauser';
	if (mysql_query($sql,$con)) {
		echo "Database created";
	} else {
		echo "Error creating database: " . mysql_error();
	}
	
	// now grant permissions
	$sql = 'grant CREATE,INSERT,DELETE,UPDATE,SELECT on ngodatauser.* to ngodata@localhost';
	//mysql_close($con);
}


// create the database
createNGODataDatabase();

?>