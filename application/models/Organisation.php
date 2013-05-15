<?php

class Organisation extends BaseEntity {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('organisation');
		$this->hasColumn('type', 'integer', null, array('notnull' => true, 'notblank' => true));
		$this->hasColumn('fullname', 'string', 255, array('notnull' => true, 'notblank' => true));
		$this->hasColumn('shortname', 'string', 255);
		$this->hasColumn('engname', 'string', 255);
		$this->hasColumn('omuziro', 'string', 255);
		
		$this->hasColumn('clanid', 'integer', null);
		$this->hasColumn('ssigaid', 'integer', null);
		$this->hasColumn('mutubaid', 'integer', null);
		$this->hasColumn('lunyiririid', 'integer', null);
		$this->hasColumn('nyumbaid', 'integer', null);
		$this->hasColumn('parentid', 'integer', null);
		
		$this->hasColumn('omubala', 'string', 500);
		$this->hasColumn('akabbiro', 'string', 500);
		$this->hasColumn('leader', 'string', 255);
		$this->hasColumn('katikiro', 'string', 255);
		$this->hasColumn('leaderid', 'integer', null);
		$this->hasColumn('katikiroid', 'integer', null);
		
		$this->hasColumn('butakaid', 'integer', null);
		$this->hasColumn('embugaid', 'integer', null);
		$this->hasColumn('contactpersonid', 'integer', null);
		$this->hasColumn('contactname', 'string', 255);
		$this->hasColumn('contactphone', 'string', 15);
		
