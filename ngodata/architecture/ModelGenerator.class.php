<?php
require_once '../database/iSQLFactory.class.php';
require_once '../architecture/DBIGenerator.class.php';

class ModelGenerator {

	private $sqlf;
	
	public function __construct(iSQLFactory $sqlf) {
		// need to inject the sql factory
		$this->setSQLFactory($sqlf);
	}
	
	//inject the dbFactory
	private function setSQLFactory(iSQLFactory $sqlf) {
		$this->sqlf = $sqlf;
	}
	
	
	// $tablename can be an array - join tables need more than one tablename
	public function manageObjectCreation($tablename, $path, $fields, $dbname, $pk=null) {
		if (is_null($pk)) $this->createJoinObjects($tablename, $path, $fields, $dbname, $pk);
		else $this->createObjects($tablename, $path, $fields, $dbname, $pk);
	}
	
	
	private function createObjects($tablename, $path, $dbfields, $dbname, $pk) {
		// why do I do this?
		$fields = $this->extractObjectFieldsFromDBFields($dbfields);
		
		//echo '<pre>ModelGenerator - Fields: ';
		//print_r($fields); //'fields: '.;
		//echo '</pre>';
		
		
		// create the table in the database
		$sql = '';
		$tn = '';
		
		// create the DBI, and inject dependencies
		$dbi = new DBIGenerator();
		$dbi->setFieldNames($fields);
		$dbi->setNames($tablename);
		$dbi->setFilePath($path);
		$dbi->setDBName($dbname);
		$dbi->setDBFields($dbfields);
		//$dbi->setDBFields($this->removeDisplayFromFields($dbfields));
		
		// if $pk is null, probably means join table...
		if (!is_null($pk)) {
			$tn = strtolower($tablename);
			// copy the dbfields array, or else the next method call will destroy the display...
			$array = $dbfields;
			$sql = $dbi->createTableSQL(strtolower($tablename), $this->recursiveUnsetKey($array, 'display'), $pk);
			//$sql = $dbi->createTableSQL(strtolower($tablename), $dbfields, $pk);
		} else {
		//else $sql = $dbi->createTableSQL(strtolower($tablename), $fields);
			// need to do something with the $tablename...
			//$tn = '';
			for ($i=0; $i<count($tablename); $i++) {
				$tn .= strtolower($tablename[$i]).'_';
			}
			$tn = substr($tn, 0, -1);
			$sql = $dbi->createTableSQL($tn, $fields);
		}
		
		//**************************//
		//*** Start SQL use here ***//
		//**************************//
		
		$dropSQL = $dbi->dropTableSQL($tn);
		echo '<pre>';
		print 'SQL: '.$sql.'. DropSQL: '.$dropSQL;
		echo '</pre>';
		
		$this->runSQL($dropSQL, $dbname);
		$this->runSQL($sql, $dbname);
		


		if ($dbi->createQO()) echo '...query object  creation success'."\n";
		else echo '...qo creation FAILED!'."\n";
		
		if ($dbi->createVO()) echo '...value object creation success'."\n";
		else echo '...vo creation FAILED!'."\n";
		
		if ($dbi->createDAO()) echo '...data access object creation success'."\n";
		else echo '...dao creation FAILED!'."\n";
		
		if ($dbi->createFactory()) echo '...creation success'."\n";
		else echo '... factory creation FAILED!'."\n";
		
		if ($dbi->createValidationHandler()) echo '...creation success'."\n";
		else echo '... factory creation FAILED!'."\n";
			
		// display stuff...
		if ($dbi->createDisplayClass($dbfields, $tablename)) echo '...display creation success'."\n";
		else echo '...display class creation FAILED!'."\n";
		
		if ($dbi->createGeneralException($tablename)) echo '...general exception creation success'."\n";
		else echo '...general exception creation FAILED!'."\n";
		
		if ($dbi->createMessage(strtolower($tablename))) echo '...message creation success'."\n";
		else echo '...message creation FAILED!'."\n";
	}
	
