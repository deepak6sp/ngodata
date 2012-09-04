<?php
/************************************************************************
Validate_fields Class - verion 1.35
Easy to use form field validation

Copyright (c) 2004 - 2006, Olaf Lederer
All rights reserved.
Modified: Steve Cooke, Feb 2010, Feb 2011

*************************************************************************/
require_once ('NGOData.class.php');


//error_reporting(E_ALL);

class ValidateField extends NGOData {
	
	var $fields = array();
	var $messages = array();
	var $check_4html = false;
	var $language;
	
	/*
	$msg[0] = 'Please correct the following error(s):';
	$msg[1] = 'The <b>'.$fieldname.'</b> field is empty.';
	$msg[10] = "The date in field <b>".$fieldname."</b> is not valid.";
	$msg[11] = "The e-mail address in field <b>".$fieldname."</b> is not valid.";
	$msg[12] = "The value in field <b>".$fieldname."</b> is not valid.";
	$msg[13] = "The text in field <b>".$fieldname."</b> is too long.";
	$msg[14] = "The url in field <b>".$fieldname."</b> is not valid.";
	$msg[15] = "There is html code in field <b>".$fieldname."</b>, this is not allowed.";
	$msg[16] = 'The year in <b>'.$fieldname.'</b> has an incorrect value.';
	$msg[17] = 'No selection was made in the <b>'.$fieldname.'</b> field. You must make a selection from those available.';
	$msg[18] = 'The <b>passwords</b> do not match. Please ensure both are the same.';
	$msg[19] = 'You are required to select one of the <b>'.$fieldname.'</b> radio buttons.';
	$msg[20] = 'You cannot delete all the categories from the <b>'.$fieldname.'</b> field. If you want to delete one, add one first so there are at least two.';
	*/
	
	
	
	
	
	public function __construct() {
		$this->create_msg();
	}
	
	public function validate() {
		$status = 0; 
		foreach ($this->fields as $key => $val) {
			switch ($val['type']) {
				case "text":
				if (!$this->checkText($val['value'], $key, $val['length'], $val['required'])) {
					$status++;
				}
				break;
				
				case "date":
				if (!$this->checkDate($val['value'], $key, $val['version'], $val['required'])) {
					$status++;
				}
				break;
				
				case "year":
				if (!$this->checkYear($val['value'], $key, $val['required'])) {
					$status++;
				}
				break;
				
				case "select":
				if (!$this->checkSelect($val['value'], $key, $val['required'])) {
					$status++;
				}
				break;
				
				case "pass":
				if (!$this->checkPass($val['pass1'], $val['pass2'], $key)) {
					$status++;
				}
				break;
				
				case "radio":
				if (!$this->checkRadio($val['element'], $key, $val['required'])) {
					$status++;
				}
				break;
				
				case "delete_cat":
				if (!$this->checkCategoryToDelete($val['cat_to_delete'], $val['delete_from_count'], $key)) {
					$status++;
				}
				break;
				
				case "add_cat":
				if (!$this->checkCategoryToAdd($val['cat_to_add'], $key)) {
					$status++;
				}
				break;
				
				
				

				/*
				case "email":
				if (!$this->check_email($val['value'], $key, $val['required'])) {
					$status++;
				}
				break;
				case "number":
				if (!$this->check_num_val($val['value'], $key, $val['length'], $val['required'])) {
					$status++;
				}
				break;
				case "decimal":
				if (!$this->check_decimal($val['value'], $key, $val['decimals'], $val['required'])) {
					$status++;
				}
				break;
				*/
				
				/*
				case "url":
				if (!$this->check_url($val['value'], $key, $val['required'])) {
					$status++;
				}
				break;
				*/
				/*
				case "checkbox":
				case "radio":
				if (!$this->check_check_box($val['value'], $key, $val['element'])) {
					$status++;
				}
				*/
			} 
			if ($this->check_4html) {
				if (!$this->check_html_tags($val['value'], $key)) {
					$status++;
				}
			}
		}
		if ($status == 0) {
			return true;
		} else {
			$this->messages[] = $this->error_text(0);
			//print 'From ValidateField - there are errors...';
			return false;
		}
	}
	
