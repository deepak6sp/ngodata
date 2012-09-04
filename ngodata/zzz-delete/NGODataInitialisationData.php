<?php
require_once '../metadata/MetadatumDAO.class.php';
require_once '../metadata/MetadatumVO.class.php';
require_once '../metadata/MetadatumQO.class.php';
require_once '../metadata/MetadatumFactory.class.php';

session_start();
$_SESSION['user_id'] = '0';

// this class sets up initial data required by the NGOData system

//############################################################################# //
// Truncate table first - set this up in NGODataDBFactory or NGOMetadataFactory
//############################################################################# //
$mf = NGOMetadataDB::getInstance();
$r = $mf->truncate('metadata');
if (!$r) {
	echo '<pre>';
	print 'Truncate failed...';
	echo '<pre>';
} else {
	echo '<pre>';
	print 'Truncate success...';
	echo '<pre>';
}


// initialises the metadata database
// ngodata username
$data[] = array(
	'variable' => 'ngodata_username',
	'value' => 'ngodata',
	'is_encrypted' => '0'
);
$nameQO = new MetadatumQO();
$nameDAO = new MetadatumDAO();
$nameVO = new MetadatumVO();
$nameVO->setData($data);
$nameVO->setQueryObject($nameQO);
$nameDAO->setData($nameVO);
if ($nameDAO->save()) {
	echo '<pre>';
	print 'Name success...';
	echo '<pre>';
} else {
	echo '<pre>';
	print 'Name fail...';
	echo '<pre>';
}


// ngodata password
$data[] = array(
	'variable' => 'ngodata_userpass',
	'value' => 'tLp_1039',
	'is_encrypted' => '0'
);
$passQO = new MetadatumQO();
$passDAO = new MetadatumDAO();
$passVO = new MetadatumVO();
$passVO->setData($data);
$passVO->setQueryObject($passQO);
$passDAO->setData($passVO);
if ($passDAO->save()) {
	echo '<pre>';
	print 'Pass success...';
	echo '<pre>';
} else {
	echo '<pre>';
	print 'Pass fail...';
	echo '<pre>';
}


$data[] = array(
	'variable' => 'ngodata_host',
	'value' => 'localhost',
	'isEncrypted' => '0'
);
$hostQO = new MetadatumQO();
$hostDAO = new MetadatumDAO();
$hostVO = new MetadatumVO();
$hostVO->setData($data);
$hostVO->setQueryObject($hostQO);
$hostDAO->setData($hostVO);
if ($hostDAO->save()) {
	echo '<pre>';
	print 'Host success...';
	echo '<pre>';
} else {
	echo '<pre>';
	print 'Host fail...';
	echo '<pre>';
}

$data[] = array(
	'variable' => 'ngodata_dbname',
	'value' => 'ngodata',
	'isEncrypted' => '0'
);
$dbnameQO = new MetadatumQO();
$dbnameDAO = new MetadatumDAO();
$dbnameVO = new MetadatumVO();
$dbnameVO->setData($data);
$dbnameVO->setQueryObject($dbnameQO);
$dbnameDAO->setData($dbnameVO);
if ($dbnameDAO->save()) {
	echo '<pre>';
	print 'Host success...';
	echo '<pre>';
} else {
	echo '<pre>';
	print 'Host fail...';
	echo '<pre>';
}

//################//
// the user stuff
//################//
$data[] = array(
	'variable' => 'ngouser_username',
	'value' => 'ngodata',
	'isEncrypted' => '0'
);
$nameQO = new MetadatumQO();
$nameDAO = new MetadatumDAO();
$nameVO = new MetadatumVO();
$nameVO->setData($data);
$nameVO->setQueryObject($nameQO);
$nameDAO->setData($nameVO);
if ($nameDAO->save()) {
	echo '<pre>';
	print 'User name success...';
	echo '<pre>';
} else {
	echo '<pre>';
	print 'User name fail...';
	echo '<pre>';
}

$data[] = array(
	'variable' => 'ngouser_userpass',
	'value' => 'tLp_1039',
	'isEncrypted' => '0'
);
$passQO = new MetadatumQO();
$passDAO = new MetadatumDAO();
$passVO = new MetadatumVO();
$passVO->setData($data);
$passVO->setQueryObject($passQO);
$passDAO->setData($passVO);
if ($passDAO->save()) {
	echo '<pre>';
	print 'User pass success...';
	echo '<pre>';
} else {
	echo '<pre>';
	print 'User pass fail...';
	echo '<pre>';
}


$data[] = array(
	'variable' => 'ngouser_host',
	'value' => 'localhost',
	'isEncrypted' => '0'
);
$hostQO = new MetadatumQO();
$hostDAO = new MetadatumDAO();
$hostVO = new MetadatumVO();
$hostVO->setData($data);
$hostVO->setQueryObject($hostQO);
$hostDAO->setData($hostVO);
if ($hostDAO->save()) {
	echo '<pre>';
	print 'User host success...';
	echo '<pre>';
} else {
	echo '<pre>';
	print 'User host fail...';
	echo '<pre>';
}

$data[] = array(
	'variable' => 'ngouser_dbname',
	'value' => 'ngouser',
	'isEncrypted'=>'0');
$dbnameQO = new MetadatumQO();
$dbnameDAO = new MetadatumDAO();
$dbnameVO = new MetadatumVO();
$dbnameVO->setData($data);
$dbnameVO->setQueryObject($dbnameQO);
$dbnameDAO->setData($dbnameVO);
if ($dbnameDAO->save()) {
	echo '<pre>';
	print 'User dbname success...';
	echo '<pre>';
} else {
	echo '<pre>';
	print 'User dbname fail...';
	echo '<pre>';
}
?>