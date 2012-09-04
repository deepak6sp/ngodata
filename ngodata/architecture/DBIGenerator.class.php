<?php
// DATABASES
// - each entity will have their own copy of the database - store database name in entity table
// - each new entity will trigger a new copy of the database;
// - so, need a complete database creation script
// The entity table will be part of the User framwork - separate from the data network
//
// FOR NOW - leave as the same database and key all data on the entity key, whilst
//	leaving the database name in entity table

class DBIGenerator {
	// name of class
	private $classname;
	
	// the path to create object file
	private $path;
	
	// The names of the fields to be processed by the DBIGenerator for each class and table
	private $fieldnames;
	
	//
	private $table;
	
	// the objects should know which database they belong to
	private $dbname;
	
	//
	private $dbfields;
	
	CONST USER = 0;
	CONST DATA = 1;
	CONST GUEST = 2;
	
	
	public function __construct() {
	//public function __construct($name = 'DEFAULTNAME.class.php', 
			//$path = 'DEFAULTPATH/', $fieldNames = array(),  $db) {
		//$this->name = $name;
		//$this->path = $path;
		//$this->options = $options;
		//$this->table = strtolower($name);
		// the objects should know which database they belong to!
		//if ($db = self::DATA) $this->db = 'ngodata';
		//else if ($db = self::USER) $this->db = 'ngouser';
		//$this->db = $db;
	}
	
	
	/**************************************************/
	/***********  INJECT DEPENENCIES  *****************/
	/**************************************************/
	public function setDBName($dbname) {
		//if ($dbname == self::DATA) $this->db = 'ngodata';
		//else if ($dbname == self::USER) $this->db = 'ngouser';
		$this->dbname = $dbname;
	}
	
	public function setFilePath($path) {
		$this->path = $path;
	}
	
	public function setFieldNames($fieldnames) {
		$this->fieldnames = $fieldnames;
	}
	
	public function setDBFields($dbFields) {
		$this->dbfields = $dbFields;
	}
	
	public function setNames($classname) {
		$name = '';
		if (is_array($classname)) {
			for ($i=0; $i<count($classname); $i++) {
				//$name .= $classname[$i].'_';
				$name .= $classname[$i];
			}
			// remove last character...
			//$name = substr($name, 0, -1);
			$this->classname = $name;
			$this->table = strtolower($name);
		} else {
			$this->classname = $classname;
			$this->table = strtolower($classname);
		}
	}
	
	
	
	
	//#########################################//
	//#######   VALUE OBJECT methods   ########//
	//#########################################//
	
