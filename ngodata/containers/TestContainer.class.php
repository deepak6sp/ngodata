<?php
//require_once '../model/RegistrationDAO.class.php';
//require_once '../model/RegistrationFactory.class.php';
//require_once '../model/RegistrationVO.class.php';
//require_once '../model/RegistrationQO.class.php';
//require_once '../model/TitleDAO.class.php';
//require_once '../model/TitleFactory.class.php';
//require_once '../model/TitleVO.class.php';
//require_once '../model/TitleQO.class.php';
//require_once '../model/TitleFactory.class.php';
//require_once '../display/RegistrationDisplay.class.php';
//require_once '../display/TitleDisplay.class.php';
	
class TestContainer {
	
	private $dbFactory;
	
	
	public function __construct() {
		// create the dbFactory...
		$this->dbFactory = new NGODataDBFactory();
		
		// inject metadataFactory into the NGODataDBFactory
		$mf = new NGOMetadataDBFactory();
		$this->dbFactory->setMetadataFactory($mf);
	}
	
	public function loadTestLookupData() {
		$tf = new TitleFactory();
		$tf->setDBFactory($this->dbFactory);
		
		// id => 0 means new field, and assign new id
		$data[] = array('id'=>'0');
		$data[] = array('title'=>'Mr');
		$data[] = array('description'=>'Mister');
		$tdao = $tf->createTitle($data);
		$tdao->save();
		
		//$data = array();
		$data2[] = array('id'=>'0');
		$data2[] = array('title'=>'Mrs');
		$data2[] = array('description'=>'Mistress married');
		//$objectData2[] = $data2;
		$tdao = $tf->createTitle($data2);
		$tdao->save();
		
		//$data = array();
		$data3[] = array('id'=>'0');
		$data3[] = array('title'=>'Dr');
		$data3[] = array('description'=>'Doctor');
		//$objectData3[] = $data3;
		$tdao = $tf->createTitle($data3);
		$tdao->save();
		
		//$data = array();
		$data4[] = array('id'=>'0');
		$data4[] = array('title'=>'Miss');
		$data4[] = array('description'=>'Mistress unmarried');
		//$objectData3[] = $data3;
		$tdao = $tf->createTitle($data4);
		$tdao->save();
		
		//$data = array();
		$data5[] = array('id'=>'0');
		$data5[] = array('title'=>'Ms');
		$data5[] = array('description'=>'Mistress no marriage distinction');
		//$objectData3[] = $data3;
		$tdao = $tf->createTitle($data5);
		$tdao->save();
	}
	
	
	public function testDisplay() {
		$rvo = new RegistrationVO();
		$rqo = new RegistrationQO();
		$rdao = new RegistrationDAO();
		$data[] = array('id'=>15);
		$data[] = array('entity_name'=>'NGOData');
		$data[] = array('business_registration_code'=>'27883382340');
		$data[] = array('email'=>'sa_cooke@me.com');
		$data[] = array('title_id'=>'2');
		$data[] = array('firstname'=>'Steve');
		$data[] = array('middlename'=>'');
		$data[] = array('surname'=>'Cooke');
		$data[] = array('registration_request_date'=>time());
		$data[] = array('registration_number'=>'NGODR00001');
	
		$rvo->setData($data);
		$rvo->setQueryObject($rqo);
		$rdao->setValueObject($rvo);
	
		$rd = new RegistrationDisplay();
		$rd->setDBFactory($this->dbFactory);
		echo $rd->displayReview($rdao->getValueObject());
		echo $rd->displayNew();
		echo $rd->displayUpdate($rdao->getValueObject());
	}
	

}
?>