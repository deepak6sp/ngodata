<?php
interface iValidation {
	public function getField();
	public function getMessages();
	public function validateField($rule);
	public function isValid();
}
?>