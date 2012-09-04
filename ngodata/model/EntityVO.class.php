<?php
// not decided on this yet...
require_once '../interfaces/iValueObject.class.php';
require_once '../interfaces/iQueryObject.class.php';
require_once '../database/iPostData.class.php';
require_once '../database/PostData.class.php';
/**
 * Value object: Entity
 * @author ngodata <ngodata@me.com>
 * DO NOT ALTER - this class is created automatically by the NGOData system.
 * Access this object ONLY through a factory object.
 */

class EntityVO implements iValueObject{
//class Entity {
	// Attributes
	private $qo;

	private $id = null;
	private $entity_name = null;
	private $description = null;
	private $logo = null;
	private $business_registration_code = null;
	private $entity_database = null;
	private $security_key = null;


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

			case 'entity_name':
				return $this->entity_name;
				break;

			case 'description':
				return $this->description;
				break;

			case 'logo':
				return $this->logo;
				break;

			case 'business_registration_code':
				return $this->business_registration_code;
				break;

			case 'entity_database':
				return $this->entity_database;
				break;

			case 'security_key':
				return $this->security_key;
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

					case ($key == 'entity_name'):
						$this->entity_name = $value;
						break;

					case ($key == 'description'):
						$this->description = $value;
						break;

					case ($key == 'logo'):
						$this->logo = $value;
						break;

					case ($key == 'business_registration_code'):
						$this->business_registration_code = $value;
						break;

					case ($key == 'entity_database'):
						$this->entity_database = $value;
						break;

					case ($key == 'security_key'):
						$this->security_key = $value;
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
		$item = array('entity_name'=>$this->getDataItem('entity_name'));
		$data[] = $item;
		$item = array('description'=>$this->getDataItem('description'));
		$data[] = $item;
		$item = array('logo'=>$this->getDataItem('logo'));
		$data[] = $item;
		$item = array('business_registration_code'=>$this->getDataItem('business_registration_code'));
		$data[] = $item;
		$item = array('entity_database'=>$this->getDataItem('entity_database'));
		$data[] = $item;
		$item = array('security_key'=>$this->getDataItem('security_key'));
		$data[] = $item;
		$pd = new PostData($data);
		return $pd;
	}
}
?>
