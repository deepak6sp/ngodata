<?php
// not decided on this yet...
require_once '../interfaces/iValueObject.class.php';
require_once '../interfaces/iQueryObject.class.php';
require_once '../database/iPostData.class.php';
require_once '../database/PostData.class.php';
/**
 * Value object: Registration
 * @author ngodata <ngodata@me.com>
 * DO NOT ALTER - this class is created automatically by the NGOData system.
 * Access this object ONLY through a factory object.
 */

class RegistrationVO implements iValueObject{
//class Registration {
	// Attributes
	private $qo;

	private $id = null;
	private $business_name = null;
	private $business_registration_code = null;
	private $email = null;
	private $title_id = null;
	private $firstname = null;
	private $middlename = null;
	private $surname = null;
	private $registration_request_date = null;
	private $registration_number = null;


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

			case 'business_name':
				return $this->business_name;
				break;

			case 'business_registration_code':
				return $this->business_registration_code;
				break;

			case 'email':
				return $this->email;
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

			case 'registration_request_date':
				return $this->registration_request_date;
				break;

			case 'registration_number':
				return $this->registration_number;
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

					case ($key == 'business_name'):
						$this->business_name = $value;
						break;

					case ($key == 'business_registration_code'):
						$this->business_registration_code = $value;
						break;

					case ($key == 'email'):
						$this->email = $value;
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

					case ($key == 'registration_request_date'):
						$this->registration_request_date = $value;
						break;

					case ($key == 'registration_number'):
						$this->registration_number = $value;
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
		$item = array('business_name'=>$this->getDataItem('business_name'));
		$data[] = $item;
		$item = array('business_registration_code'=>$this->getDataItem('business_registration_code'));
		$data[] = $item;
		$item = array('email'=>$this->getDataItem('email'));
		$data[] = $item;
		$item = array('title_id'=>$this->getDataItem('title_id'));
		$data[] = $item;
		$item = array('firstname'=>$this->getDataItem('firstname'));
		$data[] = $item;
		$item = array('middlename'=>$this->getDataItem('middlename'));
		$data[] = $item;
		$item = array('surname'=>$this->getDataItem('surname'));
		$data[] = $item;
		$item = array('registration_request_date'=>$this->getDataItem('registration_request_date'));
		$data[] = $item;
		$item = array('registration_number'=>$this->getDataItem('registration_number'));
		$data[] = $item;
		$pd = new PostData($data);
		return $pd;
	}
}
?>
