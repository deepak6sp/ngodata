<?php
require_once '../interfaces/iValueObject.class.php';
require_once '../model/TitleFactory.class.php';
/**
 * Class created automatically by the NGOData system.
 * DO NOT add to or change this class directly.
 * This is a business model class!
 */
class UserDisplay {

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
		$text .= '<div id="user">'."\n";
		$text .= '<fieldset>'."\n";
		$text .= '<legend>Review - User data</legend>'."\n";
		// the data...

		$text .= '<label for="username">Username: </label>';
		// get the data...
		$text .= $vo->getDataItem('username');
		$text .= '<br />';
		$text .= '<label for="preferred_name">Preferred name: </label>';
		// get the data...
		$text .= $vo->getDataItem('preferred_name');
		$text .= '<br />';

		// get the item the id is pointing to
		$oFactory = new TitleFactory($this->getSQLFactory());
		// inject the dbfactory
		$dao = $oFactory->getTitleById($vo->getDataItem('title_id'));
		$value = $dao->getValueObject()->getDataItem('title');
		$text .= '<label for="title">Title: </label>';
		// get the data...
		$text .= $value;
		$text .= '<br />';
		$text .= '<label for="firstname">Firstname: </label>';
		// get the data...
		$text .= $vo->getDataItem('firstname');
		$text .= '<br />';
		$text .= '<label for="middlename">Middlename: </label>';
		// get the data...
		$text .= $vo->getDataItem('middlename');
		$text .= '<br />';
		$text .= '<label for="surname">Surname: </label>';
		// get the data...
		$text .= $vo->getDataItem('surname');
		$text .= '<br />';
		$text .= '<label for="user_creation_date">User creation date: </label>';
		// get the data...
		$text .= $vo->getDataItem('user_creation_date');
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
		$text .= '<div id="user">'."\n";
		$text .= '<fieldset>'."\n";
		$text .= '<legend>Enter new User details</legend>'."\n";
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
		if ($this->errorDisplay->isValidationError($errors, 'username')) {
			// get submitted text
			$text .= ' class="formerror"';
		}
		$text .= '>'."\n";
		// no error, so just display
		$text .= '<label for="username">'."\n";
		$text .= '<abbr class="req" title="This is a required field">*</abbr>';
		$text .= 'Username';
		$text .= ': </label>'."\n";
		// need to check for errors to determine whether to display data
		if (!is_null($errors)) {
			$text .= '<input type="text" name="username"';
			foreach ($data as $item) {
				foreach ($item as $field=>$value) {
					if ($field ==='username') {
						$text .= ' value="';
						$text .= $this->getFieldValue('username', $item);
						$text .= '"';
					}
				}
			}
			$text .= ' />'."\n";
		} else {
			$text .= '<input type="text" name="username" />';
		}
		$text .= '</p>'."\n";

		$text .= '<p';
		// need to check for errors here - if there is an error, we need to display any entered text
		if ($this->errorDisplay->isValidationError($errors, 'password')) {
			// get submitted text
			$text .= ' class="formerror"';
		}
		$text .= '>'."\n";
		// no error, so just display
		$text .= '<label for="password">'."\n";
		$text .= '<abbr class="req" title="This is a required field">*</abbr>';
		$text .= 'Password';
		$text .= ': </label>'."\n";
		// need to check for errors to determine whether to display data
		if (!is_null($errors)) {
			$text .= '<input type="text" name="password"';
			foreach ($data as $item) {
				foreach ($item as $field=>$value) {
					if ($field ==='password') {
						$text .= ' value="';
						$text .= $this->getFieldValue('password', $item);
						$text .= '"';
					}
				}
			}
			$text .= ' />'."\n";
		} else {
			$text .= '<input type="text" name="password" />';
		}
		$text .= '</p>'."\n";

