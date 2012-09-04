<?php
require_once '../classes/NGODataSecurity.class.php';
require_once '../classes/NGODataDB.class.php';
require_once '../classes/NGOMetadataDB.class.php';



// first need to create NGOData database
function createNGODataDatabase() {
	$con = NGOMetadataDB::getInstance();
	//$con = mysql_connect("localhost","peter","abc123");
	if (!$con) {
  		die('Could not connect: ' . mysql_error());
  	}
  	
	
  	$sql = 'create database ngodata';
	if (mysql_query($sql,$con)) {
		echo "Database created";
	} else {
		echo "Error creating database: " . mysql_error();
	}
	
	// now grant permissions
	$sql = 'grant CREATE,INSERT,DELETE,UPDATE,SELECT on ngodata.* to ngodata@localhost';
	//mysql_close($con);
}


// first need to populate the relevant fields in te metadata with access details for NGOdata db

// create the database
createNGODataDatabase();

/*
// ngouser database
createUser();
createEntity();
createUserEntity();
createEntityAddress();
createUserAddress();
createAddress();
createAddressType();
createPhone();
createPhoneType();
createUserPhone();
createEntityPhone();
createUserPhone();
createEmail();
createUserEmail();
createEntityEmail();


createContact();
createContactAddress();
createContactPhone();
createTitle();
createEmail();
crreateContactEmail();
createAddress();
createAddressType();
createPhone();
createPhoneType();
*/

// need to store the NGOData database access information
/**
 * Funciton: storeNGODataDatabaseAccessCodes($host, $username, $password, $dbname)
 * @author Steve Cooke <sa_coke@internode.on.net>
 * @param string $host The host where the database resides.
 * @param string $username The username required to access the database.
 * @param string $password The password required to access the database.
 * @param string $name The name of the database to access.
 * @desc Takes four params and stores them with appropriate encryption. Only to be used ONCE as part of system setup.
 */
function storeNGODataDatabaseAccessCodes($host, $username, $password, $name) {
	// need all four codes, so error if any are blank - don't handle, as not a user level tool, and only used once.
	$ngo = new NGODataSecurity();
	$dbhost = $ngo->encrypt('', $host);
	$dbusername = $ngo->encrypt('', $username);
	//$dbpassword = $ngo->encrypt('', $host);
	$dbname = $ngo->encrypt('', $name);
	$globalSalt = 
	
	$sql = 'insert into NGOMetaData ';
}


function createUser() {
	$sql = 'drop table if exists user';
	$database = NGODataDB::getInstance();
	if ($database->run($sql)) echo 'Drop user table...done!<br />';
	else echo 'Drop user table...fail!<br />';
	
	
	$sql = 'create table address (
		id int(11) not null auto_increment,
		username varchar(64) not null,
		password varchar(512) not null,
		preferred_name varchar(64),
		title_id int(11),
		first_name varchar(32) not null,
		middle_name varchar(32),
		surname varchar(32) not null,
		user_creation_date datetime not null,
		primary key (id)
		)';
	
	//$database = DaybookDB::getInstance();
	if ($database->run($sql)) echo 'Create user table...done!<br />';
	else echo 'Create user table...error!<br />';
}


function createEntity() {
	$sql = 'drop table if exists entity';
	
	$database = NGODataDB::getInstance();
	if ($database->run($sql)) echo 'Drop entity table...done!<br />';
	else echo 'Drop entity table...fail!<br />';
	
	$sql = 'create table entity (
		id int(11) not null auto_increment,
		entity_name varchar(128),
		description text,
		logo blob,
		business_registration_code varchar(32),
		database varchar(64),
		security_key varchar(512),
		primary key (id)
		)';
	
	//$database = DaybookDB::getInstance();
	if ($database->run($sql)) echo 'Create entity table...done!<br />';
	else echo 'Create entity table...error!<br />';
}


function createUserEntity() {
	$sql = 'drop table if exists user_entity';
	
	$database = NGODataDB::getInstance();
	if ($database->run($sql)) echo 'Drop user_entity table...done!<br />';
	else echo 'Drop user_entity table...fail!<br />';
	
	$sql = 'create table user_entity (
		entity_id int(11) not null,
		user_id int(11) not null,
		user_type_id int(11) not null
		)';
	
	//$database = DaybookDB::getInstance();
	if ($database->run($sql)) echo 'Create user_entity table...done!<br />';
	else echo 'Create user_entity table...error!<br />';
}