	public function addTextField($name, $val, $type = "text", $required = "y", $length = 0) {
		$this->fields[$name]['value'] = $val;
		$this->fields[$name]['type'] = $type;
		$this->fields[$name]['required'] = $required;
		$this->fields[$name]['length'] = $length;
	}
	
	public function addDateField($name, $val, $type = "date", $version = "us", $required = "y") {
		$this->fields[$name]['value'] = $val;
		$this->fields[$name]['type'] = $type;
		$this->fields[$name]['version'] = $version;
		$this->fields[$name]['required'] = $required;
	}
	
	public function addYearField($name, $val, $required = "y", $length = 4) {
		$this->fields[$name]['value'] = $val;
		$this->fields[$name]['type'] = 'number';
		$this->fields[$name]['required'] = $required;
		//$this->fields[$name]['decimals'] = $decimals;
		$this->fields[$name]['length'] = $length;
	}
	
	public function addSelect($name, $val, $type ="select", $required = "y") {
		$this->fields[$name]['value'] = $val;
		$this->fields[$name]['type'] = $type;
		$this->fields[$name]['required'] = $required;
	}
	
	public function addPass($name, $val1, $val2, $type="pass") {
		$this->fields[$name]['pass1'] = $val1;
		$this->fields[$name]['pass2'] = $val2;
		$this->fields[$name]['type'] = "pass";
	}
	
	public function addRadio($name, $element_id, $type="radio", $required = "y") {
		//$this->fields[$name]['value'] = $required_value;
		$this->fields[$name]['type'] = $type;
		$this->fields[$name]['element'] = $element_id;
		$this->fields[$name]['required'] = $required;
	}
	
	public function addCategory($name, $catToAdd, $type="add_cat", $required="y") {
		//$this->fields[$name]['value'] = 1;
		$this->fields[$name]['cat_to_add'] = $catToAdd;
		$this->fields[$name]['type'] = $type;
		$this->fields[$name]['required'] = $required;
	}
	
	public function removeOneValue($name, $CatToDelete, $catCount, $type="delete_cat", $required="y") {
		//$this->fields[$name]['delete_count'] = 1; // magic number - may only delete one cat
		$this->fields[$name]['cat_to_delete'] = $CatToDelete;
		$this->fields[$name]['delete_from_count'] = $catCount;
		$this->fields[$name]['type'] = $type;
		$this->fields[$name]['required'] = $required;
	}

	
	/*
	public function addNumField($name, $val, $type = "number", $required = "y", $decimals = 0, $length = 0) {
		$this->fields[$name]['value'] = $val;
		//$this->fields[$name]['type'] = $type;
		$this->fields[$name]['required'] = $required;
		$this->fields[$name]['decimals'] = $decimals;
		$this->fields[$name]['length'] = $length;
	}
	*/
	
	/*
	function add_link_field($name, $val, $type = "email", $required = "y") {
		$this->fields[$name]['value'] = $val;
		$this->fields[$name]['type'] = $type;
		$this->fields[$name]['required'] = $required;
	}
	*/
	
	/*
	public function add_check_box($name, $element_name, $type = "checkbox", $required_value = "") {
		$this->fields[$name]['value'] = $required_value;
		$this->fields[$name]['type'] = $type;
		$this->fields[$name]['element'] = $element_name;
	}
	*/
	
	/*
	function check_url($url_val, $field, $req = "y") {
		if ($url_val == "") {
			if ($req == "y") {
				$this->messages[] = $this->error_text(1, $field);
				return false;
			} else {
				return true;
			}
		} else {
			$url_pattern = "http\:\/\/[[:alnum:]\-\.]+(\.[[:alpha:]]{2,4})+";
			$url_pattern .= "(\/[\w\-]+)*"; // folders like /val_1/45/
			$url_pattern .= "((\/[\w\-\.]+\.[[:alnum:]]{2,5})?"; // filename like index.(s)html
			$url_pattern .= "|"; // end with filename or ?
			$url_pattern .= "\/?)"; // trailing slash or not
			$error_count = 0;
			if (strpos($url_val, "?")) {
				$url_parts = explode("?", $url_val);
				if (!preg_match("/^".$url_pattern."$/", $url_parts[0])) {
					$error_count++;
				}
				if (!preg_match("/^(&?[\w\-]+=\w*)+$/", $url_parts[1])) {
					$error_count++;
				}
			} else {
				if (!preg_match("/^".$url_pattern."$/", $url_val)) {
					$error_count++;
				}
			}
			if ($error_count > 0) {
				$this->messages[] = $this->error_text(14, $field);
					return false;
			} else {
				return true;
			}
		}
	}
	*/
	
