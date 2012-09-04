<?php
session_start();
//namespace ngodata\classes;
require 'DBIGenerator.class.php';
require_once '../factories/NGODataDBFactory.class.php';
require_once '../database/NGODataDBType.class.php';
//require '../classes/User.class.php';

$_SESSION['user_id'] = 1;

define ('BR', '</ br>');

function startPage() {
	echo '<html><head></head><body>';
}

function createUser() {
	echo 'creating User...'.BR;
	$fields = array('id','firstname','surname','password',);
	$dbi = new DBIGenerator('User', '../model/', $fields, 'user',  DBIGenerator::USER);
	
	
	if ($dbi->createMessage('user')) echo '...creation success'.BR;
	else echo '...creation FAILED!'.BR;
	
	
	
	if ($dbi->createVO()) echo '...creation success'.BR;
	else echo '...creation FAILED!'.BR;
	if ($dbi->createQO()) echo '...creation success'.BR;
	else echo '...creation FAILED!'.BR;
	if ($dbi->createDAO()) echo '...creation success'.BR;
	else echo '...creation FAILED!'.BR;
	if ($dbi->createFactory()) echo '...creation success'.BR;
	else echo '...creation FAILED!'.BR;
	if ($dbi->createGeneralException('User')) echo '...creation success'.BR;
	else echo '...creation FAILED!'.BR;
	
	
	//join objects
	
	if ($dbi->createJDAO('User', 'Entity')) echo '...creation success'.BR;
	else echo '...creation FAILED!'.BR;
	if ($dbi->createJoinObjectFactory('User', 'Entity')) echo '...creation success'.BR;
	else echo '...creation FAILED!'.BR;
	if ($dbi->createJVO('User', 'Entity')) echo '...creation success'.BR;
	else echo '...creation FAILED!'.BR;
	if ($dbi->createJQO('User', 'Entity')) echo '...creation success'.BR;
	else echo '...creation FAILED!'.BR;
	
	
	//if ($dbi->createFieldCountException('User')) echo '...creation success'.BR;
	//else echo '...creation FAILED!'.BR;
	//if ($dbi->createFieldException('User')) echo '...creation success'.BR;
	//else echo '...creation FAILED!'.BR;
	//if ($dbi->createMessage('User')) echo '...creation success'.BR;
	//else echo '...creation FAILED!'.BR;
}


function testTableCreate() {
	// Ok, so why not use the table definition to also sort out the model creation?
	
	echo 'creating User table...'.BR;
	$fields = null;
	$item= array('id'=>'id');
	$fields[] = $item;
	
	$item = array('firstname'=>'v32');
	$fields[] = $item;
	
	$item = array('surname'=>'v32n');
	$fields[] = $item;
	
	$item = array('password'=>'v512n');
	$fields[] = $item;
	
	//$fields = array('id','firstname','surname','password',);
	$dbi = new DBIGenerator('User', '../model/', $fields, 'user',  DBIGenerator::USER);
	$sql = $dbi->createTableSQL('user', $fields, 'id');
	$ndbf = new NGODataDBFactory();
	
	$dropSQL = $dbi->dropTableSQL('user');
	
	$ndbf->runTableSQL($dropSQL, 'ngodata');
	$ndbf->runTableSQL($sql, 'ngodata');
}




function endPage() {
	echo '</body></html>';
}

startPage();
testTableCreate();
//createUser();
//useObject();

endPage();
?>