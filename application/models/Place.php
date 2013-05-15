<?php

class Place extends BaseEntity {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('place');
		$this->hasColumn('type', 'integer', null, array('notnull' => true, 'notblank' => true));
		$this->hasColumn('name', 'string', 255, array('notnull' => true, 'notblank' => true));
		$this->hasColumn('embuga', 'string', 255);
		$this->hasColumn('headtitle', 'string', 255);
		
		$this->hasColumn('ssazaid', 'integer', null);
		$this->hasColumn('parishid', 'integer', null);
		$this->hasColumn('parentid', 'integer', null);
		
		$this->hasColumn('history', 'string', 65535);
		$this->hasColumn('notes', 'string', 65535);
	}
	/**
	 * Contructor method for custom initialization
	 */
	public function construct() {
		parent::construct();
		
		// set the custom error messages
  		$this->addCustomErrorMessages(array(
  									"type.notblank" => $this->translate->_("place_type_error"),
  									"name.notblank" => $this->translate->_("place_fullname_error")
  	       						));
	}
	/**
	 * Model relationships
	 */
	public function setUp() {
		parent::setUp();
		
		$this->hasOne('Place as parent', 
								array(
									'local' => 'parentid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Place as ssaza', 
								array(
									'local' => 'ssazaid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Place as parish', 
								array(
									'local' => 'parishid',
									'foreign' => 'id',
								)
						);
	}
	/**
	 * Preprocess model data
	 */
	function processPost($formvalues){
		// set default values for integers, dates, decimals
		if(isArrayKeyAnEmptyString('parentid', $formvalues)){
			unset($formvalues['parentid']); 
		}
		if(isArrayKeyAnEmptyString('ssazaid', $formvalues)){
			unset($formvalues['ssazaid']); 
		}
		if(isArrayKeyAnEmptyString('parishid', $formvalues)){
			unset($formvalues['parishid']); 
		}
		
		// debugMessage($formvalues);
		// exit();			
		parent::processPost($formvalues);
	}
}
?>