		$text .= '<p';
		// need to check for errors here - if there is an error, we need to display any entered text
		if ($this->errorDisplay->isValidationError($errors, 'preferred_name')) {
			// get submitted text
			$text .= ' class="formerror"';
		}
		$text .= '>'."\n";
		// no error, so just display
		$text .= '<label for="preferred_name">'."\n";
				$text .= 'Preferred name';
		$text .= ': </label>'."\n";
		// need to check for errors to determine whether to display data
		if (!is_null($errors)) {
			$text .= '<input type="text" name="preferred_name"';
			foreach ($data as $item) {
				foreach ($item as $field=>$value) {
					if ($field ==='preferred_name') {
						$text .= ' value="';
						$text .= $this->getFieldValue('preferred_name', $item);
						$text .= '"';
					}
				}
			}
			$text .= ' />'."\n";
		} else {
			$text .= '<input type="text" name="preferred_name" />';
		}
		$text .= '</p>'."\n";


		// No need to check for errors here - this is a look-up table.
		// We DO need to check for a previously selected value if there was an error

		// use the factory get all the items - and inject dependencies
		$oFactory = new TitleFactory($this->getSQLFactory());
		// returns an array of Title data access objects
		$daos = $oFactory->getAllTitleData();
		// may need to do somethng here in case of error - but I do not think error will occur here.
		$text .= '<p>'."\n";
		$text .= '<label for="title">'."\n";
		// determine whether the field is required or not...
		;
		$text .= 'Title';
		$text .= ': </label>'."\n";
		// get the data...
		$text .= '<select name="title_id">'."\n";
		foreach ($daos as $dao) {
			$vo = $dao->getValueObject();
			$text .= '<option value="'.$vo->getDataItem('id').'"';
			// need to get the value, and we need the selected value...
			if (!is_null($errors)) {
				if ($dao->getValueObject()->getDataItem('id') == $this->get_title_id($postData)) {
					$text .= ' selected';
				}
			}
			$text .= '>'.$vo->getDataItem('title').'</option>'."\n";
		}
		$text .= '</select>'."\n";
		$text .= '</p>'."\n";
		$text .= '<p';
		// need to check for errors here - if there is an error, we need to display any entered text
		if ($this->errorDisplay->isValidationError($errors, 'firstname')) {
			// get submitted text
			$text .= ' class="formerror"';
		}
		$text .= '>'."\n";
		// no error, so just display
		$text .= '<label for="firstname">'."\n";
		$text .= '<abbr class="req" title="This is a required field">*</abbr>';
		$text .= 'Firstname';
		$text .= ': </label>'."\n";
		// need to check for errors to determine whether to display data
		if (!is_null($errors)) {
			$text .= '<input type="text" name="firstname"';
			foreach ($data as $item) {
				foreach ($item as $field=>$value) {
					if ($field ==='firstname') {
						$text .= ' value="';
						$text .= $this->getFieldValue('firstname', $item);
						$text .= '"';
					}
				}
			}
			$text .= ' />'."\n";
		} else {
			$text .= '<input type="text" name="firstname" />';
		}
		$text .= '</p>'."\n";

		$text .= '<p';
		// need to check for errors here - if there is an error, we need to display any entered text
		if ($this->errorDisplay->isValidationError($errors, 'middlename')) {
			// get submitted text
			$text .= ' class="formerror"';
		}
		$text .= '>'."\n";
		// no error, so just display
		$text .= '<label for="middlename">'."\n";
				$text .= 'Middlename';
		$text .= ': </label>'."\n";
		// need to check for errors to determine whether to display data
		if (!is_null($errors)) {
			$text .= '<input type="text" name="middlename"';
			foreach ($data as $item) {
				foreach ($item as $field=>$value) {
					if ($field ==='middlename') {
						$text .= ' value="';
						$text .= $this->getFieldValue('middlename', $item);
						$text .= '"';
					}
				}
			}
			$text .= ' />'."\n";
		} else {
			$text .= '<input type="text" name="middlename" />';
		}
		$text .= '</p>'."\n";

