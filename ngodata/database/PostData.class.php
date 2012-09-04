<?php
require_once '../database/iPostData.class.php';

// this class emulates the $_POST array for passing around the application
class PostData implements iPostData {
	private $data = null;
	
	public function __construct(array $data, $post = null) {
		if (is_null($post)) $this->setData($data);
		else $this->setPostData($data);
	}
	
	public function getData() {
		if ($this->data === null) return null;
		return $this->data;
	}
	
	private function setPostData($data) {
		$dataArray = null;
		foreach ($data as $key=>$value) {
			$a = array($key => $value);
			$dataArray[] = $a;
		}
		$this->data = $dataArray;
	}
	
	private function setData($data) {
		//$this->data = $data;
		
		$dataArray = null;
		foreach ($data as $key=>$value) {
			//$a = array($key => $value);
			//$dataArray[] = $a;
			
			$dataArray[$key] = $value;
			//foreach ($value as $field=>$item) {
				//$dataArray[$field] = $item;
			//}
		}
		$this->data = $dataArray;
		echo '<pre>';
		print_r($this->data);
		echo '</pre>';
	}
}
?>