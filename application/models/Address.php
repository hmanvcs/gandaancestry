<?php

class Address extends BaseEntity {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('address');
		$this->hasColumn('userid', 'integer', null);
		$this->hasColumn('personid', 'integer', null);
		$this->hasColumn('organisationid', 'integer', null);
		$this->hasColumn('type', 'integer', null, array('default' => '1')); // 1 Home, 2 - Work, 3 - Other, 4 - Obutaka,  5 - Organisation Butaka, 6 - Organisation Embuga 
		$this->hasColumn('country', 'string', 2, array('default' => 'UG'));
		$this->hasColumn('county', 'string', 50);
		$this->hasColumn('state', 'string', 50);
		$this->hasColumn('zipcode', 'string', 15);
		$this->hasColumn('city', 'string', 50);
		$this->hasColumn('village', 'string', 50);
		$this->hasColumn('town', 'string', 50);
		$this->hasColumn('streetaddress', 'string', 255);
		$this->hasColumn('iscurrent', 'integer', null, array('default' => '1'));
		$this->hasColumn('visibility', 'integer', null, array('default' => '1'));
		$this->hasColumn('isprimary', 'integer', null, array('default' => '0'));
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
		$this->hasOne('Place as ssaza', 
								array(
									'local' => 'county',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Place as parish', 
								array(
									'local' => 'town',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Place as kyalo', 
								array(
									'local' => 'village',
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
		if(isArrayKeyAnEmptyString('iscurrent', $formvalues)){
			unset($formvalues['iscurrent']); 
		}
		if(isArrayKeyAnEmptyString('visibility', $formvalues)){
			unset($formvalues['visibility']); 
		}
		if(isArrayKeyAnEmptyString('isconfirmed', $formvalues)){
			unset($formvalues['isconfirmed']); 
		}		
		
		parent::processPost($formvalues);
	}
	/**
     * Determine the label for type
     * @return String the type of address
     */
    function getAddressTypeLabel(){
    	return LookupType::getLookupValueDescription("ALL_ADDRESS_TYPES", $this->getType()); 
    }
	/**
	 * Return the user's full physical address
	 *
	 * @return String The full physical address
	 */
	function getFullAddress() {
		$textstring = "";
		
		if(!isEmptyString($this->getStreetAddress())){
			$textstring .= "".$this->getStreetAddress()."<br />";
		}
		switch($this->getCountry()) {
			case 'UG':
				if(!isEmptyString($this->getVillage())){
					$textstring .= $this->getVillage().",&nbsp;";
				}
				if(!isEmptyString($this->getCity())){
					$textstring .= $this->getCity();
				}
				break;
			case 'CA':
			case 'US':
				if(!isEmptyString($this->getCity())){
					$textstring .= $this->getCity().",&nbsp;";
				}
				if(!isEmptyString($this->getZipCode())){
					$textstring .= $this->getZipCode().",&nbsp;";
				}
				if(!isEmptyString($this->getState())){
					$textstring .= $this->getState();
				}
				break;
			case 'XX':
				if(!isEmptyString($this->getVillage())){
					$textstring .= $this->getVillageName().",&nbsp;";
				}
				if(!isEmptyString($this->getCounty())){
					$textstring .= $this->getCountyName();
				}
				break;
			default:
				if(!isEmptyString($this->getVillage())){
					$textstring .= $this->getVillage().",&nbsp;";
				}
				if(!isEmptyString($this->getCounty())){
					$textstring .= $this->getCounty().",&nbsp;";
				}
				if(!isEmptyString($this->getCity())){
					$textstring .= $this->getCity();
				}
				break;
		}
		if(!isEmptyString($this->getCountry())){
			$textstring .= "<br />".$this->getCountryName();
		}
		return $textstring;
	}
	/**
	 * Get the full name of the state from the two digit code
	 * 
	 * @return String The full name of the state 
	 */
	function getStateName() {
		if(isEmptyString($this->getState())){
			$currentstate = "";
		}
		if(strlen(getStates() == '2')) {
			if($this->getCountry() == 'US'){
				$states = getStates(); 
				$currentstate = $states[$this->getState()];
			}
		} else {
			$currentstate = $this->getState();
		}
		
		return $currentstate; 
	}
	# determine if address is a buganda address
	function isBugandaAddress(){
		return $this->getCountry() == "XX" ? true : false;
	}
	/**
	 * Get the full name of the country from the two digit code
	 * 
	 * @return String The full name of the state 
	 */
	function getCountryName() {
		if(isEmptyString($this->getCountry())){
			return "";
		}
		if($this->isBugandaAddress()){
			return "Buganda";
		}
		$countries = getCountries(); 
		return $countries[$this->getCountry()]; 
	}
	/**
	 * Get the full name of the country or ssaza
	 * 
	 * @return String The full name of the county. if buganda return ssaza name from places 
	 */
	function getCountyName() {
		$txt = $this->getCounty();
		if($this->isBugandaAddress() && is_numeric($this->getCounty())){
			$txt = $this->getSsaza()->getName();
		}
		return $txt; 
	}
	/**
	 * Get the full name of the town 
	 * 
	 * @return String The full name of the county. if buganda return Ggombolola name from places 
	 */
	function getTownName() {
		$txt = $this->getTown();
		if($this->isBugandaAddress() && is_numeric($this->getTown())){
			$txt = $this->getParish()->getName();
		}
		return $txt; 
	}
	function getTownID() {
		$txt = NULl;
		if($this->isBugandaAddress() && is_numeric($this->getTown())){
			$txt = $this->getTown();
		}
		return $txt; 
	}
	/**
	 * Get the full name of the village 
	 * 
	 * @return String The full name of the village. if buganda return ekyalo name from places 
	 */
	function getVillageName() {
		$txt = $this->getVillage();
		if($this->isBugandaAddress() && is_numeric($this->getVillage())){
			$txt = $this->getKyalo()->getName();
		}
		return $txt; 
	}
	function getVillageID() {
		$txt = NULl;
		if($this->isBugandaAddress() && is_numeric($this->getVillage())){
			$txt = $this->getVillage();
		}
		return $txt; 
	}
	/**
     * Determine if a person is muganda
     * @return bool
     */
    function isThePrimary(){
    	return $this->getIsPrimary() == '1' ? true : false; 
    }
    /**
     * Determine the primary text displayed against primary contact
     * @return bool
     */
    function getPrimaryLabel() {
    	if($this->getIsPrimary()){
    		return " <b>(Primary)</b>";
    	} else {
    		return "";
    	}
    }
    /**
	 * Return the user's full physical address
	 *
	 * @return String The full physical address
	 */
	function getButakaAddressLabel() {
		$txt = '';
		if(!isEmptyString($this->getVillage())){
			$txt .= $this->getVillageName().', ';
		}
		if(!isEmptyString($this->getTown())){
			$txt .= $this->getTownName().', ';
		}
		if(!isEmptyString($this->getCounty())){
			$txt .= $this->getCountyName();
		}
		return $txt;
	}
}
?>