	/*
	function checkNumVal($num_val, $field, $num_len = 0, $req = "n") {
		if ($num_val == "") {
			if ($req == "y") {
				$this->messages[] = $this->error_text(1, $field);
				return false;
			} else {
				return true;
			}
		} else {
			$pattern = ($num_len == 0) ? "/^\-?[0-9]*$/" : "/^\-?[0-9]{0,".$num_len."}$/";
			if (preg_match($pattern, $num_val)) {
				return true;
			} else {
				$this->messages[] = $this->error_text(12, $field);
				return false;
			}
		}
	}
	*/
	
	private function checkYear($num_val, $field, $num_len = 4, $req = "n") {
		if ($num_val == "") {
			if ($req == "y") {
				$this->messages[] = $this->error_text(1, $field);
				return false;
			} else {
				return true;
			}
		} else {
			$pattern = ($num_len == 0) ? "/^\-?[0-9]*$/" : "/^\-?[0-9]{0,".$num_len."}$/";
			if (preg_match($pattern, $num_val)) {
				return true;
			} else {
				$this->messages[] = $this->error_text(16, $field);
				return false;
			}
		}
	}

	
	/**
	 * Function: checkText()
	 * @desc checks whether text is beyond max length, or is empty
	 */
	private function checkText($text_val, $field, $text_len = 0, $req = "y") {
		if (empty($text_val)) {
			if ($req == "y") {
				$this->messages[] = $this->error_text(1, $field);
				return false;
			} else {
				return true; // in case only the text length is validated
			}
		} else {
			if ($text_len > 0) {
				if (strlen($text_val) > $text_len) {
					$this->messages[] = $this->error_text(13, $field);
					return false;
				} else {
					return true;
				}
			} else {
				return true;
			}
		}
	}
	
	// checks whether two text strings are equal...
	private function checkPass($pass1, $pass2, $fleld) {
		//print 'Pass1: '.$pass1.'; Pass2: '.$pass2.'<br />';
		if ($pass1 == $pass2) {
			return true;
		} else {
			$this->messages[] = $this->error_text(18, $field);
			return FALSE;
		}
	}
	
	private function checkSelect($select_vals, $field, $req ='y') {
		if (empty($select_vals)) {
			$this->messages[] = $this->error_text(17, $field);
			return false;
		} else {
			return true;
		}
	}
	
	
	// may only delete ONE category, but not allowed to leave no cats after deletion
	private function checkCategoryToDelete($cat_to_delete, $delete_from_count, $field) {
		//print 'cats_to_delete - '.$cats_to_delete.'<br>';
		if ($cat_to_delete == 0 ) {
			//error - no cat selected
			$this->messages[] = $this->error_text(17, $field);
			return false;
		} else if ($delete_from_count <= 1) {
			// error - there is only one cat from which to delete
			$this->messages[] = $this->error_text(20, $field);
			return false;
		}
		return true;
	}
	
	
	// check user has selected a category to add
	private function checkCategoryToAdd($cat_to_add, $field) {
		//print 'cats_to_delete - '.$cats_to_delete.'<br>';
		if ($cat_to_add == 0 ) {
			//error - no cat selected
			$this->messages[] = $this->error_text(17, $field);
			return false;
		}
		return true;
	}
	
	
	
	private function checkRadio($radio_id, $field, $req) {
		if ($req == 'y') {
			if ($radio_id == NULL || $radio_id =='') {
				$this->messages[] = $this->error_text(19, $field);
				return false;
			}
		}
		return true;
	}
	
