<?php
// not decided on this yet...
//require_once '../model/ValueObjectInterface.class.php';
/**
 * Value object: User
 * @author ngodata <ngodata@me.com>
 * DO NOT ALTER - this class is created automatically by the NGOData system.
 * Access this object ONLY through a factory object.
 */

//class User implements ValueObjectInterface{
class User {
	// Attributes
	private $data = NULL;
	private $id;
	private $firstname;
	private $surname;
	private $password;

	// May remove paramenter so dependency injection occurs via a setter
	public function __construct($data) {
		$this->setData($data);
	}


	// attribute access - no mutator
	public function getid() {
		return $this->id;
	}


	// attribute access - no mutator
	public function getfirstname() {
		return $this->firstname;
	}


	// attribute access - no mutator
	public function getsurname() {
		return $this->surname;
	}


	// attribute access - no mutator
	public function getpassword() {
		return $this->password;
	}

	// mutator for object id
	public function setId($id) {
		$this->id = $id;
	}

	// create with data array
	public function setData($data) {
		foreach ($data as $key=>$value) {
			foreach ($value as $k2=>$v2) {
				switch ($k) {
					case ($k2 == 'id'):
						$this->id = $v2;
						break;
					case ($k2 == 'firstname'):
						$this->firstname = $v2;
						break;
					case ($k2 == 'surname'):
						$this->surname = $v2;
						break;
					case ($k2 == 'password'):
						$this->password = $v2;
						break;
				}
			}
		}
	}
}
?>

