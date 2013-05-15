<?php

class Education extends BaseRecord {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('education');
		$this->hasColumn('userid', 'integer', null);
		$this->hasColumn('personid', 'integer', null);
		$this->hasColumn('type', 'integer', null, array('default' => '1'));
		$this->hasColumn('name', 'string', 255, array( 'notnull' => true, 'notblank' => true));
		$this->hasColumn('startdate','date');
		$this->hasColumn('enddate','date');
		$this->hasColumn('award', 'string', 50);
		$this->hasColumn('country', 'string', 2, array('default' => 'UG'));
		$this->hasColumn('state', 'string', 50);
		$this->hasColumn('city', 'string', 50);
		$this->hasColumn('streetaddress', 'string', 255);
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
}
?>