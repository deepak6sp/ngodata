<?php
require_once '../database/iSQLStatement.class.php';
require_once '../database/iBindParam.class.php';

/**
 * Class: SQLStatement
 * @author Steve Cooke <sa_cooke@me.com>
 * Date: August 2012
 * @descr This class holds the various elements that may be needed by an sql statement
 * @usage Pass an instance of this object to the SQLHandler.  
 */
class SQLStatement implements iSQLStatement {
	private $sqlStatement;
	private $bindParams;
	
	public function __construct($sql=null, iBindParam $bindParams=null) {
		if ($sql === null) $this->setSQLStatement = null;
		else $this->setSQLStatement($sql);
		
		if (!is_null($bindParams)) {
			$this->setBindParams($bindParams);
		} else {
			$this->setBindParams(null);
		}
	}
	
	private function setSQLStatement($sql) {
		$this->sqlStatement = $sql;
	}
	
	private function setBindParams($bind) {
		$this->bindParams = $bind;
	}
	
	public function getSQLStatement() {
		if ($this->sqlStatement === null) return null;
		return $this->sqlStatement;
	}
	
	public function getBindParams() {
		if ($this->bindParams === null) return null;
		return $this->bindParams;
	}
}
?>