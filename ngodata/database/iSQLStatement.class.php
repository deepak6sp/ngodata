<?php
/**
 * Interface: iSQLStatement
 * @author Steve Cooke <sa_cooke@me.com>
 * Date: august 2012
 */
interface iSQLStatement {
	public function getSQLStatement();
	//private function setSQLStatement($statement);
	public function getBindParams();
	//private function setBindParams($bindParams);
}
?>