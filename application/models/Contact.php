<?php

class Contact extends BaseEntity {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('contact');
		$this->hasColumn('userid', 'integer', null);
		$this->hasColumn('personid', 'integer', null);
		$this->hasColumn('organisationid', 'integer', null);
		$this->hasColumn('type', 'integer', null); // 1-Email, 2-Phone Number, 3-Web Address
		$this->hasColumn('subtype', 'integer', null);
		$this->hasColumn('value1', 'string', 500);
		$this->hasColumn('value2', 'string', 500);
		$this->hasColumn('value3', 'string', 500);
		$this->hasColumn('isprimary', 'integer', null, array('default' => '0'));
		$this->hasColumn('unconfirmed', 'integer', null);		
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
		if(isArrayKeyAnEmptyString('userid', $formvalues)){
			unset($formvalues['userid']); 
		}
		if(isArrayKeyAnEmptyString('personid', $formvalues)){
			unset($formvalues['personid']); 
		}
		if(isArrayKeyAnEmptyString('organisationid', $formvalues)){
			unset($formvalues['organisationid']); 
		}
		if(isArrayKeyAnEmptyString('type', $formvalues)){
			unset($formvalues['type']); 
		}
		if(isArrayKeyAnEmptyString('subtype', $formvalues)){
			unset($formvalues['subtype']); 
		}
		if(isArrayKeyAnEmptyString('iscurrent', $formvalues)){
			unset($formvalues['iscurrent']); 
		}
		if(isArrayKeyAnEmptyString('isprimary', $formvalues)){
			unset($formvalues['isprimary']); 
		}
		if(isArrayKeyAnEmptyString('visibility', $formvalues)){
			unset($formvalues['visibility']); 
		}		
		parent::processPost($formvalues);
	}
	/**
     * Determine the label for email sub type
     * @return String the life status 
     */
    function getEmailSubTypeLabel(){
    	return LookupType::getLookupValueDescription("ALL_EMAIL_TYPES", $this->getSubType()); 
    }
	/**
     * Determine the label for phone sub type
     * @return String the life status 
     */
    function getPhoneSubTypeLabel(){
    	return LookupType::getLookupValueDescription("ALL_PHONE_TYPES", $this->getSubType()); 
    }
	/**
     * Determine the label for phone sub type
     * @return String the life status 
     */
    function getWebSubTypeLabel(){
    	return LookupType::getLookupValueDescription("ALL_WEB_SERVICES", $this->getSubType()); 
    }
	/**
     * Determine if a person is muganda
     * @return bool
     */
    function isThePrimary(){
    	return $this->getIsPrimary() == '1' || $this->getValue1() == $this->getPerson()->getUser()->getEmail() ? true : false; 
    }
    /**
     * Determine the primary text displayed against primary contact
     * @return bool
     */
    function getPrimaryLabel() {
    	if($this->getIsPrimary()){
    		return "<b>(Primary)</b>";
    	} else {
    		return "";
    	}
    }
	/**
     * Determine the primary text displayed against primary contact
     * @return bool
     */
    function getLoginEmailLabel() {
    	if($this->getIsPrimary()){
    		return "<b>(Used to login)</b>";
    	} else {
    		return "";
    	}
    }
}
?>