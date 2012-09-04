<?php
// not decided on this yet...
require_once '../interfaces/iValueObject.class.php';
require_once '../interfaces/iQueryObject.class.php';
require_once '../database/iPostData.class.php';
require_once '../database/PostData.class.php';
/**
 * Value object: Title
 * @author ngodata <ngodata@me.com>
 * DO NOT ALTER - this class is created automatically by the NGOData system.
 * Access this object ONLY through a factory object.
 */

class TitleVO implements iValueObject{
//class Title {
	// Attributes
	private $qo;

	private $id = null;
	private $title = null;
	private $description = null;


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

			case 'title':
				return $this->title;
				break;

			case 'description':
				return $this->description;
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

					case ($key == 'title'):
						$this->title = $value;
						break;

					case ($key == 'description'):
						$this->description = $value;
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
		$item = array('title'=>$this->getDataItem('title'));
		$data[] = $item;
		$item = array('description'=>$this->getDataItem('description'));
		$data[] = $item;
		$pd = new PostData($data);
		return $pd;
	}
}
?>
