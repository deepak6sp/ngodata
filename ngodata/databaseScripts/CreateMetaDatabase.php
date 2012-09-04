<?php
require_once ('../classes/NGOMetadataDB.class.php');
require_once ('../classes/NGODataSecurity.class.php');
session_start();

$con = NGOMetadataDB::getInstance();
print_r($con);

// Create metadata table
$sql = "create table if not exists metadata (
	variable varchar(16) not null,
	value varchar(255) not null,
	is_encrypted tinyint not null)";

$d = $con->insert($sql);


//print_r($_SESSION);

// Create sessions table
$sql = "create table if not exists sessions
	(id varchar(255) NOT NULL,
	data text,
	lastaccess int(10) unsigned DEFAULT '0',
	primary key (id))";

$d2 = $con->insert($sql);

print_r($_SESSION);

// insert some bootstrap data

// global salt
//global salt - I must go down to the sea again. From Dawkins, "The Greatest Show On Earth", p. 169, 
//$ds = new NGODataSecurity();
//$hashVal = $ds->encrypt('',NULL,'I must go down to the sea again');
$hashVal = 'I must go down to the sea again';

$sql = "insert into metadata (variable, value,is_encrypted) values ('gSecTerm','".addslashes($hashVal)."','0')";
print $sql;
$d3 = $con->insert($sql);


if (!$d3) print ' ...fail!';
else print ' ...all good!';

// need to insert the NGOData database info

// NGOData database tables




/*
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