<?php
// not decided on this yet...
require_once '../interfaces/iValueObject.class.php';
require_once '../interfaces/iQueryObject.class.php';
require_once '../database/iPostData.class.php';
require_once '../database/PostData.class.php';
/**
 * Value object: User
 * @author ngodata <ngodata@me.com>
 * DO NOT ALTER - this class is created automatically by the NGOData system.
 * Access this object ONLY through a factory object.
 */

class UserVO implements iValueObject{
//class User {
	// Attributes
	private $qo;

	private $id = null;
	private $username = null;
	private $password = null;
	private $preferred_name = null;
	private $title_id = null;
	private $firstname = null;
	private $middlename = null;
	private $surname = null;
	private $user_creation_date = null;


	// May remove paramenter so dependency injection occurs via a setter
	//public function __construct(iPostData $data) {
	public function __construct() {
		//$this->setData($data->getData());
	}


	// attribute access - no mutator
	public function getDataItem($field) {
		switch ($field) {
			case 'id':
				return $this->id;
				break;

			case 'username':
				return $this->username;
				break;

			case 'password':
				return $this->password;
				break;

			case 'preferred_name':
				return $this->preferred_name;
				break;

			case 'title_id':
				return $this->title_id;
				break;

			case 'firstname':
				return $this->firstname;
				break;

			case 'middlename':
				return $this->middlename;
				break;

			case 'surname':
				return $this->surname;
				break;

			case 'user_creation_date':
				return $this->user_creation_date;
				break;

		}
	}


	public function setQueryObject(iQueryObject $qo) {
		$this->qo = $qo;
	}

	public function getQueryObject() {
		if (isset($this->qo)) {
			return $this->qo;
		}
		return false;
	}


	/**
	 * Method: setWhere($where)
	 * Method created automatically by NGOData system
	 */
	/**
	 * Method: getWhere()
	 * Method created automatically by NGOData system
	 */
	/**
	 * Method: setId($id)
	 * Method created automatically by NGOData system
	 */
	public function setId($id) {
		$this->id = $id;
	}


	/**
	 * Method created automatically by NGOData system
	 */
	/**
	 * Method created automatically by NGOData system
	 * The $fields array is created and set by the DBIGenerator.
	 */
	/**
	 * Method created automatically by NGOData system
	 */
	public function setData(iPostData $pd) {
		$data = $pd->getData();
		if (empty($data)) return;

		foreach ($data as $item) {
			foreach ($item as $key=>$value) {
			//foreach ($data as $key=>$value) {
				switch ($key) {
					case ($key == 'id'):
						$this->id = $value;
						break;

					case ($key == 'username'):
						$this->username = $value;
						break;

					case ($key == 'password'):
						$this->password = $value;
						break;

					case ($key == 'preferred_name'):
						$this->preferred_name = $value;
						break;

					case ($key == 'title_id'):
						$this->title_id = $value;
						break;

					case ($key == 'firstname'):
						$this->firstname = $value;
						break;

					case ($key == 'middlename'):
						$this->middlename = $value;
						break;

					case ($key == 'surname'):
						$this->surname = $value;
						break;

					case ($key == 'user_creation_date'):
						$this->user_creation_date = $value;
						break;

				}
			}
		}
		return;
	}

	/**
	 * Method created automatically by NGOData system
	 */
	public function getData() {
		$data = array();
		$item = array('id'=>$this->getDataItem('id'));
		$data[] = $item;
		$item = array('username'=>$this->getDataItem('username'));
		$data[] = $item;
		$item = array('password'=>$this->getDataItem('password'));
		$data[] = $item;
		$item = array('preferred_name'=>$this->getDataItem('preferred_name'));
		$data[] = $item;
		$item = array('title_id'=>$this->getDataItem('title_id'));
		$data[] = $item;
		$item = array('firstname'=>$this->getDataItem('firstname'));
		$data[] = $item;
		$item = array('middlename'=>$this->getDataItem('middlename'));
		$data[] = $item;
		$item = array('surname'=>$this->getDataItem('surname'));
		$data[] = $item;
		$item = array('user_creation_date'=>$this->getDataItem('user_creation_date'));
		$data[] = $item;
		$pd = new PostData($data);
		return $pd;
	}
}
?>
