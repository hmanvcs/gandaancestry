<?php

class Kabaka extends BaseRecord {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('kabaka');
		$this->hasColumn('no', 'string', 255);
		$this->hasColumn('name', 'string', 255);
		$this->hasColumn('from', 'string', 255);
		$this->hasColumn('to', 'string', 255);
		$this->hasColumn('mothername', 'string', 255);
		$this->hasColumn('motherclan', 'string', 255);
		$this->hasColumn('hasspan', 'integer', null);
		$this->hasColumn('spanrow1', 'integer', null);
	}
	/**
	 * Contructor method for custom initialization
	 */
	public function construct() {
		parent::construct();
		
	}
	/**
	 * Model relationships
	 */
	public function setUp() {
		parent::setUp(); 
		
	}
	/**
	 * Preprocess model data
	 */
	function processPost($formvalues){
		// set default values for integers, dates, decimals
		parent::processPost($formvalues);
	}
	# all kings
	function getAllKings(){
		$all_results_query = "SELECT * FROM kabaka k where (k.id <> '') ";   
    	
    	$conn = Doctrine_Manager::connection(); 
		$result = $conn->fetchAll($all_results_query);
		return $result;
	}
}
?>