		$text .= '<p';
		// need to check for errors here - if there is an error, we need to display any entered text
		if ($this->errorDisplay->isValidationError($errors, 'surname')) {
			// get submitted text
			$text .= ' class="formerror"';
		}
		$text .= '>'."\n";
		// no error, so just display
		$text .= '<label for="surname">'."\n";
		$text .= '<abbr class="req" title="This is a required field">*</abbr>';
		$text .= 'Surname';
		$text .= ': </label>'."\n";
		// need to check for errors to determine whether to display data
		if (!is_null($errors)) {
			$text .= '<input type="text" name="surname"';
			foreach ($data as $item) {
				foreach ($item as $field=>$value) {
					if ($field ==='surname') {
						$text .= ' value="';
						$text .= $this->getFieldValue('surname', $item);
						$text .= '"';
					}
				}
			}
			$text .= ' />'."\n";
		} else {
			$text .= '<input type="text" name="surname" />';
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
		$text .= '<div id="user">';
		$text .= '<fieldset>';
		$text .= '<legend>Update User data</legend>';
		$text .= '<p>Required fields are marked with an asterisk (<abbr class="req" title="required">*</abbr>).</p>';
		// the data...

		$text .= '<label for="username">Username';
		// determine whether the field is required or not...
		$text .= '<abbr class="req" title="This is a required field">*</abbr>';
		$text .= ': </label>';
		$text .= '<input type="text" name="username" ';
		$text .= 'value="'.$vo->getData('username').'" />';
		$text .= '<br />';
		$text .= '<label for="password">Password';
		// determine whether the field is required or not...
		$text .= '<abbr class="req" title="This is a required field">*</abbr>';
		$text .= ': </label>';
		$text .= '<input type="text" name="password" ';
		$text .= 'value="'.$vo->getData('password').'" />';
		$text .= '<br />';
		$text .= '<label for="preferred_name">Preferred name';
		// determine whether the field is required or not...
				$text .= ': </label>';
		$text .= '<input type="text" name="preferred_name" ';
		$text .= 'value="'.$vo->getData('preferred_name').'" />';
		$text .= '<br />';

		// get all the items
		$oFactory = new TitleFactory();
		// inject the dbfactory
		$oFactory->setDBFactory($this->dbFactory);
		// returns an array of Title data access objects
		$daos = $oFactory->getAllTitleData();
		$text .= '<label for="title">Title';
				$text .= ': </label>';
		// get the data...
		$text .= '<select>';
		foreach ($daos as $item) {
			
			$text .= '<option value="'.$item->getValueObject()->get_id().'"';
			// need to get the value, and we need the selected value...
			if ($item->getValueObject()->get_id() == $data->get_title_id()) {
				$text .= ' selected ';

			}
			$text .= '>'.$item->getValueObject()->get_Title().'</option>';
		}
		$text .= '</select>';
		$text .= '<br />';
		$text .= '<label for="firstname">Firstname';
		// determine whether the field is required or not...
		$text .= '<abbr class="req" title="This is a required field">*</abbr>';
		$text .= ': </label>';
		$text .= '<input type="text" name="firstname" ';
		$text .= 'value="'.$vo->getData('firstname').'" />';
		$text .= '<br />';
		$text .= '<label for="middlename">Middlename';
		// determine whether the field is required or not...
				$text .= ': </label>';
		$text .= '<input type="text" name="middlename" ';
		$text .= 'value="'.$vo->getData('middlename').'" />';
		$text .= '<br />';
		$text .= '<label for="surname">Surname';
		// determine whether the field is required or not...
		$text .= '<abbr class="req" title="This is a required field">*</abbr>';
		$text .= ': </label>';
		$text .= '<input type="text" name="surname" ';
		$text .= 'value="'.$vo->getData('surname').'" />';
		$text .= '<br />';
		$text .= '</fieldset>';
		$text .= '</div>';
		return $text;
	}


	private function get_title_id(iPostData $pd) {
		$data = $pd->getData();
		foreach ($data as $item) {
			if (is_array($item)) {
				if (isset($item['title_id'])) {
					return $item['title_id'];
				}
			}
		}
	}


	private function getFieldValue($field, $data) {
		if (isset($data[$field])) return $data[$field];
		return null;
	}


}
?>
