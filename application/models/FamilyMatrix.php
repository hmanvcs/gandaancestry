<?php

class FamilyMatrix extends BaseRecord {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('familymatrix');
		$this->hasColumn('index', 'integer', null);
		$this->hasColumn('englishname', 'string', 50);
		$this->hasColumn('englishdescription', 'string', 50);
		$this->hasColumn('lugandaname', 'string', 50);
		$this->hasColumn('lugandadescription', 'string', 50);
		$this->hasColumn('theorder', 'string', 255);
		$this->hasColumn('generation', 'string', 15);
		$this->hasColumn('thegroup', 'string', 15);
		$this->hasColumn('isimmediate', 'integer', null, array('default' => '0'));
		$this->hasColumn('isinlaw', 'integer', null, array('default' => '0'));
		$this->hasColumn('isbloodrelative', 'integer', null, array('default' => '0'));
		$this->hasColumn('listable', 'integer', null, array('default' => '0'));
		$this->hasColumn('listableorder', 'integer', null, array('default' => '0'));
		$this->hasColumn('disabledrel', 'string', 255);
		
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
		if(isArrayKeyAnEmptyString('index', $formvalues)){
			unset($formvalues['index']); 
		}
		if(isArrayKeyAnEmptyString('isimmediate', $formvalues)){
			unset($formvalues['isimmediate']); 
		}
		if(isArrayKeyAnEmptyString('isinlaw', $formvalues)){
			unset($formvalues['isinlaw']); 
		}
		if(isArrayKeyAnEmptyString('isbloodrelative', $formvalues)){
			unset($formvalues['isbloodrelative']); 
		}
		if(isArrayKeyAnEmptyString('listable', $formvalues)){
			unset($formvalues['listable']); 
		}
		if(isArrayKeyAnEmptyString('listableorder', $formvalues)){
			unset($formvalues['listableorder']); 
		}
		parent::processPost($formvalues);
	}
	/**
	 * Return the profile languages
	 *
	 * @return collection the profile languages
	 */
	function getMatrixLine($order) {
		$q = Doctrine_Query::create()->from('FamilyMatrix fm')->where("fm.theorder LIKE '%".$order."%'");
		// debugMessage($q->getSqlQuery());
		return $q->fetchOne();
	}	
}
?>