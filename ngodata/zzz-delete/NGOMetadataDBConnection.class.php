<?php

require_once 'NGOData.class.php';

/**
 * 
 * Manages requested connections to the database containing NGOData metadata
 * @author Steve Cooke <ngodata@internode.on.net>
 *
 */
class NGOMetadataDBConnection extends NGOData {
	
	
	
	
	// should get these from a file
	const MDHOST = 'localhost'; // this needs to be changed to whatever SID the host provides
	//private $mdhost = '<database>';
	const MDUSERNAME = 'meta';
	const MDPASS = 'n%g#o!_d4t4';
	const MDNAME = 'ngometadata';
	
	private $connection;
	private static $instance;
	
	private function __construct() {
		self::getConnectionDataFromFile();
		self::$conn = mysql_connect(self::MDHOST, self::MDUSERNAME, self::MDPASS, null, null);
		// need to set teh database
		
		// error checking
	}
	
	public function getInstance() {
		if (!self::$instance) {
			$i = new NGOMetadataDBConnection();
			if (!isset($i)) {
				return FALSE;
				
			}
		}
	}
	
	public function getConnection() {
		if (!isset(self::$conn) || !self::$conn) {
			$conn = new NGOMetadataDBConnection();
		}
		if (!$conn) {
			// log error...
			parent::userMessage(parent::EMESS);
				parent::logMessage(parent::EMESS, 'NGOMetadataDBConnection', 'getConnection()', 'Cannot connect to the database.');
			return FALSE;
		}
		
		self::$conn = $conn;
		return $conn;
	}
	
	// Don't want to cache results though, and not in this class...
	public static function FetchResults($name) {

		$results = array();

		if (self::$connection) {
			if (strlen(trim($name)) != 0 && (array_key_exists($name, self::$savedQueries) || array_key_exists($name, self::$savedResults))){
				if (array_key_exists($name, self::$savedQueries)) {
					switch (self::$type) {
						case "mysql":
							$row = 0;
							while ($currentResult = @mysql_fetch_assoc(self::$savedQueries[$name])) {
								$col = 0;
								foreach ($currentResult as $key => $value) {
									$results[$row][$col] = $value;
									$results[$row][$key] = $value;
									$col++;
								}
								$row++;
							}
							break;
							
						case "mysqli":
							$row = 0;
							while ($currentResult = @mysqli_fetch_assoc(self::$savedQueries[$name])) {
								$col = 0;
								foreach ($currentResult as $key => $value) {
									$results[$row][$col] = $value;
									$results[$row][$key] = $value;
									$col++;
								}
								$row++;
							}
							break;
					}
						
					self::$savedResults[$name] = $results;
				}else{
					$results = self::$savedResults[$name];
				}
			} else {
				if (strlen(trim($name)) == 0) {
					Error::LogError("Fetch Results Name Missing", "The name parameter was empty, the name is required so it knows which results to return.");
				} else {
					Error::LogError("Fetch Results Name ('{$name}') Not Found", "The name provided did not have any query results associated with it.");
				}
			}
		}
		return $results;
	}
	
	
	
}
?>

/*
Created By : Mayurika bhatt
Created On : 17th Oct,2007
Name : connection.php
Functionality : Connection and query class.
*/

Class DbConnect
{
var $host = '' ;
var $user = '';
var $password = '';
var $database = '';

var $conn;

var $error_reporting = false;

/*constructor function this will run when we call the class */

function DbConnect ($host1, $user1, $password1, $database1) {


//pass the hostname, user, password, database names here if static
$this->host = $host1;
$this->user = $user1;
$this->password = $password1;
$this->database = $database1;

}

function open(){

/* Connect to the MySQl Server */
	$this->conn = mysql_connect($this->host, $this->user, $this->password);
	if (!$this->conn) {
		return false;
	}
	/* Select the requested DB */

	if (@!mysql_select_db($this->database, $this->conn)) {
		return false;
	}
	return true;
}

/*close the connection */

function close() {
return (@mysql_close($this->conn));
}



}
}
/* Class to perform query*/
class DbQuery extends DbConnect
{
var $result = '';
var $sql;
function DbQuery($sql1)
{
$this->sql = $sql1;
}

function query() {

return $this->result = mysql_query($this->sql);
//return($this->result != false);
}

function affectedrows() {
return(@mysql_affected_rows($this->conn));
}

function numrows() {
return(@mysql_num_rows($this->result));
}
function fetchobject() {
return(@mysql_fetch_object($this->result, MYSQL_ASSOC));
}
function fetcharray() {
return(@mysql_fetch_array($this->result));
}

function fetchassoc() {
return(@mysql_fetch_assoc($this->result));
}

function freeresult() {
return(@mysql_free_result($this->result));
}

}