	//#########################//
	// Create the VALUE OBJECT
	public function createVO() {
		$text ='';
		$text .= '<?php'."\n";
		$text .= '// not decided on this yet...'."\n";
		$text .= 'require_once \'../interfaces/iValueObject.class.php\';'."\n";
		$text .= 'require_once \'../interfaces/iQueryObject.class.php\';'."\n";
		$text .= 'require_once \'../database/iPostData.class.php\';'."\n";
		$text .= 'require_once \'../database/PostData.class.php\';'."\n";
		//$text .= 'require_once \'../model/'.$this->classname.'QO.class.php\';'."\n";
		$text .= '/**'."\n";
		$text .= ' * Value object: '.$this->classname."\n";
		$text .= ' * @author ngodata <ngodata@me.com>'."\n";
		$text .= ' * DO NOT ALTER - this class is created automatically by the NGOData system.'."\n";
		$text .= ' * Access this object ONLY through a factory object.'."\n";
		$text .= ' */'."\n";
		$text .= ''."\n";
		$text .= 'class '.$this->classname.'VO implements iValueObject{'."\n";
		$text .= '//class '.$this->classname.' {'."\n";
		// don't create fields here - going to use an array to hold the object data
		$text .= '	// Attributes'."\n";
		$text .= '	private $qo;'."\n";
		$text .= ''."\n";
		// attributes for data
		foreach ($this->fieldnames as $field) {
			$text .= '	private $'.$field.' = null;'."\n";
		}
		//$text = substr($text, 0, -2);
		//$text .= ''."\n";
		//$text .= '	// array of possible fields to check data requests against - possibly redundent, but I do not care!'."\n";
		//$text .= '	private $fields = array('."\n";
		//foreach ($this->fieldnames as $field) {
			//$text .= '	private $'.$field.' = null;'."\n";
		///}
		//$text .= ''."\n";
		//$text .= ''."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		// constructor
		$text .= '	// May remove paramenter so dependency injection occurs via a setter'."\n";
		$text .= '	//public function __construct(iPostData $data) {'."\n";
		$text .= '	public function __construct() {'."\n";
		$text .= '		//$this->setData($data->getData());'."\n";
		//$text .= '		$this->qo = new '.$this->name.'QO();'."\n";
		//$text .= '		'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		$text .= '	// attribute access - no mutator'."\n";
		$text .= '	public function getDataItem($field) {'."\n";
		$text .= '		switch ($field) {'."\n";
		foreach ($this->fieldnames as $field) {
			$text .= '			case \''.$field.'\':'."\n";
			$text .= '				return $this->'.$field.';'."\n";
			$text .= '				break;'."\n";
			$text .= ''."\n";
		}
		$text .= '		}'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		/*
		foreach ($this->fieldnames as $field) {
			$text .= ''."\n";
			$text .= '	// attribute access - no mutator'."\n";
			$text .= '	public function get_'.$field.'() {'."\n";
			$text .= '		return $this->'.$field.';'."\n";
			$text .= '	}'."\n";
			$text .= ''."\n";
			
			// look for foreign keys - _id
			//$idTextArray = null;
		}
		*/
		
		// set the query object
		$text .= '	public function setQueryObject(iQueryObject $qo) {'."\n";
		$text .= '		$this->qo = $qo;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= '	public function getQueryObject() {'."\n";
		$text .= '		if (isset($this->qo)) {'."\n";
		$text .= '			return $this->qo;'."\n";
		$text .= '		}'."\n";
		$text .= '		return false;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		
		$text .= '	/**'."\n";
		$text .= '	 * Method: setWhere($where)'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		/*
		$text .= '	public function setWhere(array $where) {'."\n";
		$text .= '		$this->getQueryObject()->setWhere($where);'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		*/
		
		$text .= '	/**'."\n";
		$text .= '	 * Method: getWhere()'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		/*
		$text .= '	public function getWhere() {'."\n";
		$text .= '		return $this->getQueryObject()->getWhere();'."\n";
		$text .= '	}'."\n";
		//$text .= ''."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		*/
		
		//$text .= ''."\n";
		$text .= '	/**'."\n";
		$text .= '	 * Method: setId($id)'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function setId($id) {'."\n";
		$text .= '		$this->id = $id;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		//public function getTablename();
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		/*
		$text .= '	public function getTablename() {'."\n";
		$text .= '		return $this->qo->getTablename();'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		*/
		
		
		//public function getFields();
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * The $fields array is created and set by the DBIGenerator.'."\n";
		$text .= '	 */'."\n";
		/*
		$text .= '	public function getFields() {'."\n";
		$text .= '		return $this->qo->getFields();'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		*/
		
		// setData()
		//$text .= '	// create with data array'."\n";
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function setData(iPostData $pd) {'."\n";
		$text .= '		$data = $pd->getData();'."\n";
		$text .= '		if (empty($data)) return;'."\n";
		$text .= ''."\n";
		$text .= '		foreach ($data as $item) {'."\n";
		$text .= '			foreach ($item as $key=>$value) {'."\n";
		$text .= '			//foreach ($data as $key=>$value) {'."\n";
		$text .= '				switch ($key) {'."\n";
		
		foreach ($this->fieldnames as $field) {
			$text .= '					case ($key == \''.$field.'\'):'."\n";
			$text .= '						$this->'.$field.' = $value;'."\n";
			$text .= '						break;'."\n";
			$text .= ''."\n";
		}
		$text .= '				}'."\n";
		$text .= '			}'."\n";
		$text .= '		}'."\n";
		$text .= '		return;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		// create the getData() method
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function getData() {'."\n";
		//$text .= '		return $this->data;'."\n";
		//$text .= ''."\n";
		$text .= '		$data = array();'."\n";
		
		foreach ($this->fieldnames as $field) {
			$text .= '		$item = array(\''.$field.'\'=>$this->getDataItem(\''.$field.'\'));'."\n";
			$text .= '		$data[] = $item;'."\n";
			/*
			// for transforming a null id to a '0'
			if ($field = 'id') {
				$text .= '		if (is_null($this->getDataItem(\'id\'))) {'."\n";
				$text .= '			$item = array(\''.$field.'\'=>\'0\');'."\n";
				$text .= '		} else {'."\n";
				$text .= '			$item = array(\''.$field.'\'=>$this->getDataItem(\''.$field.'\'));'."\n";
				$text .= '		}'."\n";
				$text .= '		$data[] = $item;'."\n";
				
			} else {
				$text .= '		$item = array(\''.$field.'\'=>$this->getDataItem(\''.$field.'\'));'."\n";
				$text .= '		$data[] = $item;'."\n";
			}
			*/
		}
		
		/*
		foreach ($this->fieldnames as $field) {
			$text .= '		$item = array(\''.$field.'\'=>$this->get_'.$field.'());'."\n";
			$text .= '		$data[] = $item;'."\n";
			$text .= ''."\n";
		}
		*/
		$text .= '		$pd = new PostData($data);'."\n";
		$text .= '		return $pd;'."\n";
		$text .= '	}'."\n";
		
		// thinking of adding a generic getIndex method... 
		//$text .= ''."\n";
		//$text .= '	public function getIndex()'."\n";
		$text .= '}'."\n";
		$text .= '?>'."\n";
		//$text .= ''."\n";
		
		// write contents to class file
		$fp = fopen($this->path.$this->classname.'VO.class.php',"w") or die ('Error creating file');
		fwrite($fp, $text);
		fclose($fp);
		//print $text;
		return TRUE;
	}
	
	
	//#########################//
	// Create the QUERY OBJECT
	//#########################//
	public function createQO() {
		$text = '';
		$text .= '<?php'."\n";
		$text .= 'require_once \'../interfaces/iQueryObject.class.php\';'."\n";
		$text .= ''."\n";
		$text .= '/**'."\n";
		$text .= ' * Query Object: '.$this->classname.'QO'."\n";
		$text .= ' * This is a helper class - holds field names, tablename, and database name for the entity.'."\n";
		$text .= ' * This object DOES NOT hold data.'."\n";
		$text .= ' * @author ngodata <ngodata@me.com>'."\n";
		$text .= ' * DO NOT ALTER - this class is created automatically by the NGOData system.'."\n";
		$text .= ' * Access this object ONLY through a factory object.'."\n";
		$text .= ' */'."\n";
		$text .= ''."\n";
		// start the class
		$text .= 'class '.$this->classname.'QO implements iQueryObject {'."\n";
		$text .= '	// legal fields array, and type of each field for binding purposes'."\n";
		$text .= '	private $fields = array(';
		
		foreach ($this->dbfields as $field=>$data) {
			foreach ($data as $name=>$type) {
				if ($name != 'display' && $name != 'valid') {
					$name = str_replace(',', '_', $name);
					$text .= '\''.$name.'\'=>\'';
					$text .= $this->getFieldType($type).'\',';
					print '<pre>Field name: '.$name.'; field type: '.$type.'</pre>';
				}
			}
		}	
		
		/*
		foreach ($this->fieldnames as $field) {
			$text .= '\''.$field.'\'=>\'';
			// get field type - s - string, i - int, b - blob (packets), d - double
			//$type = $this->getFieldType($field);
			$text .= $this->getFieldType($field).'\',';
		}
		*/
		
		// remove trailing comma...
		$text = substr($text, 0, -1);
		$text .= ');'."\n";
		$text .= ''."\n";
		
		// also need insert fields...
		$text .= '	// legal insert fields array, and type of each field for binding purposes'."\n";
		$text .= '	private $userDataFields = array(';
		foreach ($this->dbfields as $field=>$item) {
			if (array_key_exists('display', $item)) {
				//echo '<pre>This is a display field: '.print_r($item).'</pre>';
				foreach ($item as $name=>$type) {
					if ($name != 'display' && $name != 'valid') {
						$name = str_replace(',', '_', $name);
						$text .= '\''.$name.'\'=>\'';
						$text .= $this->getFieldType($type).'\',';
						print '<pre>Display field: '.$name.'; field type: '.$type.'</pre>';
					}
				}
			}
		}
		// remove trailing comma...
		$text = substr($text, 0, -1);
		$text .= ');'."\n";
		$text .= ''."\n";
		
		
		$text .= '	// table name - for automated query preparation'."\n";
		$text .= '	private $tablename = \''.$this->table.'\';'."\n";
		$text .= ''."\n";
		$text .= '	// where parameters - for automated query preparation'."\n";
		$text .= '	private $where = array();'."\n";
		$text .= ''."\n";
		$text .= '	// The database the object data is in'."\n";
		$text .= '	private $dbname = \''.$this->dbname.'\';'."\n";
		$text .= ''."\n";
		
		// create constructor
		$text .= '	public function __construct() {'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * '."\n";
		$text .= '	 */'."\n";
		$text .= '	public function getFields() {'."\n";
		$text .= '		return $this->fields;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * @param array $args Holds the parameters for constructing the where clause of the query'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function getUserDataFields() {'."\n";
		$text .= '		return $this->userDataFields;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		$text .= '	public function getTablename() {'."\n";
		$text .= '		return $this->tablename;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		$text .= '	public function getDBName() {'."\n";
		$text .= '		return $this->dbname;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		$text .= '	public function getWhere() {'."\n";
		$text .= '		return $this->where;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * @param array $args Holds the parameters for constructing the where clause of the query'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function setWhere(array $where) {'."\n";
		$text .= '		$this->where = $where;'."\n";
		$text .= '	}'."\n";
		$text .= '}'."\n";
		//$text .= ''."\n";
		
		// write contents to class file
		$fp = fopen($this->path.$this->classname.'QO.class.php',"w") or die ('Error creating file');
		fwrite($fp, $text);
		fclose($fp);
		//print $text;
		return TRUE;
	}
	
	//###############################//
	// Create the DATA ACCESS OBJECT
	//###############################//
	public function createDAO() {
		//echo 'Here!';
		// create class member definitions
		$text = '';
		$text .= '<?php'."\n";
		$text .= 'require_once \'../interfaces/iValueObject.class.php\';'."\n";
		$text .= 'require_once \'../interfaces/iDataAccessObject.class.php\';'."\n";
		$text .= 'require_once \'../factories/ExceptionFactory.class.php\';'."\n";
		$text .= 'require_once \'../database/iSQLFactory.class.php\';'."\n";
		$text .= 'require_once \'../exceptions/'.$this->classname.'Exception.class.php\';'."\n";
		$text .= 'require_once \'../messages/'.$this->classname.'Message.class.php\';'."\n";
		$text .= ''."\n";
		$text .= '/**'."\n";
		$text .= ' * Data Access Object: '.$this->classname.'DAO'."\n";
		$text .= ' * This is the Gateway object for '.$this->classname.'. See p.144 PofEAA.'."\n";
		$text .= ' * @author ngodata <ngodata@me.com>'."\n";
		$text .= ' * DO NOT ALTER - this class is created automatically by the NGOData system.'."\n";
		$text .= ' * Access this object ONLY through a factory object.'."\n";
		$text .= ' */'."\n";
		$text .= ''."\n";
		// start the class
		$text .= 'class '.$this->classname.'DAO implements iDataAccessObject {'."\n";
		
		// don't create fields here - going to use an array to hold the object data
		//$text .= '	// field data - associative array keyed on field name'."\n";
		$text .= '	// the value object - includes the query object'."\n";
		$text .= '	private $valueObject = null;'."\n";
		$text .= ''."\n";
		
		$text .= '	// SQL factory'."\n";
		$text .= '	private $sqlFactory;'."\n";
		$text .= ''."\n";
		
		//$text .= '	// DB factory - inject this, NOT to be created by the object'."\n";
		//$text .= '	private $dbFactory = null;'."\n";
		//$text .= ''."\n";
		//$text .= '	// '."\n";
		//$text .= ''."\n";
		$text .= ''."\n";
		
		// create constructor
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * This object may be constructed with no data'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function __construct(iSQLFactory $sqlf) {'."\n";
		$text .= '		$this->setSQLFactory($sqlf);'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		
		// create setData method
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function setValueObject(iValueObject $valueObject) {'."\n";
		$text .= '		$this->valueObject = $valueObject;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		
		// create getData method
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function getValueObject() {'."\n";
		$text .= '		return $this->valueObject;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		
		// create the save function
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function saveObject() {'."\n";
		$text .= '		$data = $this->getSQLFactory()->doSave($this->getValueObject());'."\n";
		//$text .= '		echo \'<pre>Inside saveObject... the id is: \';'."\n";
		//$text .= '		print_r($this->getValueObject()->getDataItem(\'id\'));'."\n";
		//$text .= '		echo \'</pre>\';'."\n";
		
		//$text .= '		if ($this->getValueObject()->getDataItem(\'id\') == \'0\') {'."\n";
		//$text .= '			return $this->insertObject();'."\n";
		//$text .= '		} else {'."\n";
		//$text .= '			return $this->updateObject();'."\n";
		//$text .= '		}'."\n";
		
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		
		// create insert function
		$text .= '	/**'."\n";
		$text .= '	 * Method: insertObject()'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * Perhaps inject the db/sql dependency in the call to the method?'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function insertObject() {'."\n";
		$text .= '		$data = $this->getSQLFactory()->doSave($this->getValueObject());'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		
		// create update function
		$text .= '	/**'."\n";
		$text .= '	 * Method: updateObject()'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function updateObject() {'."\n";
		$text .= '		// need to set the where clause'."\n";
		$text .= '		$id = $this->valueObject->getDataItem(\'id\');'."\n";
		$text .= '		$where[] = array(\'id\'=>$id);'."\n";
		$text .= '		$this->valueObject->getQueryObject()->setWhere($where);'."\n";
		$text .= ''."\n";
		$text .= '		//$sql = $this->getSQLFactory()->prepUpdateStatement($this->valueObject);'."\n";
		$text .= '		return $this->getDBFactory()->doUpdate($this->valueObject);'."\n";
		$text .= ''."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		// doSelect method
		$text .= '	/**'."\n";
		$text .= '	 * Method: doSelect()'."\n";
		$text .= '	 * Not sure why there is a select method - should be handled by the factory'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		/*
		$text .= '	public function doSelect() {'."\n";
		$text .= '		$id = $this->valueObject->getFieldData(\'id\');'."\n";
		$text .= '		$where[] = array(\'id\'=>$id);'."\n";
		$text .= '		$this->valueObject->setWhere($where);'."\n";
		$text .= '		$data = $this->getSQLFactory()->doSelect($this->valueObject);'."\n";
		$text .= '		$objectData = array();'."\n";
		$text .= '		if (!is_null($data)) {'."\n";
		$text .= '			foreach ($data as $item) {'."\n";
		$text .= '				$objectData[] = array($item);'."\n";
		$text .= '			}'."\n";
		$text .= '		}'."\n";
		$text .= '		return $objectData;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		*/
		
		// create delete function
		$text .= '	/**'."\n";
		$text .= '	 * Method: deleteObject()'."\n";
		$text .= '	 * May need to change delete...'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function deleteObject() {'."\n";
		$text .= '		// To be implemented'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		// lazy load sqlFactory
		$text .= '	/**'."\n";
		$text .= '	 * Method: getSQLFactory()'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		
		$text .= '	private function getSQLFactory() {'."\n";
		$text .= '		return $this->sqlFactory;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		
		$text .= '	/**'."\n";
		$text .= '	 * Method: setSQLFactory()'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * The SQLFactory is to be injected by creation process'."\n";
		$text .= '	 */'."\n";
		
		$text .= '	public function setSQLFactory(iSQLFactory $sqlf) {'."\n";
		$text .= '		$this->sqlFactory = $sqlf;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		//$text .= ''."\n";
		
		
		
		// lazy load dbFactory
		//$text .= '	/**'."\n";
		//$text .= '	 * Method: getDBFactory()'."\n";
		//$text .= '	 * Method created automatically by NGOData system'."\n";
		//$text .= '	 * DBFactory is to be injected by the creation process'."\n";
		//$text .= '	 */'."\n";
		//$text .= '	private function getDBFactory() {'."\n";
		//$text .= '		return $this->dbFactory;'."\n";
		//$text .= '	}'."\n";
		//$text .= ''."\n";
		
		
		
		// lazy load dbFactory
		//$text .= '	/**'."\n";
		//$text .= '	 * Method: setDBFactory()'."\n";
		//$text .= '	 * - lazy load the dbFactory'."\n";
		//$text .= '	 * Method created automatically by NGOData system'."\n";
		//$text .= '	 * DBFactory is to be injected by the creation process'."\n";
		//$text .= '	 */'."\n";
		
		//$text .= '	public function setDBFactory($dbFactory) {'."\n";
		//$text .= '		$this->dbFactory = $dbFactory;'."\n";
		//$text .= '	}'."\n";
		//$text .= ''."\n";
		
		
	
		// close class...
		$text .= '}'."\n";
		$text .= '?>'."\n";
		
		// write contents to class file
		$fp = fopen($this->path.$this->classname.'DAO.class.php',"w") or die ('Error creating file');
		fwrite($fp, $text);
		fclose($fp);
		//print $text;
		return TRUE;
	}
	
	
	
	//###################################//
	// create SQL Handler
	//###################################//
	public function createSQLHandler() {
		$text = '';
		$text .= '<?php'."\n";
		$text .= 'require_once \'../database/iDBConfigReader.class.php\';'."\n";
		$text .= ''."\n";
		$text .= '/**'."\n";
		$text .= ' * Class: '.$this->classname."\n";
		$text .= ' * This is the SQL handler for '.$this->classname."\n";
		$text .= ' * - this provides access to the dbs, and issues various queries using '.$this->classname.' objects.'."\n";
		//$text .= ' * DO NOT forget to inject the dbFactory into the objects.'."\n";
		$text .= ' * @author ngodata <ngodata@me.com>'."\n";
		$text .= ' * DO NOT ALTER - this class is created automatically by the NGOData system.'."\n";
		$text .= ' */'."\n";
		$text .= ''."\n";
		
		// start the class
		$text .= 'class '.$this->classname.'SQLHandler {'."\n";
		$text .= '	// The live db connection'."\n";
		$text .= '	protected $connection = NULL;'."\n";
		$text .= ''."\n";
		$text .= '	public function __construct(iDBConfigReader $conf) {'."\n";
		$text .= '		$host = $conf->getHost();'."\n";
		$text .= '		$user = $conf->getUser();'."\n";
		$text .= '		$pass = $conf->getPassword();'."\n";
		$text .= '		$db   = $conf->getDatabase();'."\n";
		$text .= '		'."\n";
		$text .= '		$connection = new MySQLi($host, $user, $pass, $db);'."\n";
		
		$text .= '		// on error - need to check return value...'."\n";
		$text .= '		if (mysqli_connect_error()) {'."\n";
		$text .= '			throw new RuntimeException(mysqli_connect_error(), mysqli_connect_errno());'."\n";
		$text .= '		}'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		$text .= '	public function __destruct() {'."\n";
		$text .= '		$this->connection->close();'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		$text .= '}'."\n";
		$text .= ''."\n";
		
		
		// write contents to class file
		$fp = fopen('../model/'.$this->classname.'SQLHandler.class.php',"w") or die ('Error creating file');
		fwrite($fp, $text);
		fclose($fp);
		//print $text;
		return TRUE;
	}
	
	
	
	//###################################//
	// create data access object FACTORY
	//###################################//
	public function createFactory() {
		$text = '';
		$text .= '<?php'."\n";
		//$text .= 'require_once \'../model/'.$this->classname.'DAO.class.php\';'."\n";
		$text .= 'require_once \'../model/'.$this->classname.'VO.class.php\';'."\n";
		$text .= 'require_once \'../model/'.$this->classname.'QO.class.php\';'."\n";
		$text .= 'require_once \'../model/'.$this->classname.'DAO.class.php\';'."\n";
		$text .= 'require_once \'../model/'.$this->classname.'ValidationHandler.class.php\';'."\n";
		$text .= 'require_once \'../interfaces/iValueObject.class.php\';'."\n";
		$text .= 'require_once \'../interfaces/iQueryObject.class.php\';'."\n";
		$text .= 'require_once \'../interfaces/iDataAccessObject.class.php\';'."\n";
		$text .= 'require_once \'../database/iPostData.class.php\';'."\n";
		$text .= 'require_once \'../database/SQLFactory.class.php\';'."\n";
		//$text .= 'require_once \'../factories/NGODataDBFactory.class.php\';'."\n";
		
		$text .= ''."\n";
		$text .= '/**'."\n";
		$text .= ' * Factory: '.$this->classname."\n";
		$text .= ' * This is the factory for '.$this->classname."\n";
		$text .= ' * - this creates and sets '.$this->classname.' objects.'."\n";
		$text .= ' * DO NOT forget to inject the dbFactory into the objects.'."\n";
		$text .= ' * @author ngodata <ngodata@me.com>'."\n";
		$text .= ' * DO NOT ALTER - this class is created automatically by the NGOData system.'."\n";
		$text .= ' */'."\n";
		$text .= ''."\n";
		// start the class
		$text .= 'class '.$this->classname.'Factory {'."\n";
		
		$text .= '	// SQL factory'."\n";
		$text .= '	private $sqlFactory = null;'."\n";
		$text .= ''."\n";
		//$text .= '	// DB factory'."\n";
		//$text .= '	private $dbFactory = null;'."\n";
		//$text .= ''."\n";
		//$text .= ''."\n";
		
		// create constructor
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * This object may be constructed with no data'."\n";
		$text .= '	 * Do we need to inject the db and sql factories?'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function __construct(iSQLFactory $sqlf) {'."\n";
		$text .= '		//$this->setDBFactory($dbf);'."\n";
		$text .= '		$this->setSQLFactory($sqlf);'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
				$text .= '	/**'."\n";
		$text .= '	 * Method: setSQLFactory()'."\n";
		$text .= '	 * Injects the sqlFactory attribute.'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		$text .= '	private function setSQLFactory(iSQLFactory $sqlf) {'."\n";
		$text .= '		$this->sqlFactory = $sqlf;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		// lazy load sqlFactory
		$text .= '	/**'."\n";
		$text .= '	 * Method: getSQLFactory()'."\n";
		//$text .= '	 * Lazy loads the sqlFactory attribute.'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function getSQLFactory() {'."\n";
		$text .= '		return $this->sqlFactory;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		//$text .= '	/**'."\n";
		//$text .= '	 * Method: setDBFactory()'."\n";
		//$text .= '	 * Injects the dbFactory attribute.'."\n";
		//$text .= '	 * Method created automatically by NGOData system'."\n";
		//$text .= '	 */'."\n";
		//$text .= '	public function setDBFactory(iDBFactory $dbf) {'."\n";
		//$text .= '		$this->dbFactory = $dbf;'."\n";
		//$text .= '	}'."\n";
		//$text .= ''."\n";
		
		
		//$text .= '	/**'."\n";
		//$text .= '	 * Method: getDBFactory()'."\n";
		//$text .= '	 * @return Returns the injected dbFactory attribute.'."\n";
		//$text .= '	 * Method created automatically by NGOData system'."\n";
		//$text .= '	 */'."\n";
		//$text .= '	private function getDBFactory() {'."\n";
		//$text .= '		return $this->dbFactory;'."\n";
		//$text .= '	}'."\n";

		
		
		// create object by id
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		//$text .= '	 * This object may be constructed with no data'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function create'.$this->classname.'ById($id) {'."\n";
		$text .= '		$qo = new '.$this->classname.'QO();'."\n";
		$text .= '		// set the where'."\n";
		$text .= '		$item = array(\'id\'=>$id);'."\n";
		$text .= '		$qo->setWhere($item);'."\n";
		$text .= '		$vo = new '.$this->classname.'VO();'."\n";
		$text .= '		$vo->setId($id);'."\n";
		$text .= '		$vo->setQueryObject($qo);'."\n";
		$text .= '		$dao = new '.$this->classname.'DAO();'."\n";
		$text .= '		$dao->setValueObject($vo);'."\n";
		$text .= '		// No data set apart from id'."\n";
		$text .= '		return $dao;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		// create<classname> with $data
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function create'.$this->classname.'(iPostData $data) {'."\n";
		$text .= '		echo \'<pre>\';'."\n";
		$text .= '		print \'in create'.$this->classname.': \';'."\n";
		$text .= '		echo \'</pre>\';'."\n";
		
		$text .= '		echo \'<pre>The ssql factory in createTitle: \';'."\n";
		$text .= '		print_r($this->getSQLFactory());'."\n";
		$text .= '		echo \'</pre>\';'."\n";
		$text .= '		$qo = new '.$this->classname.'QO();'."\n";
		$text .= '		$vo = new '.$this->classname.'VO();'."\n";
		$text .= '		$vo->setData($data);'."\n";
		$text .= '		$vo->setQueryObject($qo);'."\n";
		$text .= '		$dao = new '.$this->classname.'DAO($this->getSQLFactory());'."\n";
		//$text .= '		$sqlf = new NGODataSQLFactory();'."\n";
		//$text .= '		$dao->setSQLFactory($this->sqlFactory);'."\n";
		//$text .= '		$dao->setSQLFactory($this->getSQLFactory());'."\n";
		//$text .= '		$dao->setDBFactory($this->getDBFactory());'."\n";
		//$text .= '		//$dao->setDBFactory($this->getDBFactory());'."\n";
		$text .= '		$dao->setValueObject($vo);'."\n";
		$text .= '		return $dao;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		// getAll<classname>Data()
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function getAll'.$this->classname.'Data() {'."\n";
		$text .= '		// select the data from the database'."\n";
		//$text .= '		$dbf = $this->getDBFactory();'."\n";
		//$text .= '		$data = NULL;'."\n";
		$text .= '		// prepare the sql statement'."\n";
		$text .= '		$vo = new '.$this->classname.'VO();'."\n";
		$text .= '		$qo = new '.$this->classname.'QO();'."\n";
		$text .= '		$vo->setQueryObject($qo);'."\n";
		//$text .= '		//$sqlf = $this->getSQLFactory();'."\n";
		//$text .= '		//$sql = $sqlf->prepSelectStatement($vo);'."\n";
		//$text .= ''."\n";
		$text .= '		$results = $this->doSelect($vo);'."\n";
		$text .= '		// iterate through the data to create the value objects and daos'."\n";
		$text .= '		$daos = array();'."\n";
		$text .= '		if ($results !== null) { // if data is returned...'."\n";
		$text .= '			foreach ($results as $key=>$data) {'."\n";
		$text .= '				$vo = new '.$this->classname.'VO();'."\n";
		$text .= '				$pd = new PostData($data);'."\n";
		$text .= '				$vo->setData($pd);'."\n";
		$text .= '				$qo = new '.$this->classname.'QO();'."\n";
		$text .= '				$vo->setQueryObject($qo);'."\n";
		$text .= '				$dao = new '.$this->classname.'DAO($this->getSQLFactory());'."\n";
		$text .= '				$dao->setValueObject($vo);'."\n";
		$text .= '				$daos[] = $dao;'."\n";
		$text .= '			}'."\n";
		$text .= '		}'."\n";
		//$text .= '		// returns an array of vo objects'."\n";
		$text .= '		return $daos;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		// get<object>ById($id)
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * @return Returns a Data access object containing the selected data'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function get'.$this->classname.'ById($id) {'."\n";
		//$text .= '		$dbf = $this->getDBFactory();'."\n";
		$text .= '		$vo = new '.$this->classname.'VO();'."\n";
		$text .= '		$vo->setId($id);'."\n";
		$text .= '		// also set QO, and set the where to the id'."\n";
		$text .= '		$qo = new '.$this->classname.'QO();'."\n";
		$text .= '		// set the where'."\n";
		$text .= '		$item = array(\'id\'=>$id);'."\n";
		$text .= '		$qo->setWhere($item);'."\n";
		$text .= '		$vo->setQueryObject($qo);'."\n";
		$text .= '		// create the select sql'."\n";
		//$text .= '		$sqlf = $this->getSQLFactory();'."\n";
		//$text .= '		$sql = $sqlf->prepSelectStatement($vo);'."\n";
		//$text .= '		echo \'in get'.$this->classname.'ById($id)\'.$sql;'."\n";
		//$text .= '		// this is not going to work - pass in the database name'."\n";
		//$text .= '		$data = $dbf->doSelect($qo->getDBName(), $sql);'."\n";
		$text .= '		$data = $this->getSQLFactory()->doSelect($vo);'."\n";
		//$text .= '		echo \'<pre>...\';'."\n";
		//$text .= '		print_r($data);'."\n";
		//$text .= '		echo \'</pre>\';'."\n";
		$text .= '		foreach ($data as $item) {'."\n";
		$text .= '			$vo->setData($item);'."\n";
		$text .= '		}'."\n";
		$text .= '		$dao = new '.$this->classname.'DAO();'."\n";
		$text .= '		$dao->setValueObject($vo);'."\n";
		$text .= '		return $dao;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		//*********************************************//
		//********** validate the object data *********//
		//*********************************************//
		
		/*
		// Pattern to use for display and validation...
		// See: http://stackoverflow.com/questions/2975306/best-practice-to-deal-with-form-processing
		if POST_REQUEST:
			ERRORS = VALIDATE_FORM()
			if ERRORS IS EMPTY:
				PROCESS_REQUEST
				REDIRECT TO <Successful URL>
				
		DISPLAY_FORM(ERRORS)
		
		These are the possible scenarios:

		* Get request at form url:
			POST_REQUEST is false and so you just display the form with no errors (so a normal empty form)
		* Form submission with errors:
			POST_REQUEST is true, you validate the form. In this case some errors will be returned in the ERRORS
			variable (probably an array). The ERRORS variable is not empty so you just display the form with the
			error messages displayed
		* Correct form submission:
			POST_REQUEST is true and you validate the form. Since this time the form is valid the ERRORS variable
			would be empty and you perform the redirect to the <Successful URL> which will display the successful
			message to the user.

		Using this pattern you prevent that the user to send again form data when refreshing the page after a
		correct form submission.

		A good way to handle errors in form display is to have the VALIDATE_FORM() function returning an
		associative array of errors where each key is the name of the field and the corresponding item is
		an array of error messages.
		
		
		Another take
		------------
		As you may know from your research, POST-redirect-GET looks like this:
		
		- The client gets a page with a form.
		- The form POSTs to the server.
		- The server performs the action, and then redirects to another page.
		- The client follows the redirect.

		For example, say we have this structure of the website:
		- /posts (shows a list of posts and a link to "add post")
			- /<id> (view a particular post)
			- /create (if requested with the GET method, returns a form posting to itself; if it's a POST request,
			creates the post and redirects to the /<id> endpoint)
			
		- /posts itself isn't really relevant to this particular pattern, so I'll leave it out.

		- /posts/<id> might be implemented like this:
			- Find the post with that ID in the database.
			- Render a template with the content of that post.

		- /posts/create might be implemented like this:
			- If the request is a GET request:
				- Show an empty form with the target set to itself and the method set to POST.
			- If the request is a POST request:
				- Validate the fields.
				- If there are invalid fields, show the form again with errors indicated.
				- Otherwise, if all fields are valid:
					- Add the post to the database.
					- Redirect to /posts/<id> (where <id> is returned from the call to the database)

		*/

		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * Validates the data a user may enter.'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function validate($validationArray) {'."\n";
		$text .= '		// I do not need to know whether all fields to be validated are present in the array...'."\n";
		
		$text .= '		$rvh = new RegistrationValidationHandler($validationArray);'."\n";
		$text .= '		$rvh->validate();'."\n";
		$text .= '		return $rvh->getMessages();'."\n";
		$text .= ''."\n";
		reset($this->dbfields);
		foreach ($this->dbfields as $field=>$data) {
			// type tells us whether validation is required
			// if the definition calls for validation of a field, validate it!
			if (array_key_exists('valid', $data)) {
				// this line is not going to work because DISPLAY is now one or more of r/u/n
				if ($this->isRequiredFieldType($data[key($data)])) {
					$text .= '		//'.key($data).' needs validating.'."\n";
					//echo '<pre> ';
					if (array_key_exists('valid', $data)) $text .= '		//'.$data['valid'].' are required tests.'."\n";
					//echo '</pre>';
				}
			}
		}
		$text .= '	}'."\n";
		
		$text .= ''."\n";
		$text .= ''."\n";
		
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * Process $_POST array.'."\n";
		$text .= '	 * @return Returns a properly formatted array of elements for validation.'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function processPostArray(array $data) {'."\n";
		$text .= '		$valArray = null;'."\n";
		$text .= '		foreach($data as $key=>$item) {'."\n";
		$text .= '			$valArray[$key] = array(\'value\'=>$item);'."\n";
		$text .= '		};'."\n";
		$text .= '		return $valArray;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		
		/*******************************/
		/****  Database operations  ****/  
		/*******************************/
		// doInsert
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * Inserts $obj into persistent storage.'."\n";
		$text .= '	 * - Assumes $obj DOES NOT exist in persistent storage.'."\n";
		$text .= '	 */'."\n";
		$text .= '	private function doInsert(iDataAccessObject $dao) {'."\n";
		$text .= '		// get dbtype from $obj'."\n";
		//$text .= '		$db = $this->getDB($dbt);'."\n";
		//$text .= '		return $this->db->insert($vo);'."\n";
		$text .= '		return null;'."\n";
		$text .= ''."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		
		// doSelect
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * Updates $obj in persistent storage.'."\n";
		$text .= '	 * - Assumes $obj exists in persistent storage.'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function doSelect(iValueObject $obj) {'."\n";
		$text .= '		// The SQLFactory should handle all the sql stuff, and return the data from the database'."\n";
		$text .= '		$data = $this->getSQLFactory()->doSelect($obj);'."\n";
		$text .= '		$objectData = array();'."\n";
		$text .= '		if (!is_null($data)) {'."\n";
		$text .= '			foreach ($data as $item) {'."\n";
		$text .= '				$objectData[] = array($item);'."\n";
		$text .= '			}'."\n";
		$text .= '		}'."\n";
		$text .= ''."\n";
		//$text .= '		'."\n";
		//$text .= ''."\n";
		$text .= '		return $objectData;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";

		
		// doUpdate
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * Updates $obj in persistent storage.'."\n";
		$text .= '	 * - Assumes $obj exists in persistent storage.'."\n";
		$text .= '	 */'."\n";
		$text .= '	private function doUpdate(iDataAccessObject $obj) {'."\n";
		$text .= '		// get dbtype from $obj'."\n";
		$text .= '		// need to get the sql factory'."\n";
		$text .= '		$sqlf = new NGODataSQLFactory();'."\n";
		$text .= '		$sql = $sqlf->prepUpdateStatement($vo);'."\n";
		$text .= '		$db = $this->getDB($dbt);'."\n";
		$text .= '		return $db->update($vo);'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		// doDelete
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * Updates $obj in persistent storage.'."\n";
		$text .= '	 * - Assumes $obj exists in persistent storage.'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function doDelete('.$this->classname.'DAO $obj) {'."\n";
		$text .= '		// do some magic...'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
	
	
		$text .= ''."\n";
		$text .= '}'."\n";
		
		
		// write contents to class file
		$fp = fopen('../model/'.$this->classname.'Factory.class.php',"w") or die ('Error creating file');
		fwrite($fp, $text);
		fclose($fp);
		//print $text;
		return TRUE;
	}
	
	
	
	public function createValidationHandler() {
		// we need fields and validation rules
		// we send one field and associated rules to the validation class
		
		$text = '';
		$text .= '<?php'."\n";
		//$text .= 'require_once \'../interfaces/iValueObject.class.php\';'."\n";
		//$text .= 'require_once \'../interfaces/iQueryObject.class.php\';'."\n";
		$text .= 'require_once \'../classes/Validation.class.php\';'."\n";
		$text .= 'require_once \'../classes/ValidationData.class.php\';'."\n";
		
		$text .= ''."\n";
		$text .= '/**'."\n";
		$text .= ' * ValidationHandler: '.$this->classname."\n";
		$text .= ' * This is the validation handler for '.$this->classname."\n";
		$text .= ' * - this validates and keeps track of error messages for '.$this->classname.' objects.'."\n";
		$text .= ' * @author ngodata <ngodata@me.com>'."\n";
		$text .= ' * DO NOT ALTER - this class is created automatically by the NGOData system.'."\n";
		$text .= ' */'."\n";
		$text .= ''."\n";
		// start the class
		$text .= 'class '.$this->classname.'ValidationHandler {'."\n";
		
		// we need an associative array, each item indexed on field, and with the required validation rules as children
		// So:
		// array()
		//   |
		//   -- item[validation rule, validation rule,...]
		
		reset($this->dbfields);
		$text .= '	// this holds the details of the field validation for '.$this->classname.' objects.'."\n";
		$text .= '	private $validationRules = array('."\n";
		foreach ($this->dbfields as $field=>$data) {
			if (array_key_exists('valid', $data)) {
				
				// don't create validation if we don't need to in this context
				$field = $data[key($data)]; // gives us the field name
				$validationRules = $data['valid'];
				
				if ($this->isRequiredFieldType($data[key($data)])) {
					$text .= '		\''.key($data).'\'=>array(';
					$rules = explode('|', $validationRules);
					for ($i=0; $i<count($rules); $i++) {
						$text .= '\''.$rules[$i].'\', ';
					}
					$text = substr($text, 0, -2);
					$text .= '),'."\n";
				}
			}
		}
		// remove the closing comma...
		$text = substr($text, 0, -2);
		//rtrim($text, ',');
		// close the array
		$text .= "\n".'		);'."\n";
		$text .= ''."\n";
		
		$text .= '	// Array to hold the required validation objects'."\n";
		$text .= '	private $validationObjects = null;'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		$text .= '	// Array to hold any generated messages'."\n";
		$text .= '	private $messages = null;'."\n";

		$text .= ''."\n";
		$text .= ''."\n";
		$text .= ''."\n";
				
		// create constructor
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * This object may be constructed with no data'."\n";
		$text .= '	 * Each time this object is invoked a new graph of validation objects is also created'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function __construct(iPostData $pd) {'."\n";
		$text .= '		$this->setData($pd);'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		$text .= '	private function setData(iPostData $pd) {'."\n";
		$text .= '		$data = $pd->getData();'."\n";
		$text .= '		$dataForValidation = array();'."\n";
		$text .= '		foreach ($data as $item) {'."\n";
		$text .= '			foreach ($item as $field=>$data) {'."\n";
		$text .= '				if ($this->IsInValidationArray($field)) {'."\n";
		$text .= '					$v = new ValidationData($field, $data);'."\n";
		$text .= '					$dataForValidation[] = $v;'."\n";
		$text .= '				}'."\n";
		$text .= '			}'."\n";
		$text .= '		}'."\n";
		$text .= '		$this->validationObjects = $dataForValidation;'."\n";
		$text .= '		return $this->validationObjects;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		
		$text .= '	private function IsInValidationArray($field) {'."\n";
		$text .= '		if (array_key_exists($field, $this->validationRules)) return true;'."\n";
		$text .= '		return false;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		
		$text .= '	private function getValidationObjects() {'."\n";
		$text .= '		return $this->validationObjects;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		
		$text .= '	public function validate() {'."\n";
		$text .= '		// Reset the messages array...'."\n";
		$text .= '		$this->messages = null;'."\n";
		$text .= ''."\n";
		$text .= '		// Find the field, and call the relevant validation method(s)'."\n";
		$text .= '		$vos = $this->getValidationObjects();'."\n";
		$text .= '		$validationRules = null;'."\n";
		$text .= '		$validate = new Validation();'."\n";
		$text .= '		foreach ($vos as $vo) {'."\n";
		$text .= '			$rules = $this->getRulesForField($vo->getField());'."\n";
		$text .= '			// $rules is an array...;'."\n";
		$text .= '			//print_r($rules);'."\n";
		$text .= '			for ($i=0; $i<count($rules); $i++) {'."\n";
		$text .= '				$message = $validate->validateField($vo, $rules[$i]);'."\n";
		$text .= '				if (!is_null($message)) $this->setMessage($vo->getField(), $message);'."\n";
		$text .= '			}'."\n";
		$text .= '		}'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		$text .= '	private function getRulesForField($field) {'."\n";
		$text .= '		foreach ($this->validationRules as $key=>$value) {'."\n";
		$text .= '			if ($key === $field) {'."\n";
		$text .= '				return $value;'."\n";
		$text .= '			}'."\n";
		$text .= '		}'."\n";
		$text .= '		return false;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		
		$text .= '	private function setMessage($field, $message) {'."\n";
		$text .= '		$this->messages[$field] = $message;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		
		$text .= '	public function getMessages() {'."\n";
		$text .= '		return $this->messages;'."\n";
		$text .= '	}'."\n";
		
		
		$text .= '}'."\n";
		$text .= '?>'."\n";
		
		
		
		// write contents to class file
		$fp = fopen('../model/'.$this->classname.'ValidationHandler.class.php',"w") or die ('Error creating file');
		fwrite($fp, $text);
		fclose($fp);
		//print $text;
		return TRUE;
	}
	
	
	
	//##################################################//
	//#######   EXCEPTION and MESSAGE methods   ########//
	//##################################################//
	
	// create exception and message
	public function createGeneralException() {
		$path = '../exceptions/';
		//$text = ''."\n";
		$text = '<?php'."\n";
		//$text .= 'require_once \'NGODataException.class.php\';'."\n";
		//$text .= 'class '.$name.'Exception extends NGODataException {'."\n";
		$text .= 'class '.$this->classname.'Exception extends Exception {'."\n";
		$text .= '}'."\n";
		$text .= '?>'."\n";
		//$text .= ''."\n";
		
		// write contents to class file
		$fp = fopen($path.$this->classname.'Exception.class.php',"w") or die ('Error creating file');
		fwrite($fp, $text);
		fclose($fp);
		//print $text;
		return TRUE;
	}
	

	public function createMessage() {
		$path = '../messages/';
		//$text = ''."\n";
		$text = '<?php'."\n";
		//$text .= 'require_once \'NGODataException.class.php\';'."\n";
		//$text .= 'class '.$name.'FieldException extends NGODataException {'."\n";
		$text .= 'class '.ucfirst($this->classname).'Message extends Exception {'."\n";
		$text .= '}'."\n";
		$text .= '?>'."\n";
		//$text .= ''."\n";
		
		// write contents to class file
		$fp = fopen($path.ucfirst($this->classname).'Message.class.php',"w") or die ('Error creating file');
		fwrite($fp, $text);
		fclose($fp);
		//print $text;
		return TRUE;
	}
	
	
	
	//########################################//
	//#######   JOIN OBJECT methods   ########//
	//########################################//
	
	
	//#####################//
	// create Join Objects
	public function createJDAO($name1, $name2) {
		$text = '';
		$text .= '<?php'."\n";
		//$text .= 'require_once \'../model/'.$this->name.'VO.class.php\';'."\n";
		//$text .= 'require_once \'../model/'.$this->name.'QO.class.php\';'."\n";
		//$text .= 'require_once \'../model/'.$this->name.'Factory.class.php\';'."\n";
		$text .= 'require_once \'../factories/ExceptionFactory.class.php\';'."\n";
		$text .= 'require_once \'../exceptions/'.$name1.$name2.'Exception.class.php\';'."\n";
		//$text .= 'require_once \'../exceptions/'.$this->name.'FieldException.class.php\';'."\n";
		$text .= 'require_once \'../messages/'.$name1.$name2.'Message.class.php\';'."\n";
		$text .= 'require_once \'../factories/NGODataSQLFactory.class.php\';'."\n";
		$text .= ''."\n";
		$text .= '/**'."\n";
		$text .= ' * Data Access Join Object: '.$name1.$name2.'DAJO'."\n";
		$text .= ' * This is the join object between '.$name1.' and '.$name2."\n";
		$text .= ' * @author ngodata <ngodata@me.com>'."\n";
		$text .= ' * DO NOT ALTER - this class is created automatically by the NGOData system.'."\n";
		$text .= ' * Access this join object ONLY through a factory object.'."\n";
		$text .= ' */'."\n";
		$text .= ''."\n";
		// start the class
		$text .= 'class '.$name1.$name2.'JDAO {'."\n";
		
		// don't create fields here - going to use an array to hold the object data
		//$text .= '	// field data - associative array keyed on field name'."\n";
		$text .= '	// the value object - includes the query object'."\n";
		$text .= '	private $valueObject = NULL;'."\n";
		$text .= ''."\n";
		
		$text .= '	// SQL factory'."\n";
		$text .= '	private $sqlFactory = NULL;'."\n";
		$text .= ''."\n";
		
		$text .= '	// DB factory - inject this, NOT to be created by the object'."\n";
		$text .= '	private $dbFactory = NULL;'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		// create constructor
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * This object may be constructed with no data'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function __construct() {'."\n";
		//$text .= '		$this->qo = new '.$this->name.'QO();'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		
		// get $name1
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * This object may be constructed with no data'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function get'.$name1.'Id() {'."\n";
		$text .= ''."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		// get $name2
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * This object may be constructed with no data'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function get'.$name2.'Id() {'."\n";
		$text .= ''."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * Set the database factory by injection.'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function setDBFactory($dbFactory) {'."\n";
		$text .= '		$this->dbFactory = $dbFactory;'."\n";
		$text .= '	}'."\n";
		
		
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * @return The database factory .'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function getDBFactory() {'."\n";
		$text .= '		return $this->dbFactory;'."\n";
		$text .= '	}'."\n";
		
		
		$text .= '}'."\n";
		//$text .= ''."\n";
		
		
		// write contents to class file
		$fp = fopen($this->path.$name1.$name2.'JDAO.class.php',"w") or die ('Error creating file');
		fwrite($fp, $text);
		fclose($fp);
		//print $text;
		return TRUE;
	}
	
	
	//############################//
	// create JOIN OBJECT FACTORY
	
	public function createJoinObjectFactory($name1, $name2) {
		$text = '';
		$text .= '<?php'."\n";
		$text .= 'require_once \'../factories/ExceptionFactory.class.php\';'."\n";
		$text .= 'require_once \'../exceptions/'.$name1.$name2.'Exception.class.php\';'."\n";
		$text .= 'require_once \'../messages/'.$name1.$name2.'Message.class.php\';'."\n";
		$text .= 'require_once \'../factories/NGODataSQLFactory.class.php\';'."\n";
		$text .= ''."\n";
		$text .= '/**'."\n";
		$text .= ' * Join Object Factory: '.$name1.$name2.'Factory'."\n";
		$text .= ' * This is the factory dealing with join objects between '.$name1.' and '.$name2."\n";
		$text .= ' * @author ngodata <ngodata@me.com>'."\n";
		$text .= ' * DO NOT ALTER - this class is created automatically by the NGOData system.'."\n";
		//$text .= ' * Access this join object ONLY through a factory object.'."\n";
		$text .= ' */'."\n";
		$text .= ''."\n";
		// start the class
		$text .= 'class '.$name1.$name2.'Factory {'."\n";
		
		// don't create fields here - going to use an array to hold the object data
		//$text .= '	// field data - associative array keyed on field name'."\n";
		$text .= '	// the value object - includes the query object'."\n";
		$text .= '	private $valueObject = NULL;'."\n";
		$text .= ''."\n";
		
		$text .= '	// SQL factory'."\n";
		$text .= '	private $sqlFactory = NULL;'."\n";
		$text .= ''."\n";
		$text .= '	// DB factory'."\n";
		$text .= '	private $dbFactory = NULL;'."\n";
		//$text .= ''."\n";
		//$text .= ''."\n";
		
		// create constructor
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * This object may be constructed with no data'."\n";
		$text .= '	 */'."\n";
		$text .= ''."\n";
		$text .= '	public function __construct() {'."\n";
		//$text .= '		$this->qo = new '.$this->name.'QO();'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		// get by $name1
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * This object may be constructed with no data'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function get'.$name2.'sBy'.$name1.'Id($'.strtolower($name1).'Id) {'."\n";
		$text .= ''."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		// get by $name2
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * This object may be constructed with no data'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function get'.$name1.'sBy'.$name2.'Id($'.strtolower($name2).'Id) {'."\n";
		$text .= ''."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		$text .= '}'."\n";
		
		// write contents to class file
		$fp = fopen('../model/'.$name1.$name2.'Factory.class.php',"w") or die ('Error creating file');
		fwrite($fp, $text);
		fclose($fp);
		//print $text;
		return TRUE;
	}
	
	
	//##############################//
	// Create the JOIN VALUE OBJECT
	
	public function createJVO($name1, $name2) {
		$text ='';
		$text .= '<?php'."\n";
		$text .= 'require_once \'../interfaces/NGOValueObjectInterface.class.php\';'."\n";
		$text .= 'require_once \'../model/'.$name1.$name2.'QO.class.php\';'."\n";
		$text .= '/**'."\n";
		$text .= ' * Join value object: '.$name1.$name2."\n";
		$text .= ' * @author ngodata <ngodata@me.com>'."\n";
		$text .= ' * DO NOT ALTER - this class is created automatically by the NGOData system.'."\n";
		$text .= ' * Access this object ONLY through a factory object.'."\n";
		$text .= ' */'."\n";
		$text .= ''."\n";
		$text .= 'class '.$name1.$name2.'JVO implements NGOValueObjectInterface{'."\n";
		// don't create fields here - going to use an array to hold the object data
		$text .= '	// Attributes'."\n";
		$text .= '	private $qo;'."\n";
		$text .= ''."\n";
		
		// attributes for data
		$text .= '	// attribute - '.$name1."\n";
		$text .= '	private $'.strtolower($name1).'Id;'."\n";
		$text .= ''."\n";
		$text .= '	// attribute - '.$name2."\n";
		$text .= '	private $'.strtolower($name2).'Id;'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		
		$text .= ''."\n";
		
		// constructor
		$text .= '	public function __construct() {'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		$text .= ''."\n";
		$text .= '	// attribute access - no mutator, use setData()'."\n";
		$text .= '	public function get'.$name1.'Id() {'."\n";
		$text .= '		return $this->'.strtolower($name1).';'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= '	// attribute access - no mutator, use setData()'."\n";
		$text .= '	public function get'.$name2.'Id() {'."\n";
		$text .= '		return $this->'.strtolower($name2).';'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		// set the query object
		$text .= '	public function setQueryObject(NGODataQueryObjectInterface $qo) {'."\n";
		$text .= '		$this->qo = $qo;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= '	public function getQueryObject() {'."\n";
		$text .= '		if (isset($this->qo)) {'."\n";
		$text .= '			return $this->qo;'."\n";
		$text .= '		}'."\n";
		$text .= '		return false;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		//$text .= ''."\n";
		$text .= '	// mutator for object id'."\n";
		$text .= '	//public function setId($id) {'."\n";
		$text .= '		//$this->id = $id;'."\n";
		$text .= '	//}'."\n";
		$text .= ''."\n";
		
		//public function getTablename();
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * The $tablename field is created and set by the DBIGenerator.'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function getTablename() {'."\n";
		$text .= '		return $this->qo->getTablename();'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		//public function getFields();
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * The $fields array is created and set by the DBIGenerator.'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function getFields() {'."\n";
		$text .= '		return $this->qo->getFields();'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		// setData()
		//$text .= '	// create with data array'."\n";
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function setData(array $data) {'."\n";
		$text .= '		foreach ($data as $item) {'."\n";
		$text .= '			foreach ($item as $key=>$value) {'."\n";
		$text .= '				switch ($key) {'."\n";
		
		$text .= '					case ($key == \''.strtolower($name1).'_id\'):'."\n";
		$text .= '						$this->'.strtolower($name1).'Id = $value;'."\n";
		$text .= '						break;'."\n";
		$text .= ''."\n";
		$text .= '					case ($key == \''.strtolower($name2).'_id\'):'."\n";
		$text .= '						$this->'.strtolower($name2).'Id = $value;'."\n";
		$text .= '						break;'."\n";
		$text .= ''."\n";
		
		$text .= '				}'."\n";
		$text .= '			}'."\n";
		$text .= '		}'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		// create the getData() method
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function getData() {'."\n";
		//$text .= '		return $this->data;'."\n";
		//$text .= ''."\n";
		$text .= '		$data = array();'."\n";
		
		$text .= '		$item = array(\''.strtolower($name1).'_id\'=>$this->get'.strtolower($name1).'Id());'."\n";
		$text .= '		$data[] = $item;'."\n";
		$text .= ''."\n";
			
		$text .= '		$item = array(\''.strtolower($name2).'_id\'=>$this->get'.strtolower($name2).'Id());'."\n";
		$text .= '		$data[] = $item;'."\n";
		$text .= ''."\n";
	
		$text .= '		return $data;'."\n";
		$text .= '	}'."\n";
		
		
		$text .= '}'."\n";
		$text .= '?>'."\n";
		//$text .= ''."\n";
		
		// write contents to class file
		$fp = fopen($this->path.$name1.$name2.'JVO.class.php',"w") or die ('Error creating file');
		fwrite($fp, $text);
		fclose($fp);
		//print $text;
		return TRUE;
	}
	
	
	
	//#########################//
	// Create the QUERY OBJECT
	
	public function createJQO($name1, $name2) {
		$text = '';
		$text .= '<?php'."\n";
		$text .= 'require_once \'../interfaces/NGODataQueryObjectInterface.class.php\';'."\n";
		$text .= ''."\n";
		$text .= '/**'."\n";
		$text .= ' * Query Object: '.$name1.$name2.'QO'."\n";
		$text .= ' * This is a helper class - holds field names, tablename, and database name for the entity.'."\n";
		$text .= ' * This object DOES NOT hold data.'."\n";
		$text .= ' * @author ngodata <ngodata@me.com>'."\n";
		$text .= ' * DO NOT ALTER - this class is created automatically by the NGOData system.'."\n";
		$text .= ' * Access this object ONLY through a factory object.'."\n";
		$text .= ' */'."\n";
		$text .= ''."\n";
		// start the class
		$text .= 'class '.$name1.$name2.'JQO implements NGODataQueryObjectInterface {'."\n";
		
		
		$text .= '	// legal fields array'."\n";
		$text .= '	private $fields = array(\''.strtolower($name1).'_id\', \''.strtolower($name2).'_id\');'."\n";
		$text .= ''."\n";
		
		$text .= '	// table name - for automated query preparation'."\n";
		$text .= '	private $tablename = \''.strtolower($name1).'_'.strtolower($name2).'\';'."\n";
		$text .= ''."\n";
		$text .= '	// where parameters - for automated query preparation'."\n";
		$text .= '	private $where = array();'."\n";
		$text .= ''."\n";
		$text .= '	// The database the object data is in'."\n";
		$text .= '	private $dbname = \''.$this->dbname.'\';'."\n";
		$text .= ''."\n";
		
		// create constructor
		$text .= '	public function __construct() {'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= '	public function getFields() {'."\n";
		$text .= '		return $this->fields;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= '	public function getTablename() {'."\n";
		$text .= '		return $this->tablename;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		$text .= '	public function getDBName() {'."\n";
		$text .= '		return $this->dbname;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		$text .= '	public function getWhere() {'."\n";
		$text .= '		return $this->where;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= '	/**'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * @param array $args Holds the parameters for constructing the where clause of the query'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function setWhere(array $where) {'."\n";
		$text .= '		$this->where = $where;'."\n";
		$text .= '	}'."\n";
		$text .= '}'."\n";
		//$text .= ''."\n";
		
		// write contents to class file
		$fp = fopen($this->path.$name1.$name2.'JQO.class.php',"w") or die ('Error creating file');
		fwrite($fp, $text);
		fclose($fp);
		//print $text;
		return TRUE;
	}
	
	
	
	//############################################//
	//#######   CREATE DATABASE methods   ########//
	//############################################//
	
	/**
	 * Method: createTableSQL
	 * @author Steve Cooke <sa_cooke@me.com>
	 * This is used by the architecture to create the sql for the tables defined by the model definition
	 */
	public function createTableSQL($tablename, $fields, $primaryKey=null) {
		//$sql =  'drop table if exists \''.$tablename.'\'';
		echo '<pre> in DBIGenerator->createTableSQL...tablename: '.$tablename;
		print_r($fields);
		echo '</pre>';

		$sql = 'create table '.$tablename.' ('."\n";
		
		foreach ($fields as $field=>$data) {
			foreach ($data as $name=>$type) {
				if ($name != 'display' && $name != 'valid') {
					$sql .= $name.' '.$this->getType($type)."\n";
				}
			}
		}

		if (!is_null($primaryKey)) {
			$sql .= 'primary key('.$primaryKey.')'."\n";
		} else {
			// remove last comma...
			$sql = substr($sql, 0, -2);
		}
		$sql .= ')';
		
		return $sql;
	}
	
	
	/**
	 * Method: dropTableSql
	 * @author Steve Cooke <sa_cooke@me.com>
	 * This is used by the architecture to create the sql for dropping the defined table prior
	 * to the architecture creating the table:
	 * 		- the architecture may be run multiple times
	 */
	public function dropTableSQL($tablename) {
		return 'drop table if exists '.$tablename;
	}
	
	
	/**
	 * Method: getType
	 * @author Steve Cooke <sa_cooke@me.com> 
	 * Defines how field attributes, used in the model definition, are to be translated for the table creation.
	 * Any additional items used in the model definition NEED to be added here.
	 */
	private function getType($type) {
		if ($type == 'v8') return 'varchar(8),';
		if ($type == 'v8n') return 'varchar(8) not null,';
		
		if ($type == 'v16') return 'varchar(16),';
		if ($type == 'v16n') return 'varchar(16) not null,';
		
		if ($type == 'v32') return 'varchar(32),';
		if ($type == 'v32n') return 'varchar(32) not null,';
		
		if ($type == 'v64') return 'varchar(64),'; 
		if ($type == 'v64n') return 'varchar(64) not null,'; 
		
		if ($type == 'v128') return 'varchar(128),';
		if ($type == 'v128n') return 'varchar(128) not null,';
		
		if ($type == 'v256') return 'varchar(256),';
		if ($type == 'v256n') return 'varchar(256) not null,';
		
		if ($type == 'v512') return 'varchar(512),';
		if ($type == 'v512n') return 'varchar(512) not null,';
		
		if ($type == 'id') return 'int(11) not null auto_increment,';
		if ($type == 'int') return 'int(11),';
		if ($type == 'intn') return 'int(11) not null,';
		
		if ($type == 'dt') return 'datetime,';
		if ($type == 'dtn') return 'datetime not null,';
		
		if ($type == 'b') return 'blob,';
		if ($type == 'bn') return 'blob not null,';
		
		if ($type == 't') return 'text,';
		if ($type == 'tn') return 'text not null,';
	}
	
	
	

	//###########################################//
	//#######   CREATE DISPLAY methods   ########//
	//###########################################//
	
	public function createDisplayClass($fields, $classname) {
		// three display types:
		// - new object (so all fields empty)
		// - display existing data:
		//   - review (just display data - to review and/or delete)
		//   - update (display existing data)
		
		echo '<pre>';
		print_r($fields);
		echo '</pre>';
		
		$text = '';
		$text .= '<?php'."\n";
		$text .= 'require_once \'../interfaces/iValueObject.class.php\';'."\n";
		
		// get any extra requires...THIS IS NEEDED! DO NOT DELETE
		foreach ($fields as $field=>$data) {
			foreach ($data as $name=>$type) {
				if (substr_count($name, '_id') > 0) {
					$idTextArray = explode('_id', $name);
					//$text .= 'require_once \'../model/'.ucfirst($idTextArray[0]).'DOA.class.php\';'."\n";
					$text .= 'require_once \'../model/'.ucfirst($idTextArray[0]).'Factory.class.php\';'."\n";
				}
			}
		}
		
		$text .= '/**'."\n";
		$text .= ' * Class created automatically by the NGOData system.'."\n";
		$text .= ' * DO NOT add to or change this class directly.'."\n";
		$text .= ' * This is a business model class!'."\n";
		$text .= ' */'."\n";
		$text .= 'class '.ucfirst($classname).'Display {'."\n";
		$text .= ''."\n";
		//$text .= '	// database factory - injected'."\n";
		//$text .= '	private $dbFactory = null;'."\n";
		//$text .= ''."\n";
		$text .= '	// sql factory - injected'."\n";
		$text .= '	private $sqlFactory = null;'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		$text .= '	// error display - injected'."\n";
		$text .= '	private $errorDisplay;'."\n";
		$text .= ''."\n";
		//$text .= '	public function __construct(idbFactory $dbf, iSQLFactory $sqlf) {'."\n";
		$text .= '	public function __construct(iSQLFactory $sqlf) {'."\n";
		$text .= '		$this->setSQLFactory($sqlf);'."\n";
		//$text .= '		$this->setDBFactory($dbf);'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		// setDBFactory
		//$text .= '	/**'."\n";
		//$text .= '	 * Method: setDBFactory(iDBFactory $dbf)'."\n";
		//$text .= '	 * Method created automatically by NGOData system'."\n";
		//$text .= '	 * Do NOT alter this method.'."\n";
		//$text .= '	 * Inject the required factory - injected by the lookup table display elements'."\n";
		//$text .= '	 */'."\n";
		//$text .= '	private function setDBFactory(iDBFactory $dbf) {'."\n";
		//$text .= '		$this->dbFactory = $dbf;'."\n";
		//$text .= '	}'."\n";
		//$text .= ''."\n";
		
		
		
		// getDBFactory
		//$text .= '	/**'."\n";
		//$text .= '	 * Method: getDBFactory()'."\n";
		//$text .= '	 * Method created automatically by NGOData system'."\n";
		//$text .= '	 * Do NOT alter this method.'."\n";
		//$text .= '	 */'."\n";
		//$text .= '	public function getDBFactory() {'."\n";
		//$text .= '		return $this->dbFactory;'."\n";
		//$text .= '	}'."\n";
		//$text .= ''."\n";

		
		
		// setSQLFactory
		$text .= '	/**'."\n";
		$text .= '	 * Method: setSQLFactory(iSQLFactory $dbf)'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * Do NOT alter this method.'."\n";
		$text .= '	 * Inject the required factory - injected by the lookup table display elements'."\n";
		$text .= '	 */'."\n";
		$text .= '	private function setSQLFactory(iSQLFactory $sqlf) {'."\n";
		$text .= '		$this->sqlFactory = $sqlf;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
				// getSQLFactory
		$text .= '	/**'."\n";
		$text .= '	 * Method: getSQLFactory()'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * Do NOT alter this method.'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function getSQLFactory() {'."\n";
		$text .= '		return $this->sqlFactory;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";


		
		
		// setDBFactory
		$text .= '	/**'."\n";
		$text .= '	 * Method: setErrorDisplay($ed)'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * Do NOT alter this method.'."\n";
		$text .= '	 * Inject the required factory - needed so the methods can inject the db where required'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function setErrorDisplay($ed) {'."\n";
		$text .= '		$this->errorDisplay = $ed;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		
		
		// displayReview
		$text .= '	/**'."\n";
		$text .= '	 * Method: displayReview(iValueObject $vo)'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * Validation not required for this method - no data changed/added'."\n";
		$text .= '	 * Do NOT alter this method.'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function displayReview(iValueObject $vo) {'."\n";
		$text .= '		// assume headers and etc are alrady in place...'."\n";
		$text .= '		$text = \'\';'."\n";
		$text .= '		$text .= \'<div id="'.strtolower($classname).'">\'."\n";'."\n";
		$text .= '		$text .= \'<fieldset>\'."\n";'."\n";
		$text .= '		$text .= \'<legend>Review - '.ucfirst($classname).' data</legend>\'."\n";'."\n";
		//$text .= '		$text .= \'<label for="">\''."\n";
		$text .= '		// the data...'."\n";
		$text .= ''."\n";
		//$text .= '		$text .= \'</form>\''."\n";
		
		foreach ($fields as $field=>$data) {
			// don't use the valid element, nor the display
			if (isset($data['display'])) {
				// don't display if we don't need to in this context - review
				if ($this->needToDisplay($data['display'], 'r')) {
					foreach ($data as $name=>$type) {
						print 'Name: '.$name.'<br />';
						//if ($name != 'id') {
						if ($name != 'display' && $name != 'valid') {
							// this is the lookup table stuff
							if (substr_count($name, '_id') > 0) {
								// there is an id we need to get
								$idTextArray = explode('_id', $name);
								$text .= ''."\n";
								$text .= '		// get the item the id is pointing to'."\n";
								$text .= '		$oFactory = new '.ucfirst($idTextArray[0]).'Factory($this->getSQLFactory());'."\n";
								$text .= '		// inject the dbfactory'."\n";
								//$text .= '		$oFactory->setDBFactory($this->dbFactory);'."\n";
						
								$text .= '		$dao = $oFactory->get'.ucfirst($idTextArray[0]).'ById($vo->getDataItem(\''.strtolower($name).'\'));'."\n";
								//$text .= 'echo \'<pre>in displayReview()...\';'."\n";
								//$text .= 'print($data->get_'.strtolower($name).'());'."\n";
								//$text .= 'echo \'</pre>\';'."\n";
								$text .= '		$value = $dao->getValueObject()->getDataItem(\''.strtolower($idTextArray[0]).'\');'."\n";
								$text .= '		$text .= \'<label for="'.strtolower($idTextArray[0]).'">'.str_replace('_', ' ', ucfirst($idTextArray[0])).': </label>\';'."\n";
								$text .= '		// get the data...'."\n";
								$text .= '		$text .= $value;'."\n";
								$text .= '		$text .= \'<br />\';'."\n";
							} else {
								$text .= '		$text .= \'<label for="'.strtolower($name).'">'.str_replace('_', ' ', ucfirst($name)).': </label>\';'."\n";
								$text .= '		// get the data...'."\n";
								$text .= '		$text .= $vo->getDataItem(\''.strtolower($name).'\');'."\n";
								$text .= '		$text .= \'<br />\';'."\n";
							}
						}
					}
				}
			}
		}
		
		$text .= '		$text .= \'</fieldset>\';'."\n";
		$text .= '		$text .= \'</div>\';'."\n";
		$text .= '		return $text;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		/*
		 * Best practice: 
		<div id="my-module">
			<fieldset>
				<legend> Expense Report Form</legend>
				<h3>Submit Expenses</h3>
				<form name="myForm" action="doSomething.php">
					<label for="name">Your Name:</label>
					<input type="text" id="name" />
					<input type="submit" />
				</form>
			</fieldset>
		</div>
		*/
		// for "required" fields check out http://www.w3.org/WAI/GL/WCAG20-TECHS/H90.html
		
		
		// displayNew
		$text .= '	/**'."\n";
		$text .= '	 * Method: DisplayNew(array $errors(may == null))'."\n";
		$text .= '	 * @param $errors An array of error messages'."\n";
		$text .= '	 * @param $data The originally submitted data to populate fields with in the case there is an error'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * This method is post-validation - check error array.'."\n";
		$text .= '	 * WE MAY WANT TO INJECT THE DBFactory into the class on instantiation'."\n";
		$text .= '	 * Do NOT alter this method.'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function displayNew($errors = null, $postData = null) {'."\n";
		$text .= '		$data = null;'."\n";
		$text .= '		if (!is_null($postData)) $data = $postData->getData();'."\n";
		$text .= ''."\n";
		$text .= '		$text = \'\';'."\n";
		$text .= '		$text .= \'<div id="'.strtolower($classname).'">\'."\n";'."\n";
		//$text .= '	$text .='."\n";
		$text .= '		$text .= \'<fieldset>\'."\n";'."\n";
		$text .= '		$text .= \'<legend>Enter new '.ucfirst($classname).' details</legend>\'."\n";'."\n";
		//$text .= '		$text .= \'<p>Required fields are marked</p>\';'."\n";
		$text .= '		$text .= \'<p>Required fields are marked with an asterisk (<abbr class="req" title="required">*</abbr>).</p>\';'."\n";
		
        $text .= '		// display any errors'."\n";
        $text .= '		if (!is_null($errors)) {'."\n";
        $text .= '			$text .= \'<div class="formerror"><p><img src="/images/error_triangle.jpg" width="16" height="16" hspace="5" alt="Error image">Please check the following and try again:</p><ul>\'."\n";'."\n";
        $text .= '			// Get each error and add it to the error string as a list item.'."\n";
        $text .= '			foreach ($errors as $field=>$errorMessage) {'."\n";
        $text .= '				$text .= "<li>$errorMessage</li>"."\n";'."\n";
        $text .= '			}'."\n";
        $text .= '			$text .= \'</ul></div>\'."\n";'."\n";
        $text .= '		}'."\n";
        $text .= ''."\n";
        $text .= ''."\n";
        
		
		$text .= '		// the data...'."\n";
		$text .= ''."\n";
		
		foreach ($fields as $field=>$data) {
			if (isset($data['display'])) {
				// don't display if we don't need to in this context
				//print 'display IS set here...going to check '.$data['display'].'for display context<br />';
				if ($this->needToDisplay($data['display'], 'n')) {
					foreach ($data as $name=>$type) {
						if ($name != 'display' && $name != 'valid') {
							if (substr_count($name, '_id') > 0) {
								// there is a lookup list we need to get
								// NOTE: assumes only ONE word before the _id
								$idTextArray = explode('_id', $name);
								$text .= ''."\n";
								$text .= '		// No need to check for errors here - this is a look-up table.'."\n";
								$text .= '		// We DO need to check for a previously selected value if there was an error'."\n";
								$text .= ''."\n";
								$text .= '		// use the factory get all the items - and inject dependencies'."\n";
								$text .= '		$oFactory = new '.ucfirst($idTextArray[0]).'Factory($this->getSQLFactory());'."\n";
								$text .= '		// returns an array of '.ucfirst($idTextArray[0]).' data access objects'."\n";
								$text .= '		$daos = $oFactory->getAll'.ucfirst($idTextArray[0]).'Data();'."\n";
						
								$text .= '		// may need to do somethng here in case of error - but I do not think error will occur here.'."\n";
								$text .= '		$text .= \'<p>\'."\n";'."\n";
						
								$text .= '		$text .= \'<label for="'.strtolower($idTextArray[0]).'">\'."\n";'."\n";
								// determine whether the field is required or not...
								$text .= '		// determine whether the field is required or not...'."\n";
								$text .= '		'.$this->getRequiredLabel($type, $name).';'."\n";
								$text .= '		$text .= \''.str_replace('_', ' ', ucfirst($idTextArray[0])).'\';'."\n";
								$text .= '		$text .= \': </label>\'."\n";'."\n";
								$text .= '		// get the data...'."\n";
								$text .= '		$text .= \'<select name="'.$name.'">\'."\n";'."\n";
								$text .= '		foreach ($daos as $dao) {'."\n";
								$text .= '			$vo = $dao->getValueObject();'."\n";
								$text .= '			$text .= \'<option value="\'.$vo->getDataItem(\'id\').\'"\';'."\n";
								$text .= '			// need to get the value, and we need the selected value...'."\n";
								$text .= '			if (!is_null($errors)) {'."\n";
								$text .= '				if ($dao->getValueObject()->getDataItem(\'id\') == $this->get_'.$name.'($postData)) {'."\n";
								//$text .= '				if ($this->isSelected($vo->getDataItem(\'id\'), \''.$name.'\', $data)) { '."\n";
								$text .= '					$text .= \' selected\';'."\n";
								$text .= '				}'."\n";
								//$text .= ''."\n";
								//$text .= '				if ($vo->getDataItem(\'id\') == $data[\''.$name.'\']) $text .= \' selected\';'."\n";
								$text .= '			}'."\n";
								$text .= '			$text .= \'>\'.$vo->getDataItem(\''.$idTextArray[0].'\').\'</option>\'."\n";'."\n";
								//$text .= '			$text .= \'>\'.$vo->get_'.ucfirst($idTextArray[0]).'().\'</option>\';'."\n";
								$text .= '		}'."\n";
								
								$text .= '		$text .= \'</select>\'."\n";'."\n";
								$text .= '		$text .= \'</p>\'."\n";'."\n";
							} else {
								$text .= '		$text .= \'<p\';'."\n";
								$text .= '		// need to check for errors here - if there is an error, we need to display any entered text'."\n";
								$text .= '		if ($this->errorDisplay->isValidationError($errors, \''.$name.'\')) {'."\n";
								$text .= '			// get submitted text'."\n";
								$text .= '			$text .= \' class="formerror"\';'."\n";
								$text .= '		}'."\n";
								$text .= '		$text .= \'>\'."\n";'."\n";
								//$text .= '		} else {'."\n";
								$text .= '		// no error, so just display'."\n";
								$text .= '		$text .= \'<label for="'.strtolower($name).'">\'."\n";'."\n";
						
								// determine whether the field is required or not...
								//$text .= '		// determine whether the field is required or not...'."\n";
								print 'Type: '.$type.'; Name: '.$name.'<br />';
								$text .= '		'.$this->getRequiredLabel($type, $name);
						
								$text .= '		$text .= \''.str_replace('_', ' ', ucfirst($name)).'\';'."\n";
								$text .= '		$text .= \': </label>\'."\n";'."\n";
								$text .= '		// need to check for errors to determine whether to display data'."\n";
								$text .= '		if (!is_null($errors)) {'."\n";
								$text .= '			$text .= \''.$this->getDisplayType($type, $name, 2).'\';'."\n";
								$text .= '			foreach ($data as $item) {'."\n";
								$text .= '				foreach ($item as $field=>$value) {'."\n";
								//$text .= 'echo \'<pre>\';'."\n";
								//$text .= 'print \'Name is: '.$name.'; field is: \'.$field;'."\n";
								//$text .= 'echo \'</pre>\';'."\n";
								$text .= '					if ($field ===\''.$name.'\') {'."\n";
								$text .= '						$text .= \' value="\';'."\n";
								$text .= '						$text .= $this->getFieldValue(\''.$name.'\', $item);'."\n";
								$text .= '						$text .= \'"\';'."\n";
								$text .= '					}'."\n";
								$text .= '				}'."\n";
								$text .= '			}'."\n";
								
								
								//$text .= '			$text .= \' value="\'.$data[\''.$name.'\'].\'"\';'."\n";
								$text .= '			$text .= \' />\'."\n";'."\n";
								$text .= '		} else {'."\n";
								$text .= '			$text .= \''.$this->getDisplayType($type, $name).'\';'."\n";
								$text .= '		}'."\n";
								
								//$text .= '		$text .= \''.$this->getDisplayType($type, $name).'\';'."\n";
								$text .= '		$text .= \'</p>\'."\n";'."\n";
								//$text .= '		}'."\n";
								$text .= ''."\n";
							}
						}		
					}
				}
			}
		}
		$text .= '		$text .= \'</fieldset>\'."\n";'."\n";
		$text .= '		$text .= \'</div>\'."\n";'."\n";
		$text .= '		return $text;'."\n";
		$text .= ''."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		
		//**********************//
		//*** display UPDATE ***//
		//**********************//
		$text .= '	/**'."\n";
		$text .= '	 * Method: DisplayUpdate(NGOValueObjectInterface $data)'."\n";
		$text .= '	 * Method created automatically by NGOData system'."\n";
		$text .= '	 * Do NOT alter this method.'."\n";
		$text .= '	 * @param NGOValueObjectInterface $data Ensure correct type of object is given.'."\n";
		$text .= '	 */'."\n";
		$text .= '	public function displayUpdate(iValueObject $vo) {'."\n";
		$text .= '		$text = \'\';'."\n";
		$text .= '		$text .= \'<div id="'.strtolower($classname).'">\';'."\n";
		$text .= '		$text .= \'<fieldset>\';'."\n";
		$text .= '		$text .= \'<legend>Update '.ucfirst($classname).' data</legend>\';'."\n";
		$text .= '		$text .= \'<p>Required fields are marked with an asterisk (<abbr class="req" title="required">*</abbr>).</p>\';'."\n";
		$text .= '		// the data...'."\n";
		$text .= ''."\n";
		//$text .= '		$text .= \'</form>\''."\n";
		
		foreach ($fields as $field=>$data) {
			if (isset($data['display'])) {
				if ($this->needToDisplay($data['display'], 'u')) {
					foreach ($data as $name=>$type) {
						if ($name != 'display' && $name != 'valid') {
							if (substr_count($name, '_id') > 0) {
								// there is a lookup list we need to get
								// NOTE: assumes only ONE word before the _id
								$idTextArray = explode('_id', $name);
								$text .= ''."\n";
								$text .= '		// get all the items'."\n";
								$text .= '		$oFactory = new '.ucfirst($idTextArray[0]).'Factory();'."\n";
								$text .= '		// inject the dbfactory'."\n";
								$text .= '		$oFactory->setDBFactory($this->dbFactory);'."\n";
								$text .= '		// returns an array of '.ucfirst($idTextArray[0]).' data access objects'."\n";
								$text .= '		$daos = $oFactory->getAll'.ucfirst($idTextArray[0]).'Data();'."\n";
								$text .= '		$text .= \'<label for="'.strtolower($idTextArray[0]).'">'.str_replace('_', ' ', ucfirst($idTextArray[0])).'\';'."\n";
								// determine whether the field is required or not...
								//$text .= '		// determine whether the field is required or not...'."\n";
								$text .= '		'.$this->getRequiredLabel($type);
								$text .= '		$text .= \': </label>\';'."\n";
								$text .= '		// get the data...'."\n";
								$text .= '		$text .= \'<select>\';'."\n";
								$text .= '		foreach ($daos as $item) {'."\n";
								$text .= '			'."\n";
								$text .= '			$text .= \'<option value="\'.$item->getValueObject()->get_id().\'"\';'."\n";
								$text .= '			// need to get the value, and we need the selected value...'."\n";
								$text .= '			if ($item->getValueObject()->get_id() == $data->get_'.$name.'()) {'."\n";
								$text .= '				$text .= \' selected \';'."\n";
								$text .= ''."\n";
								$text .= '			}'."\n";
								$text .= '			$text .= \'>\'.$item->getValueObject()->get_'.ucfirst($idTextArray[0]).'().\'</option>\';'."\n";
								$text .= '		}'."\n";
								$text .= '		$text .= \'</select>\';'."\n";
								$text .= '		$text .= \'<br />\';'."\n";
							} else {
								$text .= '		$text .= \'<label for="'.strtolower($name).'">'.str_replace('_', ' ', ucfirst($name)).'\';'."\n";
								// determine whether the field is required or not...
								$text .= '		// determine whether the field is required or not...'."\n";
								$text .= '		'.$this->getRequiredLabel($type, $name);
								$text .= '		$text .= \': </label>\';'."\n";
								$text .= '		$text .= \''.$this->getDisplayType($type, $name, 1).' \';'."\n";
								$text .= '		$text .= \'value="\'.$vo->getData(\''.strtolower($name).'\').\'" />\';'."\n";
								$text .= '		$text .= \'<br />\';'."\n";
							}
						}
					}
				}
			}
		}
		
		$text .= '		$text .= \'</fieldset>\';'."\n";
		$text .= '		$text .= \'</div>\';'."\n";
		$text .= '		return $text;'."\n";
		//$text .= ''."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		/*
		//if ($vo->getDataItem(\'id\') == $data[\''.$name.'\']) $text .= \' selected\';'."\n";
		$text .= '	private function isSelected($id, $name, $data) {'."\n";
		$text .= '		foreach ($data as $item) {'."\n";
		$text .= '			if (array_key_exists($name, $item)) {'."\n";
		$text .= '				return $item[$name] === $id;'."\n";
		$text .= '			}'."\n";
		$text .= '		}'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		*/
		
		foreach ($fields as $field=>$data) {
			// don't use the valid element, nor the display
			if (isset($data['display'])) {
				// don't display if we don't need to in this context - review
				//if ($this->needToDisplay($data['display'], 'r')) {
					foreach ($data as $name=>$type) {
						if ($name != 'display' && $name != 'valid') {
							// this is the lookup table stuff
							if (substr_count($name, '_id') > 0) {								
								// create each of the required get_*_id thingys								
								$text .= '	private function get_'.$name.'(iPostData $pd) {'."\n";
								$text .= '		$data = $pd->getData();'."\n";
								$text .= '		foreach ($data as $item) {'."\n";
								$text .= '			if (is_array($item)) {'."\n";
								$text .= '				if (isset($item[\''.$name.'\'])) {'."\n";
								$text .= '					return $item[\''.$name.'\'];'."\n";
								$text .= '				}'."\n";
								$text .= '			}'."\n";
								$text .= '		}'."\n";
								$text .= '	}'."\n";
								$text .= ''."\n";
								$text .= ''."\n";
							}
						}
					}
				//}
			}
		}
		
		
		$text .= '	private function getFieldValue($field, $data) {'."\n";
		$text .= '		if (isset($data[$field])) return $data[$field];'."\n";
		//$text .= '		foreach ($data as $item) {'."\n";
		//$text .= '			foreach ($item as $key=>$value) {'."\n";
		//$text .= '				if ($key === $field) return $value;'."\n";
		//$text .= '			}'."\n";
		//$text .= '		}'."\n";
		$text .= '		return null;'."\n";
		$text .= '	}'."\n";
		$text .= ''."\n";
		$text .= ''."\n";
		
		//$text .= ''."\n";
		//$text .= ''."\n";
		$text .= '}'."\n";
		$text .= '?>'."\n";
		
		
		// write contents to class file
		$fp = fopen('../display/'.$this->classname.'Display.class.php',"w") or die ('Error creating file');
		fwrite($fp, $text);
		fclose($fp);
		//print $text;
		return TRUE;
	}
	
	
	/**
	 * Method: isRequiredFieldType($type)
	 * @desc Determines whether the entered $type is a required user field type or not.
	 * Used for the display graph
	 */
	private function isRequiredFieldType($type) {
		return (substr($type,-1) === 'n');
	}
	
	
	private function getRequiredLabel($type) {
		if ($this->isRequiredFieldType($type)) {
			return '$text .= \'<abbr class="req" title="This is a required field">*</abbr>\';'."\n";
		}
		return '';
	}
	
	
	/**
	 *
	 */
	private function getDisplayType($type, $name, $extra=0) {
		switch ($type) {
			case 'v8':
			case 'v8n':
			case 'v16':
			case 'v16n':
			case 'v32':
			case 'v32n':
			case 'v64':
			case 'v64n':
			case 'v128':
			case 'v128n':
			case 'v256':
			case 'v256n':
			case 'v512':
			case 'v512n':
				//$text = '<input type="text" name="'.$name.'"';
				if ($extra == 0) return '<input type="text" name="'.$name.'" />';
				else return '<input type="text" name="'.$name.'"';
				break;
			
			
			
			default:
				return '';
				break;
		}
	}
	
	
	private function getErrorDisplayType($type, $name, $extra=0, $errors) {
		switch ($type) {
			case 'v8':
			case 'v8n':
			case 'v16':
			case 'v16n':
			case 'v32':
			case 'v32n':
			case 'v64':
			case 'v64n':
			case 'v128':
			case 'v128n':
			case 'v256':
			case 'v256n':
			case 'v512':
			case 'v512n':
				/*
				<input name="phone" type="text" id="phone" value="<?php echo $_POST['phone'] ?>">
    <?php if (!empty($arrErrors['phone'])) echo '<img src="/images/triangle_error.gif" width="16" height="16" hspace="5" alt=""><br /><span class="errortext">'.$arrErrors['phone'].'</span>';
				*/
				
				//if ($extra == 0) return '<input type="text" id="'.$name.'" name="'.$name.'" value="'.$this->getValueOnError().'" />';
				if ($extra == 0) return '<input type="text" id="'.$name.'" name="'.$name.'" />';
				else return '<input type="text" id="'.$name.'" name="'.$name.'"';
				break;
			
			
			
			default:
				return '';
				break;
		}
	}
	
	private function needToDisplay($data, $needle) {
		//return in_array($context, explode($data['display']));
		$haystack = explode('|', $data);
		print '<pre>';
		print 'Here is the haystack we are looking in: ';
		print_r($haystack);
		print '</pre>';
		foreach ($haystack as $k=>$v) {
			print 'Display in '.$needle.' context: '.$v.' <br />';
			if ($v === $needle) return true;
		}
		return false;
	}
	
	
	/**
	 * Method: getFieldType
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: August 2012
	 * @return string Returns a string identifying the type of field for database binding purposes
	 */
	private function getFieldType($fieldType) {
		print '<pre>getFieldType: '.$fieldType.'</pre>';
		
		if ($fieldType === 'id') return 'i'; // check for id
		else if ($fieldType === 'b') return 'b'; // check for blob
		else if ($fieldType === 't') return 's'; // check for blob
		
		if (strpos($fieldType, 'v') !== false) return 's'; // check for varchar
		else if (strpos($fieldType, 'i') !== false) return 'i'; // check for int
		else if (strpos($fieldType, 'b') !== false) return 'b'; // check for blob
		else if (strpos($fieldType, 'd') !== false) return 's'; // check for date
		else if (strpos($fieldType, 't') !== false) return 's'; // check for date
		else return false;
	}
}
?>