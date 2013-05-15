<?php

class OrganisationContact extends BaseEntity {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('organisationcontact');
		$this->hasColumn('organisationid', 'integer', null, array('notnull' => true, 'notblank' => true));
		$this->hasColumn('personid', 'integer', null);
		$this->hasColumn('userid', 'integer', null);
		$this->hasColumn('role', 'integer', null, array('notnull' => true, 'notblank' => true, 'default' => 0)); # Other - 0, Clan Admin - 1, Clan Contact Person - 2, Clan Head - 3, Clan Katikiro - 4, Clan VIP - 5
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
  									"organisationid.notblank" => $this->translate->_("organisationcontact_organisationid_error"),
  									"roleid.notblank" => $this->translate->_("organisationcontact_role_error")
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
		if(isArrayKeyAnEmptyString('role', $formvalues)){
			unset($formvalues['role']); 
		}	
		parent::processPost($formvalues);
	}
	# determine the role text for each clan contact
	function getRoleText() {
		$text = '';
		$roleslookup = new LookupType();
		$roleslookup->setName("ALL_CLAN_ROLES");
		$rolesvalues = $roleslookup->getOptionValuesAndDescription();
		
		$text = $rolesvalues[$this->getRole()]; 
		/*switch ($this->getRole()) {
			case 0:
				$text = 'Other';
				break;
			case 1:
				$text = 'Clan Admin';
				break;
			case 2:
				$text = 'Clan Contact Person';
				break;
			case 3:
				$text = 'Clan Head';
				break;
			case 4:
				$text = 'Clan Katikiro';
				break;
			case 5:
				$text = 'Clan VIP';
				break;
			default:
				break;
		}*/
		return $text;
	}
}
?>