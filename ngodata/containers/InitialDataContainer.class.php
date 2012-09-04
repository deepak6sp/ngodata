<?php
require_once '../database/iSQLFactory.class.php';
require_once '../database/PostData.class.php';
require_once '../model/TitleFactory.class.php';
//require_once '';

class InitialDataContainer {
	private $sqlf;
	
	public function __construct(iSQLFactory $sqlf) {
		
		//echo '<pre>The SQLFactory in InitialDataContainer constructor: ';
		//print_r($sqlf);
		//echo '</pre>';

		$this->setSQLFactory($sqlf);
	}
	
	private function setSQLFactory(iSQLFactory $sqlf) {
		$this->sqlf = $sqlf;
	}
	
	private function getSQLFactory() {
		return $this->sqlf;
	}
	
	public function loadTitleLookupData() {
		
		
		$tf = new TitleFactory($this->getSQLFactory());
		//$tf->setDBFactory($this->dbFactory);
		
		//echo '<pre>The Title factory in InitialDataContainer: ';
		//print_r($tf);
		//echo '</pre>';
		
		// id => 0 means new field, and assign new id
		$data[] = array('id'=>'0');
		$data[] = array('title'=>'Mr');
		$data[] = array('description'=>'Mister');
		$pd = new PostData($data);
		$tdao = $tf->createTitle($pd);
		//echo '<pre>The Title data object: ';
		//print_r($tdao);
		//echo '</pre>';

		$tdao->saveObject();
		
		//echo '<pre>Got here... ';
		//print_r($this->getSQLFactory());
		//echo '</pre>';

		
		//$data = array();
		$data2[] = array('id'=>'0');
		$data2[] = array('title'=>'Mrs');
		$data2[] = array('description'=>'Mistress married');
		$pd2 = new PostData($data2);
		$tdao2 = $tf->createTitle($pd2);
		$tdao2->saveObject();
		
		//$data = array();
		$data3[] = array('id'=>'0');
		$data3[] = array('title'=>'Dr');
		$data3[] = array('description'=>'Doctor');
		$pd3 = new PostData($data3);
		$tdao3 = $tf->createTitle($pd3);
		$tdao3->saveObject();
		
		//$data = array();
		$data4[] = array('id'=>'0');
		$data4[] = array('title'=>'Miss');
		$data4[] = array('description'=>'Mistress unmarried');
		$pd4 = new PostData($data4);
		$tdao4 = $tf->createTitle($pd4);
		$tdao4->saveObject();
		
		//$data = array();
		$data5[] = array('id'=>'0');
		$data5[] = array('title'=>'Ms');
		$data5[] = array('description'=>'Mistress no marriage distinction');
		$pd5 = new PostData($data5);
		$tdao5 = $tf->createTitle($pd5);
		$tdao5->saveObject();
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