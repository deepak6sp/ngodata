<?php
require_once '../interfaces/NGOValueObjectInterface.class.php';
require_once '../model/UserEntityQO.class.php';
/**
 * Join value object: UserEntity
 * @author ngodata <ngodata@me.com>
 * DO NOT ALTER - this class is created automatically by the NGOData system.
 * Access this object ONLY through a factory object.
 */

class UserEntityJVO implements NGOValueObjectInterface{
	// Attributes
	private $qo;

	// attribute - User
	private $userId;

	// attribute - Entity
	private $entityId;



	public function __construct() {
	}


	// attribute access - no mutator, use setData()
	public function getUserId() {
		return $this->user;
	}

	// attribute access - no mutator, use setData()
	public function getEntityId() {
		return $this->entity;
	}

	public function setQueryObject(NGODataQueryObjectInterface $qo) {
		$this->qo = $qo;
	}

	public function getQueryObject() {
		if (isset($this->qo)) {
			return $this->qo;
		}
		return false;
	}

	// mutator for object id
	//public function setId($id) {
		//$this->id = $id;
	//}

	/**
	 * Method created automatically by NGOData system
	 * The $tablename field is created and set by the DBIGenerator.
	 */
	public function getTablename() {
		return $this->qo->getTablename();
	}

	/**
	 * Method created automatically by NGOData system
	 * The $fields array is created and set by the DBIGenerator.
	 */
	public function getFields() {
		return $this->qo->getFields();
	}

	/**
	 * Method created automatically by NGOData system
	 */
	public function setData(array $data) {
		foreach ($data as $item) {
			foreach ($item as $key=>$value) {
				switch ($key) {
					case ($key == 'user_id'):
						$this->userId = $value;
						break;

					case ($key == 'entity_id'):
						$this->entityId = $value;
						break;

				}
			}
		}
	}

	/**
	 * Method created automatically by NGOData system
	 */
	public function getData() {
		$data = array();
		$item = array('user_id'=>$this->getuserId());
		$data[] = $item;

		$item = array('entity_id'=>$this->getentityId());
		$data[] = $item;

		return $data;
	}
}
?>
