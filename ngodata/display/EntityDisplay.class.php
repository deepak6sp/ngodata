<?php
require_once '../interfaces/iValueObject.class.php';
/**
 * Class created automatically by the NGOData system.
 * DO NOT add to or change this class directly.
 * This is a business model class!
 */
class EntityDisplay {

	// sql factory - injected
	private $sqlFactory = null;


	// error display - injected
	private $errorDisplay;

	public function __construct(iSQLFactory $sqlf) {
		$this->setSQLFactory($sqlf);
	}

	/**
	 * Method: setSQLFactory(iSQLFactory $dbf)
	 * Method created automatically by NGOData system
	 * Do NOT alter this method.
	 * Inject the required factory - injected by the lookup table display elements
	 */
	private function setSQLFactory(iSQLFactory $sqlf) {
		$this->sqlFactory = $sqlf;
	}

	/**
	 * Method: getSQLFactory()
	 * Method created automatically by NGOData system
	 * Do NOT alter this method.
	 */
	public function getSQLFactory() {
		return $this->sqlFactory;
	}

	/**
	 * Method: setErrorDisplay($ed)
	 * Method created automatically by NGOData system
	 * Do NOT alter this method.
	 * Inject the required factory - needed so the methods can inject the db where required
	 */
	public function setErrorDisplay($ed) {
		$this->errorDisplay = $ed;
	}

	/**
	 * Method: displayReview(iValueObject $vo)
	 * Method created automatically by NGOData system
	 * Validation not required for this method - no data changed/added
	 * Do NOT alter this method.
	 */
	public function displayReview(iValueObject $vo) {
		// assume headers and etc are alrady in place...
		$text = '';
		$text .= '<div id="entity">'."\n";
		$text .= '<fieldset>'."\n";
		$text .= '<legend>Review - Entity data</legend>'."\n";
		// the data...

		$text .= '<label for="entity_name">Entity name: </label>';
		// get the data...
		$text .= $vo->getDataItem('entity_name');
		$text .= '<br />';
		$text .= '<label for="business_registration_code">Business registration code: </label>';
		// get the data...
		$text .= $vo->getDataItem('business_registration_code');
		$text .= '<br />';
		$text .= '</fieldset>';
		$text .= '</div>';
		return $text;
	}


	/**
	 * Method: DisplayNew(array $errors(may == null))
	 * @param $errors An array of error messages
	 * @param $data The originally submitted data to populate fields with in the case there is an error
	 * Method created automatically by NGOData system
	 * This method is post-validation - check error array.
	 * WE MAY WANT TO INJECT THE DBFactory into the class on instantiation
	 * Do NOT alter this method.
	 */
	public function displayNew($errors = null, $postData = null) {
		$data = null;
		if (!is_null($postData)) $data = $postData->getData();

		$text = '';
		$text .= '<div id="entity">'."\n";
		$text .= '<fieldset>'."\n";
		$text .= '<legend>Enter new Entity details</legend>'."\n";
		$text .= '<p>Required fields are marked with an asterisk (<abbr class="req" title="required">*</abbr>).</p>';
		// display any errors
		if (!is_null($errors)) {
			$text .= '<div class="formerror"><p><img src="/images/error_triangle.jpg" width="16" height="16" hspace="5" alt="Error image">Please check the following and try again:</p><ul>'."\n";
			// Get each error and add it to the error string as a list item.
			foreach ($errors as $field=>$errorMessage) {
				$text .= "<li>$errorMessage</li>"."\n";
			}
			$text .= '</ul></div>'."\n";
		}


		// the data...

		$text .= '<p';
		// need to check for errors here - if there is an error, we need to display any entered text
		if ($this->errorDisplay->isValidationError($errors, 'entity_name')) {
			// get submitted text
			$text .= ' class="formerror"';
		}
		$text .= '>'."\n";
		// no error, so just display
		$text .= '<label for="entity_name">'."\n";
		$text .= '<abbr class="req" title="This is a required field">*</abbr>';
		$text .= 'Entity name';
		$text .= ': </label>'."\n";
		// need to check for errors to determine whether to display data
		if (!is_null($errors)) {
			$text .= '<input type="text" name="entity_name"';
			foreach ($data as $item) {
				foreach ($item as $field=>$value) {
					if ($field ==='entity_name') {
						$text .= ' value="';
						$text .= $this->getFieldValue('entity_name', $item);
						$text .= '"';
					}
				}
			}
			$text .= ' />'."\n";
		} else {
			$text .= '<input type="text" name="entity_name" />';
		}
		$text .= '</p>'."\n";

		$text .= '<p';
		// need to check for errors here - if there is an error, we need to display any entered text
		if ($this->errorDisplay->isValidationError($errors, 'business_registration_code')) {
			// get submitted text
			$text .= ' class="formerror"';
		}
		$text .= '>'."\n";
		// no error, so just display
		$text .= '<label for="business_registration_code">'."\n";
		$text .= '<abbr class="req" title="This is a required field">*</abbr>';
		$text .= 'Business registration code';
		$text .= ': </label>'."\n";
		// need to check for errors to determine whether to display data
		if (!is_null($errors)) {
			$text .= '<input type="text" name="business_registration_code"';
			foreach ($data as $item) {
				foreach ($item as $field=>$value) {
					if ($field ==='business_registration_code') {
						$text .= ' value="';
						$text .= $this->getFieldValue('business_registration_code', $item);
						$text .= '"';
					}
				}
			}
			$text .= ' />'."\n";
		} else {
			$text .= '<input type="text" name="business_registration_code" />';
		}
		$text .= '</p>'."\n";

		$text .= '</fieldset>'."\n";
		$text .= '</div>'."\n";
		return $text;

	}


	/**
	 * Method: DisplayUpdate(NGOValueObjectInterface $data)
	 * Method created automatically by NGOData system
	 * Do NOT alter this method.
	 * @param NGOValueObjectInterface $data Ensure correct type of object is given.
	 */
	public function displayUpdate(iValueObject $vo) {
		$text = '';
		$text .= '<div id="entity">';
		$text .= '<fieldset>';
		$text .= '<legend>Update Entity data</legend>';
		$text .= '<p>Required fields are marked with an asterisk (<abbr class="req" title="required">*</abbr>).</p>';
		// the data...

		$text .= '<label for="entity_name">Entity name';
		// determine whether the field is required or not...
		$text .= '<abbr class="req" title="This is a required field">*</abbr>';
		$text .= ': </label>';
		$text .= '<input type="text" name="entity_name" ';
		$text .= 'value="'.$vo->getData('entity_name').'" />';
		$text .= '<br />';
		$text .= '<label for="business_registration_code">Business registration code';
		// determine whether the field is required or not...
		$text .= '<abbr class="req" title="This is a required field">*</abbr>';
		$text .= ': </label>';
		$text .= '<input type="text" name="business_registration_code" ';
		$text .= 'value="'.$vo->getData('business_registration_code').'" />';
		$text .= '<br />';
		$text .= '</fieldset>';
		$text .= '</div>';
		return $text;
	}


	private function getFieldValue($field, $data) {
		if (isset($data[$field])) return $data[$field];
		return null;
	}


}
?>
