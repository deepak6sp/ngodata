<?php
require_once ('../classes/NGOMetadataDB.class.php');

// Uses the NGOMetadata database
class NGODataSession {
    	
	private $id = NULL;
	private $data;
	private $lastaccess;

	// each session needs its own database connection
	private $dbhandle = NULL;

	function __construct(){
		session_set_save_handler(array($this,"open"),
		array($this,"close"),
		array($this,"read"),
		array($this,"write"),
		array($this,"destroy"),
		array($this,"gc"));
		//set the database handle
		$this->dbhandle = NGOMetadataDB::getInstance();
	}

	function open($savePath, $sessName) {
		//connect to the database
		if (!$this->dbhandle)
			$this->dbhandle = NGOMetadataDB::getInstance();
		
			//$dbh = mysql_connect(self::MDHOST ,self::MDUSERNAME ,self::MDPASS);
		//$db = mysql_select_db(self::MDNAME ,$dbh);
		
		if (!$this->dbhandle) return false;
		//if (!$dbh || !$db) return false;
		
		//$this->dbhandle = $dbh;
		return true;
	}

	function close() {
		//$dbh = NGOMetadataDB::getInstance();
		$this->gc(get_cfg_var("session.gc_maxlifetime"));
		
		// needs to 
		return $this->dbh->closeSessionHandle();
		//return @mysql_close($this->dbh);
	}

	function read($id) {
		//fetch the session record
		//$dbh = NGOMetadataDB::getInstance();
		// ensure return AT LEAST an empty string
		return $this->dbh->selectSessionRow($id);
	}

	function write($id,$data){
		//$dbh = 	$dbh = NGOMetadataDB::getInstance();
		return $this->dbh->writeSessionRow($id, $data);
	}

	function destroy($id) {
		//remove session record from the database and return result
		//$dbh = NGOMetadataDB::getInstance();
		return $this->dbh->destroySession($id);
		
		/*
		mysql_query("DELETE FROM sessions 
			WHERE id = '".mysql_real_escape_string($id)."'",$this->dbh);
		if(mysql_affected_rows($this->dbh)){
			return true;
		}else{
			return false;
		}
		*/
	}

	function gc($maxLifeTime){
		//garbage collection
		//$dbh = NGOMetadataDB::getInstance();
		$this->dbh->gcSession($maxLifeTime);
		
		/*
		$timeout = time() - $maxLifeTime;
		mysql_query("DELETE FROM sessions 
			WHERE lastaccess < '".$timeout."'",$this->dbh);
		return mysql_affected_rows($this->dbh);
		*/
	}
	
}
?>