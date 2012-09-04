<?php
interface NGODataDBInterface {
	public function connect();
	public function select();
	public function insert();
	public function delete();
	public function update();	
}
?>