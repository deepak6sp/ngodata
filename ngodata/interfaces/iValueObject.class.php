<?php
/**
 * 
 * Enter description here ...
 * @author Steve Cooke <sa_cooke@me.com>
 *
 */
interface iValueObject {
	public function setQueryObject(iQueryObject $qo);
	public function getQueryObject();
	public function setData(iPostData $data);
	public function getData();
	public function getDataItem($fieldName);
}
?>