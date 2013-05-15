<?php

class LookupTypeValue extends BaseRecord {
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('lookuptypevalue');
		$this->hasColumn('lookuptypeid', 'integer', 11, array('notnull' => true, 'notblank' => true));
		$this->hasColumn('lookuptypevalue', 'string', 50, array('notnull' => true, 'notblank' => true));
		$this->hasColumn('lookupvaluedescription', 'string', 500);
		
		# add the unique constraints
		// $this->unique("lookuptypeid", "lookuptypevalue");
	}
	
	/**
	 * Contructor method for custom initialization
	 */
	public function construct() {
		parent::construct();
		
	}
	
	function setUp() {
		parent::setUp();
		# Create the lookuptype vs value relationship
		$this->hasOne('LookupType as lookuptype',
						 array(
								'local' => 'lookuptypeid',
								'foreign' => 'id'
							)
					); 
	}
	# return the description for a lookup type value
	function getDescriptionForLookupValue($thedescription) {
		$conn = Doctrine_Manager::connection(); 
		$resultvalues = $conn->fetchAll("SELECT lookupvaluedescription FROM lookuptypevalue WHERE lookuptypevalue = '".$thedescription."'");
		//debugMessage($resultvalues);
		foreach ($resultvalues as $value) {
			$lookupdescription = $value;
		}
		return $lookupdescription;			
	}
	
}
?>