	private function createDisplayObjects($tablename, $path, $dbfields, $dbname) {
		// display stuff...
		if ($dbi->createDisplayClass($dbfields, $tablename)) echo '...display creation success'."\n";
		else echo '...creation FAILED!'."\n";
	}
	
		
	private function createJoinObjects($tablenames, $path, $dbfields, $dbname) {
		// retrieve the tablenames
		$tn1 = $tablenames[0];
		$tn2 = $tablenames[1];
		$sql = '';
		$tn = '';
		$tn = $tn1.'_'.$tn2;
		
		$dbi = new DBIGenerator();
		$dbi->setFieldNames($dbfields);
		$dbi->setNames($tn1.$tn2);
		$dbi->setFilePath($path);
		$dbi->setDBName($dbname);
		
		$sql = $dbi->createTableSQL($tn, $dbfields);
		
		$dropSQL = $dbi->dropTableSQL($tn);
		echo '<pre>';
		print 'SQL: '.$sql.'. DropSQL: '.$dropSQL;
		echo '</pre>';
		
		
		//################################################################//
		//###  Turn OFF to stop automatic creation of database tables  ###//
		//################################################################//
		
		// runTableSQL returns a boolean...
		$this->runSQL($dropSQL, $dbname);
		$this->runSQL($sql, $dbname);
		
			
		//join objects
		if ($dbi->createJDAO($tn1, $tn2)) echo '...jdoa creation success'."\n";
		else echo '...creation FAILED!'."\n";
		if ($dbi->createJVO($tn1, $tn2)) echo '...jvo creation success'."\n";
		else echo '...creation FAILED!'."\n";
		if ($dbi->createJQO($tn1, $tn2)) echo '...jqo creation success'."\n";
		else echo '...creation FAILED!'."\n";
		if ($dbi->createJoinObjectFactory($tn1, $tn2)) echo '...join factory creation success'."\n";
		else echo '...creation FAILED!'."\n";
		/*
		} else {
			print 'Cannot proceed as a connection cannot be made to the database...';
		}
		*/
	}
	
	
	private function runSQL($sql, $dbname) {
		return $this->sqlf->runTableSQL($sql, $dbname);
	}
	
	
	/**
	 *
	 */
	private function extractObjectFieldsFromDBFields($dbfields) {
		$fields = null;
		foreach ($dbfields as $field=>$data) {
			foreach ($data as $name=>$type) {
				if ($name != 'display' && $name != 'valid') {
					$name = str_replace(',', '_', $name);
					$fields[] = $name;
				}
			}
		}
		return $fields;
	}
	
	
	/**
	 * Method: recursiveUnsetKey(&$array, $unwantedKey)
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: June 2012
	 * @param
	 * @param 
	 * @return array Returns an array with any element with an unwanted key removed 
	 */
	private function recursiveUnsetKey(&$array, $unwantedKey) {
		unset($array[$unwantedKey]);
		foreach ($array as &$value) {
			//echo '<pre>recursiveUnsetKey - clean value...? ';
			//print_r($value);
			//echo '</pre>';
			if (is_array($value)) {
				$this->recursiveUnsetKey($value, $unwantedKey);
			}
		}
		return $array;
	}
	
	// DEPRICATED
	/*
	private function createMetadata() {
		$fields = array('variable', 'value', 'is_encrypted');
		$dbi = new DBIGenerator('Metadatum', '../model/', $fields, 'metadatum', 'NGOMetaData');
		if ($dbi->createVO()) echo '...creation success'."\n";
		else echo '...creation FAILED!'."\n";
		if ($dbi->createQO()) echo '...creation success'."\n";
		else echo '...creation FAILED!'."\n";
		if ($dbi->createDAO()) echo '...creation success'."\n";
		else echo '...creation FAILED!';
		if ($dbi->createFactory()) echo '...creation success'."\n";
		else echo '...creation FAILED!'."\n";
		if ($dbi->createGeneralException('Metadatum')) echo '...creation success'."\n";
		else echo '...creation FAILED!'."\n";
		if ($dbi->createMessage('Metadatum')) echo '...creation success'."\n";
		else echo '...creation FAILED!'."\n";
	}
	*/
	
	
}
?>