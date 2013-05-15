<?php

class OrganisationName extends BaseEntity {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('organisationname');
		$this->hasColumn('organisationid', 'integer', null, array('notnull' => true, 'notblank' => true));
		$this->hasColumn('type', 'integer', null, array('default' => '1')); // 1 Boys, 2 - Girls
		$this->hasColumn('name', 'string', 255, array('notnull' => true, 'notblank' => true)); # Other - 0, Clan Admin - 1, Clan Contact Person - 2, Clan Head - 3, Clan Katikiro - 4, Clan VIP - 5
		$this->hasColumn('description', 'string', 500);
		$this->hasColumn('notes', 'string', 1000);
		
	}
	/**
	 * Contructor method for custom initialization
	 */
	public function construct() {
		parent::construct();
		
		// set the custom error messages
  		$this->addCustomErrorMessages(array(
  									"organisationid.notblank" => $this->translate->_("clan_addname_organisationid_error"),
  									"name.notblank" => $this->translate->_("clan_addname_name_error")
  	       						));
	}
	/**
	 * Model relationships
	 */
	public function setUp() {
		parent::setUp();
		
		$this->hasOne('Organisation as organisation', 
								array(
									'local' => 'organisationid',
									'foreign' => 'id',
								)
						);
	}
	/**
	 * Preprocess model data
	 */
	function processPost($formvalues){
		// set default values for integers, dates, decimals
		parent::processPost($formvalues);
	}
}
?>