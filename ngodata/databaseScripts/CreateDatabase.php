<?php
//require_once ('NGOData.class.php');
require_once ('NGODataDB.class.php');

//class CreateDatabase{

$con = NGODataDB::getInstance();
	// Create NGOMetaData database

// select database...
mysql_select_db('ngometadata', $con);


// Create table
$sql = 'create table metadata (
	variable varchar(16) not null,
	value varchar(128) not null)';


mysql_query($sql,$con) or die ('Error: '.mysql_error());


/*
// NGOData database tables

// call the table definitions
contact();
organisation();
position();
address();
title();
entity();
phone();
email();
user();
addressType();

// lookup tables
contactAddress();
contactEmail();
contactPhone();
contactPosition();
userEntity();
userPosition();
userPhone();
organisationAddress();
organisationEmail();
organisationPhone();
entityAddress();
entityPhone();
entityEmail();


// table definitions
function address() {
	
	$sql = 'drop table if exists address';
	
	//$database = DaybookDB::getInstance();
	if ($database->insert($sql)) print 'Address...done!<br />';
	print 'Address...fail!<br />';
	
	$sql = 'create table address (
	id int(11) not null auto_increment,
	entity_id int(11) not null,
	address_1 varchar(128),
	address_2 varchar(128),
	city varchar(64),
	state varchar(32),
	postcode varchar(16),
	country varchar(32),
	address_type_id int(11),
	is_primary tinyint,
	primary key (id)
	)';
	
	$database = DaybookDB::getInstance();
	if ($database->insert($sql)) print '...done!<br />';
	else print '...error!<br />';
}
*/


?>