		$this->hasColumn('bio', 'string', 65535);
		$this->hasColumn('history', 'string', 65535);
		$this->hasColumn('elderinfo', 'string', 65535);
		$this->hasColumn('notes', 'string', 65535);
		$this->hasColumn('totemupload', 'string', 255);
		$this->hasColumn('flagupload', 'string', 255);
		$this->hasColumn('coverphotoupload', 'string', 255);
		$this->hasColumn('flagname', 'string', 255);
		$this->hasColumn('totemname', 'string', 255);
		
	}
	/**
	 * Contructor method for custom initialization
	 */
	public function construct() {
		parent::construct();
		
		// set the custom error messages
  		$this->addCustomErrorMessages(array(
  									"type.notblank" => $this->translate->_("organisation_type_error"),
  									"fullname.notblank" => $this->translate->_("organisation_fullname_error")
  	       						));
	}
	/**
	 * Model relationships
	 */
	public function setUp() {
		parent::setUp();
		
		$this->hasOne('Organisation as parent', 
								array(
									'local' => 'parentid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Organisation as clan', 
								array(
									'local' => 'clanid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Organisation as ssiga', 
								array(
									'local' => 'ssigaid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Organisation as mutuba', 
								array(
									'local' => 'clanid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Organisation as lunyiriri', 
								array(
									'local' => 'lunyiririid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Organisation as nyumba', 
								array(
									'local' => 'nyumbaid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Person as clanhead', 
								array(
									'local' => 'leaderid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Person as clankatikiro', 
								array(
									'local' => 'katikiroid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Person as contactperson', 
								array(
									'local' => 'contactpersonid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Person as head', 
								array(
									'local' => 'leaderid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Person as vice', 
								array(
									'local' => 'katikiroid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Address as butaka', 
								array(
									'local' => 'butakaid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Address as embuga', 
								array(
									'local' => 'embugaid',
									'foreign' => 'id',
								)
						);
		$this->hasMany('Contact as contacts',
								array(
									'local' => 'id',
									'foreign' => 'organisationid'
								)
						);
		$this->hasMany('Address as addresses',
								array(
									'local' => 'id',
									'foreign' => 'organisationid'
								)
						);
		$this->hasMany('OrganisationContact as clanpersons',
								array(
									'local' => 'id',
									'foreign' => 'organisationid'
								)
						);
		$this->hasMany('OrganisationName as clannames',
								array(
									'local' => 'id',
									'foreign' => 'organisationid'
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
		if(isArrayKeyAnEmptyString('clanid', $formvalues)){
			unset($formvalues['clanid']); 
		}
		if(isArrayKeyAnEmptyString('ssigaid', $formvalues)){
			unset($formvalues['ssigaid']); 
		}
		if(isArrayKeyAnEmptyString('mutubaid', $formvalues)){
			unset($formvalues['mutubaid']); 
		}
		if(isArrayKeyAnEmptyString('lunyiririid', $formvalues)){
			unset($formvalues['lunyiririid']); 
		}
		if(isArrayKeyAnEmptyString('nyumbaid', $formvalues)){
			unset($formvalues['nyumbaid']); 
		}
		if(isArrayKeyAnEmptyString('leaderid', $formvalues)){
			unset($formvalues['leaderid']); 
		}
		if(isArrayKeyAnEmptyString('katikiroid', $formvalues)){
			unset($formvalues['katikiroid']); 
		}
		if(isArrayKeyAnEmptyString('butakaid', $formvalues)){
			unset($formvalues['butakaid']); 
		}
		if(isArrayKeyAnEmptyString('embugaid', $formvalues)){
			unset($formvalues['embugaid']); 
		}
		if(isArrayKeyAnEmptyString('contactpersonid', $formvalues)){
			unset($formvalues['contactpersonid']); 
		}
		
		$locations = array();
		// physical addresses
		if(!isArrayKeyAnEmptyString('places', $formvalues)){
			// debugMessage($formvalues);			
			foreach ($formvalues['places'] as $key => $value){
				if(!isArrayKeyAnEmptyString('places_type_'.$key, $formvalues)){
					if(isEmptyString($formvalues['places_type_'.$key]) || isEmptyString($formvalues['places_country_'.$key]) || isEmptyString($value['streetaddress'])){
						unset($value[$key]);
					} else {
						$places[$key]['organisationid'] = NULL;
						if(!isArrayKeyAnEmptyString('id', $value)){
							$places[$key]['id'] = $value['id'];
						}
						$places[$key]['organisationid'] = $formvalues['id'];
						$places[$key]['type'] = $formvalues['places_type_'.$key];
						$places[$key]['country'] = $formvalues['places_country_'.$key];
						if(!isArrayKeyAnEmptyString('city', $value)){
							$places[$key]['city'] = $value['city'];
						}
						if(!isArrayKeyAnEmptyString('state', $value)){
							$places[$key]['state'] = $value['state'];
						}
						if(!isArrayKeyAnEmptyString('zipcode', $value)){
							$places[$key]['zipcode'] = $value['zipcode'];
						}
						if(!isArrayKeyAnEmptyString('places_ssaza_'.$key, $formvalues)){
							$places[$key]['county'] = $formvalues['places_ssaza_'.$key];
							$places[$key]['state'] = "";
							$places[$key]['zipcode'] = "";
						} else {
							if(!isArrayKeyAnEmptyString('county', $value)){
								$places[$key]['county'] = $value['county'];
							} else {
								$places[$key]['county'] = NULL;
							}
						}
						
						$butaka = $this->getButaka();
						// debugMessage($butaka->toArray());
						if(!isArrayKeyAnEmptyString('places_town_'.$key, $formvalues)){
							$places[$key]['town'] = $formvalues['places_town_'.$key];
						} else {
							$places[$key]['town'] = NULL;
						}
						if(!isArrayKeyAnEmptyString('places_village_'.$key, $formvalues)){
							$places[$key]['village'] = $formvalues['places_village_'.$key];
						} else {
							$places[$key]['village'] = NULL;
						}
						$places[$key]['streetaddress'] = $value['streetaddress'];
						if($places[$key]['country'] == 'UG' || $places[$key]['country'] == 'XX'){
							$places[$key]['state'] = "";
							$places[$key]['zipcode'] = "";
						}
						// set primary
						$places[$key]['isprimary'] = 0;
						if(!isArrayKeyAnEmptyString('primary_address', $formvalues)){
							if(strval($key) == strval($formvalues['primary_address'])){
								$places[$key]['isprimary'] = 1;
							}
						}
						$locations[$key] = $places[$key]; //debugMessage($locations);
					}
				}
			}
			$formvalues['addresses'] = $locations;
		}
		if(!isArrayKeyAnEmptyString('butaka', $formvalues)){
			if(!isArrayKeyAnEmptyString('clanid', $formvalues)){
				$formvalues['butaka']['organisationid'] = $formvalues['clanid'];
			}
			if(!isArrayKeyAnEmptyString('butaka_ssazaid', $formvalues)){
				$formvalues['butaka']['county'] = $formvalues['butaka_ssazaid'];
			}
			if(!isArrayKeyAnEmptyString('butaka_villageid', $formvalues)){
				$formvalues['butaka']['village'] = $formvalues['butaka_villageid'];
			}
		}
		// debugMessage($formvalues['butaka']);
		// exit();			
		parent::processPost($formvalues);
	}
	# clean up after save
	/*function afterSave(){
		// check if the butaka and Embuga addresses were updated and add them to the organisation object
		$but_address = $this->getTheObutaka();
		$update_obj = false;
		if($but_address){
			$this->setButakaID($but_address->getID());
			$update_obj = true;
		}
		if($update_obj){
			$this->save();
		}
		return true;
	}*/
	# clean up after update
	function afterUpdate(){
		// check if the butaka and Embuga addresses were updated and add them to the organisation object
		$but_address = $this->getTheObutaka();
		$update_obj = false;
		if($but_address){
			$this->setButakaID($but_address->getID());
			$update_obj = true;
		}
		if($update_obj){
			$this->save();
		}
		return true;
	}
	# determine fullname of the clan
	function getName() {
		$name = '';
		if(!isEmptyString($this->getFullName())){
			$name = $this->getFullName();
		}
	    return $name;
	}
	# determine if organisation is a clan
    function isClan(){
    	return $this->getType() == '1' ? true : false; 
    }
	# determine if organisation is a clan
    function isSsiga(){
    	return $this->getType() == '2' ? true : false; 
    }
	# Determine the people for this clan
    function getClanPeople() {
    	$q = Doctrine_Query::create()->from('Person p')->where("p.clanid = ".$this->getID())->orderby("p.firstname, p.lastname, p.clanname");
		$result = $q->execute();
		return $result;
    }
	# Determine the people for this ssiga
    function getSsigaPeople() {
    	$q = Doctrine_Query::create()->from('Person p')->where("p.ssigaid = ".$this->getID());
		$result = $q->execute();
		return $result;
    }
	# Determine the ssigas in a clan
    function getSsigas() {
    	$q = Doctrine_Query::create()->from('Organisation o')->where("o.type = '2' AND o.clanid = ".$this->getID()." ")->orderby("o.fullname ASC");
		$result = $q->execute();
		return $result;
    }
	# determine clan leader fromm organisaton contact
    function getTheLeader() {
    	$q = Doctrine_Query::create()->from('OrganisationContact l')->where("l.organisationid = ".$this->getID()." AND l.role = 1 ");
		$result = $q->fetchOne();
		return $result;
    }
	# determine clan katikiro fromm organisaton contact
    function getTheKatikiro() {
    	$q = Doctrine_Query::create()->from('OrganisationContact l')->where("l.organisationid = ".$this->getID()." AND l.role = 2 ");
		$result = $q->fetchOne();
		return $result;
    }
	# determine clan admins from organisaton contact
    function getTheAdmin() {
    	$q = Doctrine_Query::create()->from('OrganisationContact l')->where("l.organisationid = ".$this->getID()." AND l.role = 10 ");
		$result = $q->execute();
		return $result;
    }
	# determine clan vips from organisaton contact
    function getTheVIPS() {
    	$q = Doctrine_Query::create()->from('OrganisationContact l')->where("l.organisationid = ".$this->getID()." AND  (l.role < 10) ");
		$result = $q->execute();
		return $result;
    }
	# determine clan vips from organisaton contact
    function getAllVIPS() {
    	$q = Doctrine_Query::create()->from('OrganisationContact l')->where("l.organisationid = ".$this->getID()." AND (l.role < 10) ");
		$result = $q->execute();
		return $result;
    }
	# determine clan obutaka address
    function getTheObutaka() {
    	$q = Doctrine_Query::create()->from('Address a')->where("a.organisationid = ".$this->getID()." AND a.type = 5 ");
		$result = $q->fetchOne();
		return $result;
    }
	# determine clan headquaters address
    function getTheEmbuga() {
    	$q = Doctrine_Query::create()->from('Address a')->where("a.organisationid = ".$this->getID()." AND a.type = 6 ");
		$result = $q->fetchOne();
		return $result;
    }
	/**
	 * Determine if butaka address for organisation has been added to database
	 *
	 * @return true if present, false otherwise
	 */
	function hasButakaAddress() {
		if(!$this->getTheObutaka()){
			return false;
		}
		return true;
	}
    # determine the name of the clan leader
    function getHeadTitle() {
    	$head = '---';
    	if(!isEmptyString($this->getLeader())){
    		$head = $this->getLeader().$this->getHeadPerson();
    	}
    	$contact = $this->getTheLeader();
    	if($contact){
    		$head = (!isEmptyString($contact->getDescription()) ?  $contact->getDescription() : '').$this->getHeadPerson();
    	}
    	return $head;
    }
	# determine the name of the clan leader
    function getHeadPerson() {
    	$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
    	$head = '';
    	if(!isEmptyString($this->getLeaderID())){
    		$head = '<br /><a title="View Clan Head Profile" href="'.$baseUrl.'/person/view/id/'.encode($this->getLeaderID()).'" >('.$this->getClanHead()->getName().')</a>';
    	}
    	$contact = $this->getTheLeader();
    	if($contact){
    		if(!isEmptyString($contact->getPersonID())){
    			$head = '<br /><a title="View Clan Head Profile" href="'.$baseUrl.'/person/view/id/'.encode($contact->getPersonID()).'" >('.$contact->getPerson()->getName().')</a>';
    		}
    	}
    	return $head;
    }
	# determine the name of the clan katikiro
    function getKatikiroTitle() {
    	$kat = '---';
    	if(!isEmptyString($this->getKatikiro())){
    		$kat = $this->getKatikiro().$this->getVicePerson();
    	}
    	$contact = $this->getTheKatikiro();
    	if($contact){
    		$head = !isEmptyString($contact->getDescription()) ?  $contact->getDescription() : '';
    		$kat = $head.$this->getVicePerson();
    	}
    	return $kat;
    }
	# determine the name of the clan leader
    function getVicePerson() {
    	$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
    	$kat = '';
    	if(!isEmptyString($this->getKatikiroID())){
    		$kat = '<br /><a title="View Clan Katikiro Profile" href="'.$baseUrl.'/person/view/id/'.encode($this->getLeaderID()).'" >('.$this->getClanKatikiro()->getName().')</a>';
    	}
    	$contact = $this->getTheKatikiro();
    	if($contact){
    		if(!isEmptyString($contact->getPersonID())){
    			$kat = '<br /><a title="View Clan Katikiro Profile" href="'.$baseUrl.'/person/view/id/'.encode($contact->getPersonID()).'" >('.$contact->getPerson()->getName().')</a>';
    		}
    	}
    	return $kat;
    }
    # determine the obutaka ssaza
    function getObutakaSsaza() {
    	$txt = '---';
    	if(!isEmptyString($this->getButakaID())){
    		$txt = !isEmptyString($this->getButaka()->getCounty()) ?  $this->getButaka()->getButakaAddressLabel() : '---';
    	}
    	$ssaza = $this->getTheObutaka();
    	if($ssaza){
    		$txt = !isEmptyString($ssaza->getCounty()) ?  $ssaza->getButakaAddressLabel() : '---';
    	}
    	return $txt;
    }
	# determine the clan Embuga
    function getHeadQuaters() {
    	$txt = '---';
    	if(!isEmptyString($this->getEmbugaID())){
    		$txt = $this->getEmbuga()->getCounty();
    	}
    	$ssaza = $this->getTheEmbuga();
    	if($ssaza){
    		$txt = !isEmptyString($ssaza->getCounty()) ?  $ssaza->getButakaAddressLabel() : '---';
    	}
    	return $txt;
    }
    # return bio text
    function getBioText(){
    	$text = '--';
    	if(!isEmptyString($this->getBio())){
    		$text = $this->getBio();
    	}
    	return $text;
    }
    # return history text
    function getHistoryText() {
    	$text = '--';
    	if (!isEmptyString($this->getHistory())) {
    		$text = $this->getHistory();
    	}
    	return $text;
    }
	# Determine the clans for boys
    function getBoyNames() {
    	$q = Doctrine_Query::create()->from('OrganisationName o')->where("o.type = '1' AND o.organisationid = ".$this->getID()."")->orderby("o.name");
		$result = $q->execute();
		return $result;
    }
	# Determine the clans for boys
    function getGirlNames() {
    	$q = Doctrine_Query::create()->from('OrganisationName o')->where("o.type = '2' AND o.organisationid = ".$this->getID()."")->orderby("o.name");
		$result = $q->execute();
		return $result;
    }
    # Generate list of boy names
    function getListOfNames($type) {
    	$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
    	$list = '---';
    	$names = $type == 1 ? $this->getBoyNames() : $this->getGirlNames();
    	if($names){
    		$listarray = array();
    		foreach($names as $name){
    			$listarray[] = '<a href="'.$baseUrl.'/clan/addname/id/'.encode($name->getID()).'/pgc/true" rel="'.ucfirst($name->getName()).'" title="View Details" id="view_'.$name->getID().'" class="addclanname">'.ucfirst($name->getName()).'</a>';
    		}
    		if(count($listarray) == 0){
    			$list = '---';
    		} else {
    			$list = implode(', ', $listarray);
    		}
    	}
    	return $list;
    }
	# Generate list of girls names
    function getListOfGirlNames() {
    	$list = '---';
    	$names = $this->getGirlNames();
    	if($names){
    		$listarray = array();
    		foreach($names as $name){
    			$listarray[] = $name->getName();
    		}
    		if(count($listarray) == 0){
    			$list = '---';
    		} else {
    			$list = implode(', ', $listarray);
    		}
    	}
    	return $list;
    }
	/**
	 * Return the physical addresses for profile
	 *
	 * @return collection the physical address
	 */
	function getAllAddresses() {
		$q = Doctrine_Query::create()->from('Address a')->where("a.organisationid = '".$this->getID()."'");
		return $q->execute();
	}
	# determine if clan has cover image
	function hasCoverImage(){
		$real_path = APPLICATION_PATH."/../public/uploads/clans/clan_";
	 	
		$real_path = $real_path.$this->getID().DIRECTORY_SEPARATOR."large_cover_".$this->getCoverPhotoUpload();
		if(file_exists($real_path) && !isEmptyString($this->getCoverPhotoUpload())){
			return true;
		}
		return false;
	}
	# determine path to cover photo
	function getCoverPhotoPath() {
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$path = $baseUrl.'/uploads/clans/default/cover.jpg';
		if($this->hasCoverImage()){
			$path = $baseUrl.'/uploads/clans/clan_'.$this->getID().'/cover_'.$this->getCoverPhotoUpload();
		}
		return $path;
	}
	# determine path to totem large image
	function getLargeCoverPath() {
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$path = $baseUrl.'/uploads/clans/default/large_cover.jpg';
		if($this->hasCoverImage()){
			$path = $baseUrl.'/uploads/clans/clan_'.$this->getID().'/large_cover_'.$this->getCoverPhotoUpload();
		}
		return $path;
	}
	# determine if clan has totem image
	function hasTotemImage(){
		$real_path = APPLICATION_PATH."/../public/uploads/clans/clan_";
	 	
		$real_path = $real_path.$this->getID().DIRECTORY_SEPARATOR."large_logo_".$this->getTotemUpload();
		if(file_exists($real_path) && !isEmptyString($this->getTotemUpload())){
			return true;
		}
		return false;
	}
	# determine path to totem picture
	function getTotemPath() {
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$path = $baseUrl.'/uploads/clans/default/medium_logo.jpg';
		if($this->hasTotemImage()){
			$path = $baseUrl.'/uploads/clans/clan_'.$this->getID().'/medium_logo_'.$this->getTotemUpload();
		}
		return $path;
	}
	function getTotemThumbnailPath() {
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$path = $baseUrl.'/uploads/clans/default/thumbnail_logo.jpg';
		if($this->hasTotemImage()){
			$path = $baseUrl.'/uploads/clans/clan_'.$this->getID().'/thumbnail_logo_'.$this->getTotemUpload();
		}
		return $path;
	}
	# determine path to totem large image
	function getLargeTotemPath() {
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$path = $baseUrl.'/uploads/clans/default/large_logo.jpg';
		//if($this->hasTotemImage()){
			$path = $baseUrl.'/uploads/clans/clan_'.$this->getID().'/large_logo_'.$this->getTotemUpload();
		//}
		return $path;
	}
	# determine if clan has flag image
	function hasFlagImage(){
		$real_path = APPLICATION_PATH."/../public/uploads/clans/clan_";
	 	
		$real_path = $real_path.$this->getID().DIRECTORY_SEPARATOR."thumbnail_flag_".$this->getFlagUpload();
		if(file_exists($real_path) && !isEmptyString($this->getFlagUpload())){
			return true;
		}
		return false;
	}
	# determine path to flag picture
	function getFlagPath() {
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$path = $baseUrl.'/uploads/clans/default/thumbnail_flag.jpg';
		if($this->hasFlagImage()){
			$path = $baseUrl.'/uploads/clans/clan_'.$this->getID().'/thumbnail_flag_'.$this->getFlagUpload();
		}
		return $path;
	}
	# determine path to totem large image
	function getLargeFlagPath() {
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$path = $baseUrl.'/uploads/clans/default/large_flag.jpg';
		//if($this->hasTotemImage()){
			$path = $baseUrl.'/uploads/clans/clan_'.$this->getID().'/large_flag_'.$this->getFlagUpload();
		//}
		return $path;
	}
	# determine random selection for featured clan members
  	function getFeaturedClanMembers() {
  		$q = Doctrine_Query::create()->from('Person p')->where("p.clanid = '".$this->getID()."' ")->limit(6)->orderby('p.photo DESC, rand()');
		$result = $q->execute();
		return $result;
  	}
	# determine random selection for featured clan members
  	function getFeaturedClan() {
  		$q = Doctrine_Query::create()->from('Organisation o')->where("o.type = 1")->limit(1)->orderby('o.totemupload DESC, rand()');
		$result = $q->execute();
		return $result;
  	}
}
?>