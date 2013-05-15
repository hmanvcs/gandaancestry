<?php

class Event extends BaseEntity {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('event');
		$this->hasColumn('name', 'string', 50);
		$this->hasColumn('userid', 'integer', null);
		$this->hasColumn('personid', 'integer', null);
		$this->hasColumn('eventtype', 'integer', null, array( 'notnull' => true, 'notblank' => true));
		
		$this->hasColumn('startday', 'integer', null);
		$this->hasColumn('startmonth', 'integer', null);
		$this->hasColumn('startyear', 'integer', null);
		$this->hasColumn('starttime', 'integer', null);
		$this->hasColumn('starttype', 'integer', null); // 1-Exactly, 2-Between -- and --, 3-From -- to --, 4-Before, 5-After, 6-Around, 7-From, 8-To, 9-Unsure date, 10-Free Text
		$this->hasColumn('starttext', 'string', 50);  
		
		$this->hasColumn('endday', 'integer', null);
		$this->hasColumn('endmonth', 'integer', null);
		$this->hasColumn('endyear', 'integer', null);
		$this->hasColumn('endtime', 'integer', null);
		$this->hasColumn('endtype', 'integer', null);
		$this->hasColumn('endtext', 'string', 50);
		
		$this->hasColumn('locationtype', 'integer', null);
		$this->hasColumn('locationid', 'integer', null);
		$this->hasColumn('locationtext', 'string', 50);
		$this->hasColumn('description', 'string', 255);
		$this->hasColumn('notes', 'string', 1000);
		$this->hasColumn('attachment', 'string', 50);
		$this->hasColumn('parentid', 'integer', null);
		
	}
	/**
	 * Contructor method for custom initialization
	 */
	public function construct() {
		parent::construct();
		// set the custom error messages
       	$this->addCustomErrorMessages(array(
       									"eventtype.notblank" => $this->translate->_("event_type_error")
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
		if(isArrayKeyAnEmptyString('startday', $formvalues)){
			$formvalues['startday'] = NULL;
		}
		if(isArrayKeyAnEmptyString('startmonth', $formvalues)){
			$formvalues['startmonth'] = NULL;
		}
		if(isArrayKeyAnEmptyString('startyear', $formvalues)){
			$formvalues['startyear'] = NULL;
		}
		if(isArrayKeyAnEmptyString('starttype', $formvalues)){
			$formvalues['starttype'] = NULL;
		}
		if(isArrayKeyAnEmptyString('endday', $formvalues)){
			$formvalues['endday'] = NULL;
		}
		if(isArrayKeyAnEmptyString('endmonth', $formvalues)){
			$formvalues['endmonth'] = NULL;
		}
		if(isArrayKeyAnEmptyString('endyear', $formvalues)){
			$formvalues['endyear'] = NULL;
		}
		if(isArrayKeyAnEmptyString('endtype', $formvalues)){
			unset($formvalues['endtype']);
		}
		if(isArrayKeyAnEmptyString('locationtype', $formvalues)){
			unset($formvalues['locationtype']); 
		}
		if(isArrayKeyAnEmptyString('locationid', $formvalues)){
			unset($formvalues['locationid']); 
		}
		if(isArrayKeyAnEmptyString('parentid', $formvalues)){
			unset($formvalues['parentid']); 
		}
        // debugMessage($formvalues); 
		parent::processPost($formvalues);
	}
	/**
     * Determine the label for start type sub type
     * @return String the life status 
     */
    function getStartTypeLabel(){
    	return LookupType::getLookupValueDescription("ALL_DATE_TYPES", $this->getStartType()); 
    }
    /**
     * Determine the start date of event
     * @return String the mysql date
     */
    function getFullStartDate(){
    	if(!isEmptyString($this->getStartYear()) && !isEmptyString($this->getStartMonth()) && !isEmptyString($this->getStartDay())){
    		return $this->getStartYear()."-".$this->getStartMonth()."-".$this->getStartDay();
    	} else {
    		return '';
    	}
    }
	/**
     * Determine the start date of event
     * @return String the mysql date
     */
    function getFullEndDate(){
    	if(!isEmptyString($this->getEndYear()) && !isEmptyString($this->getEndMonth()) && !isEmptyString($this->getEndDay())){
    		return $this->getEndYear()."-".$this->getEndMonth()."-".$this->getEndDay();
    	} else {
    		return '';
    	}
    }
    # for date of birth and allow to check if only year and monthe are specified.
    function getFormatedStart(){
    	
    }
}
?>