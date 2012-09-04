<?php
interface iBindParam {
	public function add($type, &$value);
	public function get();
	public function getBindResult();
	public function getTypes();
	public function getValues();
}
?>