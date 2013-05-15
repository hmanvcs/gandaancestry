<?php

class Beta extends BaseRecord {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('beta');
		$this->hasColumn('personid', 'integer', null);
		$this->hasColumn('clan','string', 50);
		$this->hasColumn('ssiga','string', 50);
	}
	/**
	 * Contructor method for custom initialization
	 */
	public function construct() {
		parent::construct();
		
		// $this->addDateFields(array("dateapplied"));
		
		// set the custom error messages
       	$this->addCustomErrorMessages(array(
       									"personid.notblank" => "Error. No user specified"
       	       						));
	}
	/**
	 * Model relationships
	 */
	public function setUp() {
		parent::setUp(); 
		
		$this->hasOne('Person as person', 
								array(
									'local' => 'personid',
									'foreign' => 'id'
								)
						);
	}
}
?>