<?php
interface iDataAccessObject {
	//public function getId();
	public function saveObject();
	public function deleteObject();
	public function updateObject();
	public function setValueObject(iValueObject $vo);
	public function getValueObject();
}
?>