<?php
require_once '../architecture/ModelGenerator.class.php';
//require_once '../database/DBIGenerator.class.php';
//require_once '../factories/NGODataDBFactory.class.php';

// model definition file - RUN THIS TO CREATE THE PROJECT ARCHITECTURE

/**
 * USAGE:
 * $name: the name of the class to be created, and of the corresponding db table.
 * $path: the directory where the classes will be created.
 * $item: is an array with various elements that the system reads and creates various classes from:
 * 		- 'username'=>'v64n': field name and data type, including an indicator that the field is non-null 
 * 		- 'display': this field tells the architecture what fields need displaying and the context,
 *			separated by a pipe symbol (|)
 *			- r = review;
 *			- u = update;
 *			- n = new;
 *		- 'valid': this fields gives validation rules, separated by a pipe symbol (|). Valid rules are
 *			- email
 *			- required
 *			- trim
 * NOTE: id is ALWAYS autoincrement.  If you do not want id to autoincrement, call it something else - title_id,...
 */

class ModelDefinition {

	public function __construct() {
	}

	public function runModelDefinition(ModelGenerator $mg) {
		$this->defineUser($mg);
		$this->defineEntity($mg);
		$this->defineUserEntity($mg);
		$this->defineRegistration($mg);
		$this->defineTitle($mg);
	}


	//User table
	private function defineUser($mg) {
		echo 'creating User table...'."\n";
	
		$name = 'User';
		$path = '../model/';
		$dbname = 'ngouser';
		//global $mg;
	
		$fields = null;
		$item= array('id'=>'id');
		$fields[] = $item;
		
		$item = array('username'=>'v64n', 'display'=>'r|n|u', 'valid'=>'required');
		$fields[] = $item;
	
		$item = array('password'=>'v512n', 'display'=>'n|u', 'valid'=>'required');
		$fields[] = $item;
	
		$item = array('preferred_name'=>'v64', 'display'=>'r|u|n');
		$fields[] = $item;
		
		$item = array('title_id'=>'int', 'display'=>'r|u|n');
		$fields[] = $item;
		
		$item = array('firstname'=>'v32n', 'display'=>'r|u|n', 'valid'=>'required');
		$fields[] = $item;
		
		$item = array('middlename'=>'v32', 'display'=>'r|u|n');
		$fields[] = $item;
		
		$item = array('surname'=>'v32n', 'display'=>'r|u|n', 'valid'=>'required');
		$fields[] = $item;
		
		$item = array('user_creation_date'=>'dtn', 'display'=>'r');
		$fields[] = $item;
	
		//$mg = new ModelGenerator();
		$mg->manageObjectCreation($name, $path, $fields, $dbname, 'id');
	}

	private function defineEntity($mg) {
		echo 'creating Entity table...'."\n";
	
		$name = 'Entity';
		$path = '../model/';
		$dbname = 'ngouser';
	
	
		$fields = null;
		$item = array('id'=>'id');
		$fields[] = $item;
	
		$item = array('entity_name'=>'v128n', 'display'=>'r|u|n', 'valid'=>'required');
		$fields[] = $item;
	
		$item = array('description'=>'t');
		$fields[] = $item;
	
		$item = array('logo'=>'b');
		$fields[] = $item;
	
		$item = array('business_registration_code'=>'v32n', 'display'=>'r|u|n', 'valid'=>'required');
		$fields[] = $item;
	
		$item = array('entity_database'=>'v64');
		$fields[] = $item;
	
		$item = array('security_key'=>'v512');
		$fields[] = $item;
	
		//$mg = new ModelGenerator();
		$mg->manageObjectCreation($name, $path, $fields, $dbname, 'id');
	}

	private function defineUserEntity($mg) {
		echo 'creating UserEntity table...'."\n";
		$names[] = 'User';
		$names[] = 'Entity';
		$path = '../model/';
		$dbname = 'ngouser';
	
	
		$fields = null;
		$item = array('user_id'=>'intn');
		$fields[] = $item;
	
		$item = array('entity_id'=>'intn');
		$fields[] = $item;
		
		//$mg = new ModelGenerator();
		$mg->manageObjectCreation($names, $path, $fields, $dbname, null);
	}
	
	
	private function defineRegistration($mg) {
		echo 'creating Registration table...'."\n";
		$name = 'Registration';
		$path = '../model/';
		$dbname = 'ngoguest';
		
		$fields = null;
		$item = array('id'=>'id');
		$fields[] = $item;
	
		$item = array('business_name'=>'v128n', 'display'=>'r|u|n', 'valid'=>'required');
		$fields[] = $item;
	
		$item = array('business_registration_code'=>'v32n', 'display'=>'r|u|n', 'valid'=>'required');
		$fields[] = $item;
		
		$item = array('email'=>'v64n', 'display'=>'r|u|n', 'valid'=>'required|email');
		$fields[] = $item;
		
		$item = array('title_id'=>'intn', 'display'=>'r|u|n', 'valid'=>'required|numeric');
		$fields[] = $item;
		
		$item = array('firstname'=>'v32n', 'display'=>'r|u|n', 'valid'=>'required');
		$fields[] = $item;
		
		$item = array('middlename'=>'v32', 'display'=>'r|u|n');
		$fields[] = $item;
		
		$item = array('surname'=>'v32n', 'display'=>'r|u|n', 'valid'=>'required');
		$fields[] = $item;
		
		$item = array('registration_request_date'=>'dtn');
		$fields[] = $item;
		
		$item = array('registration_number'=>'v16');
		$fields[] = $item;
	
		//$mg = new ModelGenerator();
		$mg->manageObjectCreation($name, $path, $fields, $dbname, 'id');
	} 
	
	
	private function defineTitle($mg) {
		echo 'creating Title table...'."\n";
		$name = 'Title';
		$path = '../model/';
		$dbname = 'ngodata';
		
		$fields = null;
		$item = array('id'=>'id');
		$fields[] = $item;
	
		$item = array('title'=>'v16n', 'display'=>'r|u|n', 'valid'=>'required');
		$fields[] = $item;
		
		$item = array('description'=>'v32n', 'display'=>'r|u|n', 'valid'=>'required');
		$fields[] = $item;
		
		$mg->manageObjectCreation($name, $path, $fields, $dbname, 'id');
	}
}
?>