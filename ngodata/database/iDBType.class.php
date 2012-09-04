<?php
interface iDBType {
	//private $dataFields = array('ngodata_username', 'ngodata_userpass', 'ngodata_dbname', 'ngodata_host');
	//private $userFields = array('ngouser_username', 'ngouser_userpass', 'ngouser_dbname', 'ngouser_host');
	const USER = 'ngouser';
	const DATA = 'ngodata';
	
	public function getFields();
	public function getDBTypeName();
}
?>