function createUserType() {
	$sql = 'drop table if exists user_type';
	
	$database = NGODataDB::getInstance();
	if ($database->run($sql)) echo 'Drop user_type table...done!<br />';
	else echo 'Drop user_type table...fail!<br />';
	
	$sql = 'create table user_type (
		id int(11) not null,
		description varchar(32) not null
		)';
	
	//$database = DaybookDB::getInstance();
	if ($database->run($sql)) echo 'Create user_type table...done!<br />';
	else echo 'Create user_type table...error!<br />';
}


function createContact() {
	$sql = 'drop table if exists contact';
	
	$database = NGODataDB::getInstance();
	if ($database->run($sql)) echo 'Drop contact table...done!<br />';
	else echo 'Drop contact table...fail!<br />';
	
	$sql = 'create table contact (
		id int(11) not null auto_increment,
		user_id int(11) not null,
		title_id int(11),
		first_name varchar(32),
		middle_name varchar(32),
		surname varchar(32),
		organisation varchar(128),
		position varchar(128),
		first_contact_date datetime not null,
		primary key (id)
		)';
	
	//$database = DaybookDB::getInstance();
	if ($database->run($sql)) echo 'Create contact table...done!<br />';
	else echo 'Create contact table...error!<br />';
}


function createContactAddress() {
	$sql = 'drop table if exists contact_address';
	
	$database = NGODataDB::getInstance();
	if ($database->run($sql)) echo 'Drop contact_address table...done!<br />';
	else echo 'drop contact_address table...fail!<br />';
	
	$sql = 'create table contact_address (
		contact_id int(11) not null,
		address_id int(11) not null
		)';
	
	//$database = DaybookDB::getInstance();
	if ($database->run($sql)) echo 'Create contact_address...done!<br />';
	else echo 'Create contact_address...error!<br />';
}



function createContactPhone() {
	$sql = 'drop table if exists contact_phone';
	
	$database = NGODataDB::getInstance();
	if ($database->run($sql)) echo 'Drop contact_phone table...done!<br />';
	else echo 'drop contact_phone table...fail!<br />';
	
	$sql = 'create table contact_phone (
		contact_id int(11) not null,
		phone_id int(11) not null
		)';
	
	//$database = DaybookDB::getInstance();
	if ($database->run($sql)) echo 'Create contact_phone...done!<br />';
	else echo 'Create contact_phone...error!<br />';
}



function createTitle() {
	$sql = 'drop table if exists title';
	
	$database = NGODataDB::getInstance();
	if ($database->run($sql)) echo 'Drop title table...done!<br />';
	else echo 'drop title table...fail!<br />';
	
	$sql = 'create table title (
		id int(11) not null,
		title varchar(8) not null,
		description varchar(64),
		primary key (id)
		)';
	
	//$database = DaybookDB::getInstance();
	if ($database->run($sql)) echo 'Create title...done!<br />';
	else echo 'Create title...error!<br />';
}

//********************//
//** Address tables **//
//********************//
function createAddress() {
	$sql = 'drop table if exists address';
	
	$database = NGODataDB::getInstance();
	if ($database->run($sql)) echo 'Drop address table...done!<br />';
	else echo 'Drop address table...fail!<br />';
	
	$sql = 'create table address (
		id int(11) not null auto_increment,
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
	
	//$database = DaybookDB::getInstance();
	if ($database->run($sql)) echo 'Create address...done!<br />';
	else echo 'Create address...error!<br />';
}

function createAddressType() {
	$sql = 'drop table if exists address_type';
	
	$database = NGODataDB::getInstance();
	if ($database->run($sql)) echo 'Drop address_type table...done!<br />';
	else echo 'Address_type drop table...fail!<br />';
	
	$sql = 'create table address_type (
		id int(11) not null auto_increment,
		address_type varchar(32),
		description varchar(128),
		primary key (id)
		)';
	
	//$database = DaybookDB::getInstance();
	if ($database->run($sql)) echo 'Create address_type...done!<br />';
	else echo 'Create address_type...error!<br />';
}

