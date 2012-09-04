<?php

/**
 * Class: ErrorDisplay
 * This class handles the display of error meesages on the UI
 * @author Steve Cooke sa_cooke@me.com
 * Date: July 2012
 */
class ErrorDisplayFactory {
	public function __construct() {
		
	}
	
	/**
	 * Method: isValidationError(array $errors, string $field)
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: July 2012
	 * @param array $errors Array of errors and values keyed on the $field.
	 * @param string $field Key of error array.
	 * @return Returns a boolean indicationg whether a validation error is present in $errors 
	 * @desc 
	 */
	public function isValidationError($errors, $field) {
		if (is_null($errors)) return false;
		return array_key_exists($field, $errors);
		//if (array_key_exists($field, $errors)) {
			//return array_key_exists('error', $errors[$field]);
		//}
	}
	
	/**
	 * Method: getErrorValue(array $errors, string $field)
	 * Method created automatically by NGOData system
	 * Do NOT alter this method.
	 * @param array $errors Array of errors and values keyed on the $field.
	 * @param string $field Key of error array.
	 */
	public function getValidationErrorValue($errors, $field) {
		if (is_null($errors)) return '';
		// need to step through the array to find value
		if (array_key_exists($field, $errors)) {
			return $errors[$field]['value'];
		}
		return '';
	}
}
?>