<?php

class Proverb extends BaseRecord {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('proverb');
		$this->hasColumn('lugphrase', 'string', 500);
		$this->hasColumn('lugmeaning', 'string', 500);
		$this->hasColumn('engphrase', 'string', 500);
		$this->hasColumn('engmeaning', 'string', 500);
		$this->hasColumn('keywords', 'string', 1000);
		$this->hasColumn('publish', 'integer', null);
		$this->hasColumn('datecreated','date');
		$this->hasColumn('publishstart','date');
		$this->hasColumn('publishend','date');
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
	function getAllProverbs(){
		$q = Doctrine_Query::create()->from('Proverb p')->where("p.id <> '".$this->getID()."' ");
		$result = $q->execute();
		return $result;
	}
	function getProverbForWeek($date){
		$q = Doctrine_Query::create()->from('Proverb p')->where("p.publishstart = '".$date."' ");
		$result = $q->fetchOne();
		return $result;
	}
}
?>