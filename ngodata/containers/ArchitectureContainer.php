<?php
require_once '../database/iSQLFactory.class.php';
require_once '../architecture/ModelGenerator.class.php';
require_once '../architecture/ModelDefinition.class.php';

/**
 * ArchitectureContainer
 * Container to create the architecture - run createArchitecture
 */
class ArchitectureContainer {
	//private $sqlFactory;
	
	// model generator
	private $mg;
	
	// model definition
	private $md;
	
	
	public function __construct(ModelGenerator $mg, ModelDefinition $md) {
		$this->setModelGenerator($mg);
		$this->setModelDefinition($md);
		//$this->setSQLFactory($sqlf);
	}
	
	private function setSQLFactory(iSQLFactory $sqlf) {
		$this->sqlFactory = $sqlf;
	}
	
	public function setModelGenerator($mg) {
		$this->mg = $mg;
	}
	
	private function setModelDefinition($md) {
		$this->md = $md;
	}
	
	public function createArchitecture() {
		$this->md->runModelDefinition($this->mg);
	}
}
?>