function createEntityAddress() {
	$sql = 'drop table if exists entity_address';
	
	$database = NGODataDB::getInstance();
	if ($database->run($sql)) echo 'Drop entity_address table...done!<br />';
	else echo 'entity_address drop table...fail!<br />';
	
	$sql = 'create table entity_address (
		entity_id int(11) not null,
		address_id int(11) not null
		)';
	
	//$database = DaybookDB::getInstance();
	if ($database->run($sql)) echo 'Create entity_address...done!<br />';
	else echo 'Create entity_address...error!<br />';
}


/**
 * Function: createUserAddress()
 * @author Steve Cooke <ngodata@internode.on.net>
 * Date: Dec 2011
 */

function createUserAddress() {
	$sql = 'drop table if exists user_address';
	
	$database = NGODataDB::getInstance();
	if ($database->run($sql)) echo 'Drop user_address table...done!<br />';
	else echo 'user_address drop table...fail!<br />';
	
	$sql = 'create table user_address (
		user_id int(11) not null,
		address_id int(11) not null
		)';
	
	//$database = DaybookDB::getInstance();
	if ($database->run($sql)) echo 'Create user_address...done!<br />';
	else echo 'Create user_address...error!<br />';
}


/******************/
/** Phone tables **/
/******************/
function createPhone() {
	$sql = 'drop table if exists user_address';
	
	$database = NGODataDB::getInstance();
	if ($database->run($sql)) echo 'Drop user_address table...done!<br />';
	else echo 'user_address drop table...fail!<br />';
	
	$sql = 'create table user_address (
		user_id int(11) not null,
		address_id int(11) not null
		)';
	
	//$database = DaybookDB::getInstance();
	if ($database->run($sql)) echo 'Create user_address...done!<br />';
	else echo 'Create user_address...error!<br />';
}

function createPhoneType() {
	$sql = 'drop table if exists phone_type';
	
	$database = NGODataDB::getInstance();
	if ($database->run($sql)) echo 'Drop phone_type table...done!<br />';
	else echo 'drop phone_type table...fail!<br />';
	
	$sql = 'create table phone_type (
		id int(11) not null auto_increment,
		phone_type varchar(32),
		description varchar(128),
		primary key (id)
		)';
	
	//$database = DaybookDB::getInstance();
	if ($database->run($sql)) echo 'Create phone_type...done!<br />';
	else echo 'Create phone_type...error!<br />';
}


function createUserPhone() {
	$sql = 'drop table if exists user_phone';
	
	$database = NGODataDB::getInstance();
	if ($database->run($sql)) echo 'Drop user_phone table...done!<br />';
	else echo 'drop user_phone table...fail!<br />';
	
	$sql = 'create table user_phone (
		user_id int(11) not null,
		phone_id int(11) not null
		)';
	
	//$database = DaybookDB::getInstance();
	if ($database->run($sql)) echo 'Create user_phone...done!<br />';
	else echo 'Create user_phone...error!<br />';
}

function createEntityPhone() {
	$sql = 'drop table if exists entity_phone';
	
	$database = NGODataDB::getInstance();
	if ($database->run($sql)) echo 'Drop entity_phone table...done!<br />';
	else echo 'drop entity_phone table...fail!<br />';
	
	$sql = 'create table entity_phone (
		entity_id int(11) not null,
		phone_id int(11) not null
		)';
	
	//$database = DaybookDB::getInstance();
	if ($database->run($sql)) echo 'Create entity_phone...done!<br />';
	else echo 'Create entity_phone...error!'."\n";
}


function createContactPhone() {
	$sql = 'drop table if exists contact_phone';
	
	$database = NGODataDB::getInstance();
	if ($database->run($sql)) echo 'Drop contact_phone table...done!<br />';
	else echo 'drop contact_phone table...fail!<br />';
	
	$sql = 'create table contact_phone (
		entity_id int(11) not null,
		phone_id int(11) not null
		)';
	
	//$database = DaybookDB::getInstance();
	if ($database->run($sql)) echo 'Create contact_phone...done!<br />';
	else echo 'Create contact_phone...error!<br />';
}




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