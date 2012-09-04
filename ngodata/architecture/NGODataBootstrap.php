<?php
require_once '../architecture/ModelGenerator.class.php';
require_once '../architecture/ModelDefinition.class.php';
require_once '../containers/ArchitectureContainer.php';

// comment this out prior to running architecture the first time
require_once '../containers/InitialDataContainer.class.php';

require_once '../database/SQLFactory.class.php';
require_once '../database/DBFactory.class.php';

$ac = new ArchitectureContainer(new ModelGenerator(new SQLFactory(new DBFactory())), new ModelDefinition());
$ac->createArchitecture();


// load initial test lookup data...
$tc = new InitialDataContainer(new SQLFactory(new DBFactory()));
$tc->loadTitleLookupData();
?>