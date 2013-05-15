<?php

class Interest extends BaseRecord {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('interest');
		$this->hasColumn('userid', 'integer', null);
		$this->hasColumn('personid', 'integer', null);
		$this->hasColumn('type', 'integer', null); // 1 - Language, 2 - Ethinicity, 3 -	
		$this->hasColumn('value', 'string', 65535);
		$this->hasColumn('value2', 'string', 500);
		$this->hasColumn('iscurrent', 'integer', null, array('default' => '1'));
		$this->hasColumn('visibility', 'integer', null, array('default' => '1'));		
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
		
		$this->hasOne('Person as person', 
								array(
									'local' => 'personid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('UserAccount as user', 
								array(
									'local' => 'userid',
									'foreign' => 'id',
								)
						);
	}
	/**
	 * Preprocess model data
	 */
	function processPost($formvalues){
		// set default values for integers, dates, decimals
		if(isArrayKeyAnEmptyString('userid', $formvalues)){
			unset($formvalues['userid']); 
		}
		if(isArrayKeyAnEmptyString('personid', $formvalues)){
			unset($formvalues['personid']); 
		}
		if(isArrayKeyAnEmptyString('type', $formvalues)){
			unset($formvalues['type']); 
		}
		if(isArrayKeyAnEmptyString('iscurrent', $formvalues)){
			unset($formvalues['iscurrent']); 
		}
		if(isArrayKeyAnEmptyString('visibility', $formvalues)){
			unset($formvalues['visibility']); 
		}		
		parent::processPost($formvalues);
	}
	# return the eye color label
	function getLanguageLabel(){
		$languages = getLanguages();
		return isEmptyString($this->getValue2()) ? '--' : $languages[$this->getValue2()];  
	}
	# return the eye color label
	function getEthinicityLabel(){
		$ethinicities = getEthinicities();
		return isEmptyString($this->getValue2()) ? '--' : $ethinicities[$this->getValue2()];  
	}	
}
?>