	private function checkDate($date, $field, $version = "us", $req = "n") { 
		if ($date == "") {
			if ($req == "y") {
				$this->messages[] = $this->error_text(1, $field);
				return false;
			} else {
				return true;
			}
		} else {
			$date_parts = explode("-", $date);
			$month = $date_parts[1];
			if ($version == "eu") {
				$pattern = "/^(0?[1-9]|[1-2][0-9]|3[0-1])[-](0?[1-9]|1[0-2])[-](19|20)[0-9]{2}$/";
				$day = $date_parts[0];
				$year = $date_parts[2];
			} else {
				$pattern = "/^(19|20)[0-9]{2}[-](0?[1-9]|1[0-2])[-](0?[1-9]|[1-2][0-9]|3[0-1])$/";
				$day = $date_parts[2];
				$year = $date_parts[0];
			}
			if (preg_match($pattern, $date) && checkdate(intval($month), intval($day), $year)) {
				return true;
			} else {
				$this->messages[] = $this->error_text(10, $field);
				return false;
			}
		}
	}
	
	/*
	private function check_check_box($req_value, $field, $element) {
		if (empty($_REQUEST[$element])) {
			$this->messages[] = $this->error_text(12, $field);
			return false;
		} else {
			if (!empty($req_value)) {
				if ($req_value != $_REQUEST[$element]) {
					$this->messages[] = $this->error_text(12, $field);
					return false;
				} else {
					return true;
				}
			} else {
				return true;
			}
		}
	}
	*/
	
	/*
	private function check_decimal($dec_val, $field, $decimals = 2, $req = "n") {
		if ($dec_val == "") {
			if ($req == "y") {
				$this->messages[] = $this->error_text(1, $field);
				return false;
			} else {
				return true;
			}
		} else {
			$pattern = "/^[-]*[0-9][0-9]*\.[0-9]{".$decimals."}$/";
			if (preg_match($pattern, $dec_val)) {
				return true;
			} else {
				$this->messages[] = $this->error_text(12, $field);
				return false;
			}
		}
	}
	*/

	
	/*
	function check_email($mail_address, $field, $req = "y") {
		if ($mail_address == "") {
			if ($req == "y") {
				$this->messages[] = $this->error_text(1, $field);
				return false;
			} else {
				return true;
			}
		} else {
			if (preg_match("/^[0-9a-z]+(([\.\-_])[0-9a-z]+)*@[0-9a-z]+(([\.\-])[0-9a-z-]+)*\.[a-z]{2,}$/i", $mail_address)) {
				return true;
			} else {
				$this->messages[] = $this->error_text(11, $field);
				return false;
			}
		}
	}
	function check_html_tags($value, $field) {
		if (preg_match("/<[a-z1-6]+((\s[a-z]{2,}=['\"]?(.*)['\"]?)+(\s?\/)?)*>(<\/[a-z1-6]>)?/i", $value)) {
			$this->messages[] = $this->error_text(15, $field);
			return false;
		} else {
			return true;
		}
	}
	*/
	
	public function create_msg($break_elem = "<br />") {
		$the_msg = "";
		krsort($this->messages); // modified in 1.35
		reset($this->messages); 
		foreach ($this->messages as $value) {
			$the_msg .= $value.$break_elem."\n";
		}
		//print $the_msg;
		return $the_msg;
	}
	
	private function error_text($num, $fieldname = "") {
		$fieldname = str_replace("_", " ", $fieldname);
		
		$msg[0] = 'Please correct the following error(s):';
		$msg[1] = 'The <b>'.$fieldname.'</b> field is empty.';
		$msg[10] = "The date in field <b>".$fieldname."</b> is not valid.";
		$msg[11] = "The e-mail address in field <b>".$fieldname."</b> is not valid.";
		$msg[12] = "The value in field <b>".$fieldname."</b> is not valid.";
		$msg[13] = "The text in field <b>".$fieldname."</b> is too long.";
		$msg[14] = "The url in field <b>".$fieldname."</b> is not valid.";
		$msg[15] = "There is html code in field <b>".$fieldname."</b>, this is not allowed.";
		$msg[16] = 'The year in <b>'.$fieldname.'</b> has an incorrect value.';
		$msg[17] = 'No selection was made in the <b>'.$fieldname.'</b> field. You must make a selection from those available.';
		$msg[18] = 'The <b>passwords</b> do not match. Please ensure both are the same.';
		$msg[19] = 'You are required to select one of the <b>'.$fieldname.'</b> radio buttons.';
		$msg[20] = 'You cannot delete all the categories from the <b>'.$fieldname.'</b> field. If you want to delete one, add one first so there are at least two.';
		
		return $msg[$num];
	}
}
?>