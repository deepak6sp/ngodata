<?php
require_once '../metadata/MetadatumQO.class.php';
require_once '../metadata/MetadatumVO.class.php';
require_once '../metadata/MetadatumDAO.class.php';

class MetadatumFactory{
	
	// lazy load the authorisation metadata
	private $suerMetadata = null;
	
	public function __construct() {
	}

	public function createMetadatumById($id) {
		$qo = new MetadatumQO();
		$vo = new MetadatumVO();
		$vo->setId($id);
		$vo->setQueryObject($qo);
		$dao = new MetadatumDAO();
		$dao->setData($vo);
		// No data set apart from id
		return $dao;
	}

	/**
	 * 
	 */
	public function createMetadatum($data) {
		$qo = new MetadatumQO();
		$vo = new MetadatumVO();
		$vo->setData($data);
		$vo->setQueryObject($qo);
		$dao = new MetadatumDAO();
		//$dao->setSQLFactory($this->getSQLFactory());
		//$dao->setDBFactory($this->getDBFactory());
		$dao->setData($vo);
		return $dao;
	}
	
	/**
	 * 
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Clears out the metadata table - use prior to re-initialisation process
	 * ### SHOULD NOT BE RUN IN PRODUCTION ###
	 */
	public function truncateMetadatumTable() {
		
	}
}
?>