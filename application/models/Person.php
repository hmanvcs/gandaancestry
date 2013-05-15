<?php

class Person extends BaseEntity {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('person');
		$this->hasColumn('userid', 'integer', null);
		$this->hasColumn('ownerid', 'integer', null);
		$this->hasColumn('addedbyid', 'integer', null);
		$this->hasColumn('familyid', 'integer', null);
		$this->hasColumn('type', 'integer', null, array('default' => '1')); // 1=>Muganda, 2=>Mujjwa, 3=>Other
		
		$this->hasColumn('firstname', 'string', 255, array( 'notnull' => true, 'notblank' => true));
		$this->hasColumn('lastname', 'string', 255, array('notnull' => true, 'notblank' => true));
		$this->hasColumn('clanname', 'string', 255);
		$this->hasColumn('othernames', 'string', 255);
		$this->hasColumn('maidenname', 'string', 255);
		$this->hasColumn('screenname', 'string', 255);
		$this->hasColumn('alias', 'string', 255);
		$this->hasColumn('prefix', 'string', 15);
		$this->hasColumn('suffix', 'integer', null);
		$this->hasColumn('dateofbirth','date', null, array('default' => NULL));
		$this->hasColumn('gender', 'integer', null); // 1=Male, 2=Female, 3=Unknown
		$this->hasColumn('lifestatus', 'integer', null, array('default' => '1')); // 1=Alive, 2=Deceased, 3=Unknown
		$this->hasColumn('currentlocation', 'string', 50);
		
		$this->hasColumn('clanid', 'integer', null); // person's clan
		$this->hasColumn('ssigaid', 'integer', null); // person's ssiga
		$this->hasColumn('mutubaid', 'integer', null); // person's mutuba
		$this->hasColumn('lunyiririid', 'integer', null); // person's lunyiriri
		$this->hasColumn('nyumbaid', 'integer', null); // person's nyumba
		$this->hasColumn('butakaid', 'integer', null); // person's butaka address
		
		$this->hasColumn('bio', 'string', 1000);
		$this->hasColumn('photo', 'string', 50);
		$this->hasColumn('haircolor', 'integer', null);
		$this->hasColumn('eyecolor', 'integer', null);
		$this->hasColumn('height', 'decimal', 10, array('default' => NULL));
		$this->hasColumn('weight', 'decimal', 10, array('default' => NULL));
		$this->hasColumn('ethinicity', 'integer', null);
		$this->hasColumn('religion', 'integer', null); 
		
		$this->hasColumn('email', 'string', 255);
		$this->hasColumn('isinvited', 'integer', null, array('default' => 0));
		$this->hasColumn('invitedbyid', 'integer', null);
		$this->hasColumn('hasacceptedinvite', 'integer', null, array('default' => 0));
		$this->hasColumn('dateinvited','date');
		$this->hasColumn('statusflag', 'integer', null, array('default' => 1)); // 1 normal, 2 beta, 3 marked for delete
		
	}
	/**
	 * Contructor method for custom initialization
	 */
	public function construct() {
		parent::construct();
		
		$this->addDateFields(array("dateofbirth","dateinvited"));
		
		// set the custom error messages
       	$this->addCustomErrorMessages(array(
       									"firstname.notblank" => $this->translate->_("person_firstname_error"),
       									"surname.notblank" => $this->translate->_("person_surname_error"),
       									"weight.type" => "Please enter a valid value of weight",
       	       						));
	}
	/**
	 * Model relationships
	 */
	public function setUp() {
		parent::setUp(); 
		
		$this->hasOne('UserAccount as user', 
								array(
									'local' => 'userid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('UserAccount as owner', 
								array(
									'local' => 'ownerid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Person as invitedby', 
								array(
									'local' => 'invitedbyid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Person as addedby', 
								array(
									'local' => 'addedbyid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Person as inviter', 
								array(
									'local' => 'invitedbyid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Family as family', 
								array(
									'local' => 'familyid',
									'foreign' => 'id',
								)
						);
		$this->hasMany('Family as ffamilies',
								array(
									'local' => 'id',
									'foreign' => 'fatherid'
								)
						);
		$this->hasMany('Family as mfamilies',
								array(
									'local' => 'id',
									'foreign' => 'motherid'
								)
						);
		$this->hasMany('FamilyTree as familytrees',
								array(
									'local' => 'id',
									'foreign' => 'treeid'
								)
						);
		$this->hasMany('Event as events',
								array(
									'local' => 'id',
									'foreign' => 'personid'
								)
		);
		$this->hasOne('Clan as clan', 
								array(
									'local' => 'clanid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Ssiga as ssiga', 
								array(
									'local' => 'ssigaid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Mutuba as mutuba', 
								array(
									'local' => 'clanid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Lunyiriri as lunyiriri', 
								array(
									'local' => 'lunyiririid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Nyumba as nyumba', 
								array(
									'local' => 'nyumbaid',
									'foreign' => 'id',
								)
						);
		$this->hasMany('Address as addresses',
								array(
									'local' => 'id',
									'foreign' => 'personid'
								)
						);
		$this->hasMany('Contact as contacts',
								array(
									'local' => 'id',
									'foreign' => 'personid'
								)
						);
		$this->hasMany('Interest as interests',
								array(
									'local' => 'id',
									'foreign' => 'personid'
								)
						);
		$this->hasOne('Privacy as privacyline',
								array(
									'local' => 'id',
									'foreign' => 'personid'
								)
						);
		$this->hasMany('Beta as betaentry',
								array(
									'local' => 'id',
									'foreign' => 'personid'
								)
						);
	}
	
	/**
	 * Preprocess model data
	 */
	function processPost($formvalues){
		// debugMessage($formvalues);
		// set default values for integers, dates, decimals
		if(isArrayKeyAnEmptyString('userid', $formvalues)){
			unset($formvalues['userid']); 
		}
		if(isArrayKeyAnEmptyString('ownerid', $formvalues)){
			unset($formvalues['ownerid']); 
		}
		if(isArrayKeyAnEmptyString('addedbyid', $formvalues)){
			unset($formvalues['addedbyid']); 
		}
		if(isArrayKeyAnEmptyString('familyid', $formvalues)){
			unset($formvalues['familyid']); 
		}
		if(isArrayKeyAnEmptyString('type', $formvalues)){
			unset($formvalues['type']); 
		}
		if(isArrayKeyAnEmptyString('clanid', $formvalues)){
			unset($formvalues['clanid']); 
		}
		if(isArrayKeyAnEmptyString('clanid', $formvalues) && !isArrayKeyAnEmptyString('pclanid', $formvalues)){
			$formvalues['clanid'] = $formvalues['pclanid']; 
		}
		if(!isArrayKeyAnEmptyString('theclanid', $formvalues)){
			$formvalues['clanid'] = $formvalues['theclanid'];
		}
		
		if(!isArrayKeyAnEmptyString('clanname_t', $formvalues)){
			$formvalues['clanname'] = $formvalues['clanname_t']; 
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
		if(isArrayKeyAnEmptyString('butakaid', $formvalues)){
			unset($formvalues['butakaid']); 
		}
		if(isArrayKeyAnEmptyString('suffix', $formvalues)){
			unset($formvalues['suffix']); 
		}
		if(isArrayKeyAnEmptyString('gender', $formvalues)){
			unset($formvalues['gender']); 
		}
		if(isArrayKeyAnEmptyString('lifestatus', $formvalues)){
			unset($formvalues['lifestatus']); 
		}
		if(isArrayKeyAnEmptyString('eyecolor', $formvalues)){
			unset($formvalues['eyecolor']); 
		}
		if(isArrayKeyAnEmptyString('haircolor', $formvalues)){
			unset($formvalues['haircolor']); 
		}
		if(isArrayKeyAnEmptyString('ethinicity', $formvalues)){
			unset($formvalues['ethinicity']); 
		}
		if(isArrayKeyAnEmptyString('religion', $formvalues)){
			unset($formvalues['religion']); 
		}
		if(isArrayKeyAnEmptyString('weight', $formvalues)){
			unset($formvalues['weight']); 
		}
		if(!isArrayKeyAnEmptyString('feet', $formvalues) && isArrayKeyAnEmptyString('inches', $formvalues)){
			$formvalues['height'] = $formvalues['feet'].".0"; 
		}
		if(!isArrayKeyAnEmptyString('feet', $formvalues) && !isArrayKeyAnEmptyString('inches', $formvalues)){
			$formvalues['height'] = $formvalues['feet'].".".$formvalues['inches']; 
		}
		if(isArrayKeyAnEmptyString('isinvited', $formvalues)){
			unset($formvalues['isinvited']);
		}
		if(isArrayKeyAnEmptyString('hasacceptedinvite', $formvalues)){
			unset($formvalues['hasacceptedinvite']); 
		}
		if(isArrayKeyAnEmptyString('dateinvited', $formvalues)){
			unset($formvalues['dateinvited']); 
		}
		
		$events = array();
		$theevents = $this->getAllEvents();
		$existing_events = $theevents->toArray();
		// debugMessage($existing_events);
		// preprocess birth info
		if(!isArrayKeyAnEmptyString('dateofbirth', $formvalues) || !isArrayKeyAnEmptyString('birthday', $formvalues) || !isArrayKeyAnEmptyString('birthmonth', $formvalues)  || !isArrayKeyAnEmptyString('birthyear', $formvalues)) {
			if(isArrayKeyAnEmptyString('birthday', $formvalues)){
				$formvalues['birthday'] = NULL; 
			}
			if(isArrayKeyAnEmptyString('birthmonth', $formvalues)){
				$formvalues['birthmonth'] = NULL; 
			}
			if(isArrayKeyAnEmptyString('birthyear', $formvalues)){
				$formvalues['birthyear'] = NULL; 
			}
			if(isArrayKeyAnEmptyString('birthyear', $formvalues)){
				$formvalues['birthyear'] = NULL; 
			}
			if(isArrayKeyAnEmptyString('starttype', $formvalues)){
				$formvalues['starttype'] = NULL; 
			}
			if(isArrayKeyAnEmptyString('e_starttype', $formvalues)){
				$formvalues['e_starttype'] = NULL; 
			}
			if(isArrayKeyAnEmptyString('starttext', $formvalues)){
				$formvalues['starttext'] = NULL; 
			}
			if(isArrayKeyAnEmptyString('placeofbirth', $formvalues)){
				$formvalues['placeofbirth'] = NULL; 
			}
			if(isArrayKeyAnEmptyString('e_birthday', $formvalues)){
				$formvalues['e_birthday'] = NULL; 
			}
			if(isArrayKeyAnEmptyString('e_birthmonth', $formvalues)){
				$formvalues['e_birthmonth'] = NULL; 
			}
			if(isArrayKeyAnEmptyString('e_birthyear', $formvalues)){
				$formvalues['e_birthyear'] = NULL; 
			}
			
			// if date got updated by select fields only, update birth field on person
			$dobevent = $this->getBirthDetails();
			
			if(!$dobevent){
				// debugMessage('no event');
				$events[md5(0)] = array(
					"eventtype"=>"1", 
					"starttype"=>$formvalues['starttype'], 
					"startday"=>$formvalues['birthday'], 
					"startmonth"=>$formvalues['birthmonth'], 
					"startyear"=>$formvalues['birthyear'],
					"starttext"=>$formvalues['starttext'],  
					"endtype"=>$formvalues['e_starttype'], 
					"endday"=>$formvalues['e_birthday'], 
					"endmonth"=>$formvalues['e_birthmonth'], 
					"endyear"=>$formvalues['e_birthyear'],
					"locationtext"=> $formvalues['placeofbirth'],
					"createdby" => 1
				);
			} else {
				if(isArrayKeyAnEmptyString('starttype', $formvalues)){
					$formvalues['starttype'] = $dobevent->getStartType();
				}
				if(isArrayKeyAnEmptyString('placeofbirth', $formvalues)){
					$formvalues['placeofbirth'] = $dobevent->getLocationText();
				}
				if(isEmptyString($formvalues['birthday'])){
					$formvalues['birthday'] = $formvalues['birthday'];
				}
				if(isEmptyString($formvalues['birthmonth'])){
					$formvalues['birthmonth'] = $formvalues['birthmonth'];
				}
				if(isEmptyString($formvalues['birthyear'])){
					$formvalues['birthyear'] = $formvalues['birthyear'];
				}
				$b_key = array_search_key_by_id($existing_events, $dobevent->getID());
				$events[$b_key] = array(
					"id"=> $dobevent->getID(), 
					"eventtype"=>"1",
					"starttype"=>$formvalues['starttype'], 
					"startday"=>$formvalues['birthday'], 
					"startmonth"=>$formvalues['birthmonth'], 
					"startyear"=>$formvalues['birthyear'], 
					"starttext"=>$formvalues['starttext'],  
					"endtype"=>$formvalues['e_starttype'], 
					"endday"=>$formvalues['e_birthday'], 
					"endmonth"=>$formvalues['e_birthmonth'], 
					"endyear"=>$formvalues['e_birthyear'],
					"locationtext"=> $formvalues['placeofbirth'],
				);				
			}
			// debugMessage($formvalues); exit();
		}
		// preprocess death info
		if(!isArrayKeyAnEmptyString('dateofdeath', $formvalues) || !isArrayKeyAnEmptyString('deathday', $formvalues) || !isArrayKeyAnEmptyString('deathmonth', $formvalues)  || !isArrayKeyAnEmptyString('deathyear', $formvalues)) {
			if(isArrayKeyAnEmptyString('deathday', $formvalues)){
				$formvalues['deathday'] = NULL; 
			}
			if(isArrayKeyAnEmptyString('deathmonth', $formvalues)){
				$formvalues['deathmonth'] = NULL; 
			}
			if(isArrayKeyAnEmptyString('deathyear', $formvalues)){
				$formvalues['deathyear'] = NULL; 
			}
			if(isArrayKeyAnEmptyString('de_starttype', $formvalues)){
				$formvalues['de_starttype'] = NULL; 
			}
			if(isArrayKeyAnEmptyString('d_starttext', $formvalues)){
				$formvalues['d_starttext'] = NULL; 
			}
			if(isArrayKeyAnEmptyString('e_deathday', $formvalues)){
				$formvalues['e_deathday'] = NULL; 
			}
			if(isArrayKeyAnEmptyString('e_deathmonth', $formvalues)){
				$formvalues['e_deathmonth'] = NULL; 
			}
			if(isArrayKeyAnEmptyString('e_deathyear', $formvalues)){
				$formvalues['e_deathyear'] = NULL; 
			}
			$dodevent = $this->getDeathDetails();
			if(!$dodevent){
				$events[md5(1)] = array(
					"eventtype"=>"2",
					"starttype"=>$formvalues['d_starttype'], 
					"startday"=>$formvalues['deathday'], 
					"startmonth"=>$formvalues['deathmonth'], 
					"startyear"=>$formvalues['deathyear'], 
					"starttext"=>$formvalues['d_starttext'],  
					"endtype"=>$formvalues['de_starttype'], 
					"endday"=>$formvalues['e_deathday'], 
					"endmonth"=>$formvalues['e_deathmonth'], 
					"endyear"=>$formvalues['e_deathyear'],
					"locationtext"=> $formvalues['placeofdeath'],
					"createdby" => 1
				);
			} else {
				$d_key = array_search_key_by_id($existing_events, $dodevent->getID());
				$events[$d_key] = array(
					"id"=> $dodevent->getID(), 
					"starttype"=>$formvalues['d_starttype'],
					"startday"=>$formvalues['deathday'], 
					"startmonth"=>$formvalues['deathmonth'], 
					"startyear"=>$formvalues['deathyear'], 
					"starttext"=>$formvalues['d_starttext'],
					"endtype"=>$formvalues['de_starttype'], 
					"endday"=>$formvalues['e_deathday'], 
					"endmonth"=>$formvalues['e_deathmonth'], 
					"endyear"=>$formvalues['e_deathyear'],
					"locationtext"=> $formvalues['placeofdeath']);				
			}
		}
		
		// check if new or updating previous
		if(count($existing_events) > 0){
			$theevents = multidimensional_array_merge($existing_events, $events);
		} else {
			$theevents = $events;
		}
		
		// debugMessage($theevents);
		if(count($theevents) > 0){
			$formvalues['events'] = $theevents;
		} else {
			unset($formvalues['events']);
		}
		
		// contacts preprocessing
		// email addresses$theevents
		$emails = array(); $phones = array(); $services = array(); $addresses = array(); $contacts = array(); $locations = array();
		if(!isArrayKeyAnEmptyString('emails', $formvalues)){
			foreach ($formvalues['emails'] as $key => $value){
				if(isEmptyString($value['emailaddress'])){
					unset($value[$key]);
				} else {
					if(!isArrayKeyAnEmptyString('id', $value)){
						$emails[$key]['id'] = $value['id'];
					}
					
					$emails[$key]['personid'] = $formvalues['id'];
					$emails[$key]['type'] = 1;
					if(isArrayKeyAnEmptyString('emails_type_'.$key, $formvalues)){
						$emails[$key]['subtype'] = 1;
					} else {
						$emails[$key]['subtype'] = $formvalues['emails_type_'.$key];
					}
					$emails[$key]['value1'] = $value['emailaddress'];
					// set primary
					$emails[$key]['isprimary'] = 0;
					if(strval($key) == strval($formvalues['primary_email'])){
						$emails[$key]['isprimary'] = 1;
					}
					// check if primary has changed
					$contacts[$key] = $emails[$key];
				}
			}
		}
		// phone numbers
		if(!isArrayKeyAnEmptyString('phones', $formvalues)){
			foreach ($formvalues['phones'] as $key => $value){
				// debugMessage($value);
				if(isEmptyString($value['value3'])){
					unset($value[$key]);
				} else {
					if(!isArrayKeyAnEmptyString('id', $value)){
						$phones[$key]['id'] = $value['id'];
					}
					
					$phones[$key]['personid'] = $formvalues['id'];
					$phones[$key]['type'] = 2;
					if(isArrayKeyAnEmptyString('phones_type_'.$key, $formvalues)){
						$phones[$key]['subtype'] = 1;
					} else {
						$phones[$key]['subtype'] = $formvalues['phones_type_'.$key];
					}
					$phones[$key]['value1'] = $value['value1'];
					$phones[$key]['value2'] = $value['value2'];
					$phones[$key]['value3'] = $value['value3'];
					// set primary
					$phones[$key]['isprimary'] = 0;
					if(strval($key) == strval($formvalues['primary_phone'])){
						$phones[$key]['isprimary'] = 1;
					}
					$contacts[$key] = $phones[$key];
					// debugMessage($contacts[$key]);
				}
			}
		}
		// debugMessage($contacts);
		// physical addresses
		$existing_adddresses = array();
		if(!isArrayKeyAnEmptyString('addresses', $formvalues)){
			// debugMessage($formvalues);
			$theaddresses = $this->getAllAddresses();
			$existing_adddresses = $theaddresses->toArray();
			// debugMessage($existing_adddresses);
			foreach ($formvalues['addresses'] as $key => $value){
				if(!isArrayKeyAnEmptyString('addresses_type_'.$key, $formvalues)){
					if(isEmptyString($formvalues['addresses_type_'.$key]) || isEmptyString($formvalues['addresses_country_'.$key]) /*|| isEmptyString($value['streetaddress'])*/){
						unset($value[$key]);
					} else {
						if(!isArrayKeyAnEmptyString('id', $value)){
							$addresses[$key]['id'] = $value['id'];
						}
						
						$addresses[$key]['personid'] = $formvalues['id'];
						$addresses[$key]['type'] = $formvalues['addresses_type_'.$key];
						$addresses[$key]['country'] = $formvalues['addresses_country_'.$key];
						if(!isArrayKeyAnEmptyString('city', $value)){
							$addresses[$key]['city'] = $value['city'];
						}
						if(!isArrayKeyAnEmptyString('state', $value)){
							$addresses[$key]['state'] = $value['state'];
						}
						if(!isArrayKeyAnEmptyString('zipcode', $value)){
							$addresses[$key]['zipcode'] = $value['zipcode'];
						}
						if(!isArrayKeyAnEmptyString('addresses_ssaza_'.$key, $formvalues)){
							$addresses[$key]['county'] = $formvalues['addresses_ssaza_'.$key];
							$addresses[$key]['state'] = "";
							$addresses[$key]['zipcode'] = "";
						} else {
							if(!isArrayKeyAnEmptyString('county', $value)){
								$addresses[$key]['county'] = $value['county'];
							}
						}
						if(!isArrayKeyAnEmptyString('town', $value)){
							$addresses[$key]['town'] = $value['town'];
						}
						if(!isArrayKeyAnEmptyString('townid', $value)){
							$addresses[$key]['town'] = $value['townid'];
						}
						if($addresses[$key]['country'] == 'XX'){
							if(!isArrayKeyAnEmptyString('addresses_town_'.$key, $formvalues)){
								$addresses[$key]['town'] =  $formvalues['addresses_town_'.$key];
							} else { 
								$addresses[$key]['town'] =  NULL;
							}
						}
						if(!isArrayKeyAnEmptyString('village', $value)){
							$addresses[$key]['village'] = $value['village'];
						}
						if(!isArrayKeyAnEmptyString('villageid', $value)){
							$addresses[$key]['village'] = $value['villageid'];
						}
						if($addresses[$key]['country'] == 'XX'){
							$addresses[$key]['village'] =  NULL;
							if(!isArrayKeyAnEmptyString('addresses_village_'.$key, $formvalues)){
								$addresses[$key]['village'] =  $formvalues['addresses_village_'.$key];
							}
							if(!isArrayKeyAnEmptyString('addresses_village_but_'.$key, $formvalues)){
								$addresses[$key]['village'] =  $formvalues['addresses_village_but_'.$key];
							}
						}
						$addresses[$key]['streetaddress'] = $value['streetaddress'];
						if($addresses[$key]['country'] == 'UG' || $addresses[$key]['country'] == 'XX'){
							$addresses[$key]['state'] = "";
							$addresses[$key]['zipcode'] = "";
						}
						// set primary
						$addresses[$key]['isprimary'] = 0;
						if(!isArrayKeyAnEmptyString('primary_address', $formvalues)){
							if(strval($key) == strval($formvalues['primary_address'])){
								$addresses[$key]['isprimary'] = 1;
							}
						}
						$locations[$key] = $addresses[$key];
						debugMessage($addresses[$key]);
					}
				}
			}
		}
		
		// web services
		if(!isArrayKeyAnEmptyString('services', $formvalues)){
			foreach ($formvalues['services'] as $key => $value){
				if(isEmptyString($value['value1']) && isEmptyString($value['value2'])){
					unset($value[$key]);
				} else {
					if(!isArrayKeyAnEmptyString('id', $value)){
						$services[$key]['id'] = $value['id'];
					}
					
					$services[$key]['personid'] = $formvalues['id'];
					$services[$key]['type'] = 3;
					$services[$key]['subtype'] = $formvalues['services_type_'.$key];
					$services[$key]['value1'] = $value['value1'];
					$services[$key]['value2'] = $value['value2'];
					
					$contacts[$key] = $services[$key];
				}
			}
		}
		
		if(count($contacts) > 0){
			$formvalues['contacts'] = $contacts;
		} else {
			unset($formvalues['contacts']);
		}
		
		// check for addresses
		if(count($existing_adddresses) > 0 && count($locations) > 0){
			$validaddress = multidimensional_array_merge($existing_adddresses, $locations);
		} else {
			if(count($locations) > 0){
				$validaddress = $locations;
			} else {
				$validaddress = array();
			}
		}
		// debugMessage($validaddress);
		if(count($validaddress) > 0){
			$formvalues['addresses'] = $validaddress;
		} else {
			unset($formvalues['addresses']);
		}
		
		if(!isArrayKeyAnEmptyString('invite_addresses', $formvalues)){
			$formvalues['addresses'] = $formvalues['invite_addresses'];
		}
		
		$x = 0; $interests_array = array();
		$allinterests = $this->getProfileInterests()->toArray();
		// ethinicities on profile
		if(!isArrayKeyAnEmptyString('ethinicities', $formvalues)) {
			// debugMessage($allinterests);
			foreach ($formvalues['ethinicities'] as $key => $ethinicity) {
				if(!isEmptyString($formvalues['id'])){
					// place back existing ethinicity ids
					$existing_ethinicities = $this->getEthinicitiesForProfile($formvalues['id'], $ethinicity);
					// debugMessage($existing_ethinicities);
					if(!isEmptyString($existing_ethinicities['id'])){
						$k = array_search_key($allinterests, $existing_ethinicities);
						// debugMessage($k);
						$profileethinicities[$k]['id'] = $existing_ethinicities['id'];
						$profileethinicities[$k]['personid'] = $formvalues['id'];
						$profileethinicities[$k]['value2'] = $ethinicity;
						$profileethinicities[$k]['type'] = 2;
						$interests_array[$k] = $profileethinicities[$k];
					} else {
						$profileethinicities[md5($x)]['personid'] = $formvalues['id'];
						$profileethinicities[md5($x)]['value2'] = $ethinicity;
						$profileethinicities[md5($x)]['type'] = 2;
						$interests_array[md5($x)] = $profileethinicities[md5($x)];							
					}
				}
				$x++;
			}
		}
		
		// languages on profile
		if(!isArrayKeyAnEmptyString('languages', $formvalues)) {
			// debugMessage($allinterests);
			foreach ($formvalues['languages'] as $key => $language) {
				if(!isEmptyString($formvalues['id'])){
					// place back existing language ids
					$existing_languages = $this->getLanguagesForProfile($formvalues['id'], $language);
					// debugMessage($existing_languages);
					if(!isEmptyString($existing_languages['id'])){
						$k = array_search_key($allinterests, $existing_languages);
						// debugMessage($k);
						$profilelanguages[$k]['id'] = $existing_languages['id'];
						$profilelanguages[$k]['personid'] = $formvalues['id'];
						$profilelanguages[$k]['value2'] = $language;
						$profilelanguages[$k]['type'] = 1;
						$interests_array[$k] = $profilelanguages[$k];
					} else {
						$profilelanguages[md5($x)]['personid'] = $formvalues['id'];
						$profilelanguages[md5($x)]['value2'] = $language;
						$profilelanguages[md5($x)]['type'] = 1;	
						$interests_array[md5($x)] = $profilelanguages[md5($x)];					
					}
				}
				$x++;
			}
		}
		if(count($interests_array) > 0){
			$formvalues['interests'] = $interests_array;
		} else {
			unset($formvalues['interests']);
		}
		
		if(isArrayKeyAnEmptyString('dateofbirth', $formvalues)) {
			unset($formvalues['dateofbirth']); 
		}
		
		# set current father and mother families
		if(!isArrayKeyAnEmptyString('ffamilyid', $formvalues)){
			$this->setFFamilyID($formvalues['ffamilyid']);
		}
		if(!isArrayKeyAnEmptyString('mfamilyid', $formvalues)){
			$this->setMFamilyID($formvalues['mfamilyid']);
		}
		if(!isArrayKeyAnEmptyString('directrelationship', $formvalues)){
			$this->setDirectRelationShip($formvalues['directrelationship']);
		}
       	// debugMessage($formvalues); exit();
		parent::processPost($formvalues);
	}
	
	# columns which are not mapped to the database
	# The id of the person to whom a relation is to be created
	protected $focusid;
	# The type of relationship which will determine what type of family tree entry will be made
	protected $relationshiptype;
	# The id of a child's family 
	protected $childfamilyid;
	# The partner relationship status on a family
	protected $partnerstatus;
	# The focus familyid
	protected $focusfamilyid;
	# The focus fatherid
	protected $focusfatherid;
	# The focus motherid
	protected $focusmotherid;
	# The focus tree line
	protected $focustreeid;
	# The sibling type
	protected $siblingtype;
	# The parent firstname
	protected $pfirstname;
	# The parent lastname
	protected $plastname;
	# The parent clanname
	protected $pclanname;
	# The parent clan
	protected $pclanid;
	# The parent type
	protected $ptype;
	# The sibling father type
	protected $pfathertype;
	# The sibling mother type
	protected $pmothertype;
	# The sibling father family if already exists
	protected $fsiblingfamilyid;
	# The sibling mmother family if already exists
	protected $msiblingfamilyid;
	# The sibling father type
	protected $cfathertype;
	# The sibling mother type
	protected $cmothertype;
	# The father family
	protected $ffamilyid;
	# The mother family
	protected $mfamilyid;
	# The direct relationship to the passion
	protected $directrelationship;
	/**
	 * Return the focusid or empty 
	 * @return integer|Empty string
	 */
	function getFocusID(){
		return $this->focusid; 
	}
	/**
	 * Set the focusid
	 * @param integer $focusid The ID of the person
	 */
	function setFocusID($focusid) {
		$this->focusid = $focusid;
	}
	/**
	 * Return the relationshiptype or empty 
	 * @return integer|Empty string
	 */
	function getRelationshipType(){
		return $this->relationshiptype; 
	}
	/**
	 * Set the childfamilyid
	 * @param integer $childfamilyid The relation with person
	 */
	function setRelationshipType($relationshiptype) {
		$this->relationshiptype = $relationshiptype;
	}
	/**
	 * Return the childfamilyid or empty 
	 * @return integer|Empty string
	 */
	function getChildFamilyID(){
		return $this->childfamilyid; 
	}
	/**
	 * Set the childfamilyid
	 * @param integer $childfamilyid The ID of the person
	 */
	function setChildFamilyID($childfamilyid) {
		$this->childfamilyid = $childfamilyid;
	}
	/**
	 * Return the partnerstatus or empty 
	 * @return integer|Empty string
	 */
	function getPartnerStatus(){
		return $this->partnerstatus; 
	}
	/**
	 * Set the partnerstatus
	 * @param integer $partnerstatus The ID of the person
	 */
	function setPartnerStatus($partnerstatus) {
		$this->partnerstatus = $partnerstatus;
	}
	/**
	 * Return the focusfamilyid or empty 
	 * @return integer|Empty string
	 */
	function getFocusFamilyID(){
		return $this->focusfamilyid; 
	}
	/**
	 * Set the focusfamilyid
	 * @param integer $focusfamilyid The ID of the person
	 */
	function setFocusFamilyID($focusfamilyid) {
		$this->focusfamilyid = $focusfamilyid;
	}
	/**
	 * Return the focusfatherid or empty 
	 * @return integer|Empty string
	 */
	function getFocusFatherID(){
		return $this->focusfatherid; 
	}
	/**
	 * Set the focusfamilyid
	 * @param integer $focusfamilyid The ID of the person
	 */
	function setFocusFatherID($focusfatherid) {
		$this->focusfatherid = $focusfatherid;
	}
	/**
	 * Return the focusmotherid or empty 
	 * @return integer|Empty string
	 */
	function getFocusMotherID(){
		return $this->focusmotherid; 
	}
	/**
	 * Set the focusmotherid
	 * @param integer $focusmotherid The ID of the person
	 */
	function setFocusMotherID($focusmotherid) {
		$this->focusmotherid = $focusmotherid;
	}
	/**
	 * Return the focustreeid or empty 
	 * @return integer|Empty string
	 */
	function getFocusTreeID(){
		return $this->focustreeid; 
	}
	/**
	 * Set the focustreeid
	 * @param integer $focustreeid The ID of the person
	 */
	function setFocusTreeID($focustreeid) {
		$this->focustreeid = $focustreeid;
	}
	
	/**
	 * Return the siblingtype or empty 
	 * @return integer|Empty string
	 */
	function getSiblingType(){
		return $this->siblingtype; 
	}
	function setSiblingType($siblingtype) {
		$this->siblingtype = $siblingtype;
	}
	/**
	 * Return the parent firstname or empty 
	 * @return string
	 */
	function getPFirstName(){
		return $this->pfirstname; 
	}
	function setPFirstName($pfirstname) {
		$this->pfirstname = $pfirstname;
	}
	/**
	 * Return the parent lastname or empty 
	 * @return string
	 */
	function getPLastName(){
		return $this->plastname; 
	}
	function setPLastName($plastname) {
		$this->plastname = $plastname;
	}
	/**
	 * Return the parent clanname or empty 
	 * @return string
	 */
	function getPClanName(){
		return $this->pclanname; 
	}
	function setPClanName($pclanname) {
		$this->pclanname = $pclanname;
	}
	/**
	 * Return the parent clanid or empty 
	 * @return integer|Empty string
	 */
	function getPClanID(){
		return $this->pclanid; 
	}
	function setPClanID($pclanid) {
		$this->pclanid = $pclanid;
	}
	/**
	 * Return the parent type or empty 
	 * @return integer|Empty string
	 */
	function getPType(){
		return $this->ptype; 
	}
	function setPType($ptype) {
		$this->ptype = $ptype;
	}
	/**
	 * Return the father type or empty 
	 * @return integer|Empty string
	 */
	function getPFatherType(){
		return $this->pfathertype; 
	}
	function setPFatherType($pfathertype) {
		$this->pfathertype = $pfathertype;
	}
	/**
	 * Return the mother type or empty 
	 * @return integer|Empty string
	 */
	function getPMotherType(){
		return $this->pmothertype; 
	}
	function setPMotherType($pmothertype) {
		$this->pmothertype = $pmothertype;
	}
	/**
	 * Return the father type or empty 
	 * @return integer|Empty string
	 */
	function getCFatherType(){
		return $this->cfathertype; 
	}
	function setCFatherType($cfathertype) {
		$this->cfathertype = $cfathertype;
	}
	/**
	 * Return the mother type or empty 
	 * @return integer|Empty string
	 */
	function getCMotherType(){
		return $this->cmothertype; 
	}
	function setCMotherType($cmothertype) {
		$this->cmothertype = $cmothertype;
	}
	/**
	 * Return the parent clanid or empty 
	 * @return integer|Empty string
	 */
	function getFSiblingFamilyID(){
		return $this->siblingfamilyid; 
	}
	function setFSiblingFamilyID($fsiblingfamilyid) {
		$this->fsiblingfamilyid = $fsiblingfamilyid;
	}
	/**
	 * Return the parent clanid or empty 
	 * @return integer|Empty string
	 */
	function getMSiblingFamilyID(){
		return $this->msiblingfamilyid; 
	}
	function setMSiblingFamilyID($msiblingfamilyid) {
		$this->msiblingfamilyid = $msiblingfamilyid;
	}
	function getFFamilyID(){
		return $this->ffamilyid; 
	}
	function setFFamilyID($ffamilyid) {
		$this->ffamilyid = $ffamilyid;
	}
	function getMFamilyID(){
		return $this->mfamilyid; 
	}
	function setMFamilyID($mfamilyid) {
		$this->mfamilyid = $mfamilyid;
	}
	function getDirectRelationShip(){
		return $this->directrelationship; 
	}
	function setDirectRelationShip($directrelationship) {
		$this->directrelationship = $directrelationship;
	}
	/**
	 * Return the person's full names, which is a concatenation of the first and the surname
	 *
	 * @return String
	 */
	function getName() {
		$clanname = '';
		if($this->isMuganda() && !isEmptyString($this->getClanID()) && $this->getClanName() != $this->getLastName()){
			$clanname = "(".$this->getClanName().")";
		}
	    return isEmptyString($this->getScreenName()) ? $this->getFirstName()." ".$this->getLastName()." ".$clanname : $this->getScreenName();
	}
	/**
	 * Return the person's full names, which is a concatenation of the first and the surname
	 *
	 * @return String
	 */
	function getListName() {
		$clanname = '';
		if($this->isMuganda() && !isEmptyString($this->getClanID())){
			$clanname = "(".$this->getClanName().")";
		}
	    return isEmptyString($this->getScreenName()) ? $this->getFirstName()." ".$this->getLastName()." ".$clanname : $this->getScreenName();
	}
	/**
     * Determine the gender strinig of a person
     * @return String the gender
     */
    function getGenderLabel(){
    	return $this->getGender() == '1' ? 'Male' : 'Female'; 
    }
 	/**
     * Determine if a person is male
     * @return bool
     */
    function isMale(){
    	return $this->getGender() == '1' ? true : false; 
    }
	/**
     * Determine if a person is female
     * @return bool
     */
    function isFemale(){
    	return $this->getGender() == '2' ? true : false; 
    }
	/**
     * Determine the person's life status label
     * @return String the life status 
     */
    function getLifeStatusLabel(){
    	return LookupType::getLookupValueDescription("LIFE_STATUS", $this->getLifeStatus()); 
    }
	/**
     * Determine the person's life status label
     * @return String the life status 
     */
    function getSuffixLabel(){
    	return LookupType::getLookupValueDescription("SALUTATION", $this->getSuffix()); 
    }
	/**
     * Determine if a person is alive
     * @return bool
     */
    function isAlive(){
    	return $this->getLifeStatus() == '1' ? true : false; 
    }
	/**
     * Determine if a person is dead
     * @return bool
     */
    function isDead(){
    	return $this->getLifeStatus() == '2' ? true : false; 
    }
	/**
     * Determine if a person is unknown
     * @return bool
     */
    function isUnknown(){
    	return $this->getLifeStatus() == '3' ? true : false; 
    }
	/**
     * Determine the type of person
     * @return bool
     */
    function getTypeLabel(){
    	$label = '';
    	switch ($this->getType()){
    		case '1':
    			$label = "Muganda";
    			break;
    		case '2':
    			$label = "Not Muganda";
    			break;
    		case '3':
    			$label = "Other";
    			break;	
    	}
    	return $label; 
    }
	/**
     * Determine if a person is muganda
     * @return bool
     */
    function isMuganda(){
    	return $this->getType() == '1' ? true : false; 
    }
	/**
     * Determine if a person is mujjwa
     * @return bool
     */
    function isMujjwa(){
    	return $this->getType() == '2' ? true : false; 
    }
	/**
     * Determine if a person is other
     * @return bool
     */
    function isOtheType(){
    	return $this->getType() == '3' ? true : false; 
    }
 	/**
     * Whether or not the person has a father
     * 
     * @return bool 
     */
    public function hasFather() {
        // does not belong to a family so has no parents
        if(isEmptyString($this->getFamilyID())) {
            return false; 
        }
        if(isEmptyString($this->getFamily()->getFatherID())) {
            // no father defined 
            return false; 
        }
        return true; 
    }
	/**
     * return the fatherid of person
     * 
     * @return interger fatherid 
     */
    public function getFatherID() {
        // does not belong to a family so has no parents
        if(isEmptyString($this->getFamilyID())) {
            return ""; 
        }
        if(isEmptyString($this->getFamily()->getFatherID())) {
            // no father defined 
            return ""; 
        }
        return $this->getFamily()->getFatherID(); 
    }
	/**
     * return the fatherid of person
     * 
     * @return interger fatherid 
     */
    public function getMotherID() {
        // does not belong to a family so has no parents
        if(isEmptyString($this->getFamilyID())) {
            return ""; 
        }
        if(isEmptyString($this->getFamily()->getMotherID())) {
            // no father defined 
            return ""; 
        }
        return $this->getFamily()->getMotherID(); 
    }
	/**
     * return the father of person
     * 
     * @return person object
     */
    public function getFather() {
        return $this->getFamily()->getFather(); 
    }
	/**
     * return the father of person
     * 
     * @return person object
     */
    public function getMother() {
        return $this->getFamily()->getMother(); 
    }
 	/**
     * Whether or not the person has a mother
     *
     * @return bool
     */
    public function hasMother() {
        // does not belong to a family so has no parents
        if(isEmptyString($this->getFamilyID())) {
            return false; 
        }
        if(isEmptyString($this->getFamily()->getMotherID())) {
            // no mother defined 
            return false; 
        }
        return true; 
    }
	/**
     * Whether or not the person has families to which children can be added
     * 
     * @return bool 
     */
    public function hasFamily() {
    	$conn = Doctrine_Manager::connection();
		$sql = "SELECT COUNT(id) FROM family WHERE fatherid = ".$this->getID()." OR motherid = ".$this->getID(); 
		$result = $conn->fetchOne($sql);
		return intval($result) > 0; 
    }
 	/**
     * Whether or not the person belongs to a family as a child
     * 
     * @return bool
     */
    public function belongsToFamily() {
    	if(isEmptyString($this->getFamilyID())) {
            return false; 
        }
        return true; 
    }
 	/**
     * Determine the family to which the person's partner belongs to
     * @return Family or FALSE if the partner has no family
     */
	public function getPartnerFamily($fatherid, $partnerid) {
		$q = Doctrine_Query::create()->from('Family f')->where("f.father = ".$fatherid." AND f.mother = ".$partnerid);
		$result = $q->execute();
		return $result;
    }
	/**
     * Determine the spouses/partners for a person
     * @return collection the partners for person
     */
    function getPartners() {
    	// families
    	if($this->isMale()){
    		$families = $this->getFFamilies();
    	} else {
    		$families = $this->getMFamilies();
    	}
    	
    	// debugMessage($families->toArray());
    	if($families->count() > 0){
    		$pids_array = array();
    		foreach ($families as $family) {
    			// add only family ids where both partners are specified
    			if($this->isMale() && !isEmptyString($family->getMotherID())){
    				$pids_array[] = $family->getMotherID();
    			}
    			if($this->isFemale() && !isEmptyString($family->getFatherID())){
    				$pids_array[] = $family->getFatherID();
    			}
    		}
    		
    		if(count($pids_array) > 0) {
    			$spouselist = implode(",", $pids_array);
	    		// debugMessage($familylist);
	    		$q = Doctrine_Query::create()->from('Person p')->where("p.id IN(".$spouselist.") ");
				$result = $q->execute();
				// debugMessage($result->toArray());
				return $result;
    		}
    	}
    	return false;
    }
    # determine if person has partners
    function hasSpouse() {
    	if(!$this->getPartners()){
    		return false;
    	}
    	return true;
    }
	# determine if person has children
    function hasChildren() {
    	if(!$this->getAllChildren()){
    		return false;
    	}
    	return true;
    }
	/**
     * Determine the brother siblings for person
     * @return collection the siblings for person
     */
    function getBrothers() {
    	$q = Doctrine_Query::create()->from('Person p')->where("p.familyid = ".$this->getFamilyID()." AND p.gender = '1' AND p.id <> ".$this->getID());
		$result = $q->execute();
		return $result;
    }
	/**
     * Determine the sister sibilings for person
     * @return collection the siblings for person
     */
    function getSisters() {
    	$q = Doctrine_Query::create()->from('Person p')->where("p.familyid = ".$this->getFamilyID()." AND p.gender = '2' AND p.id <> ".$this->getID());
		$result = $q->execute();
		return $result;
    }
	/**
     * Determine the all sibilings for person
     * @return collection the siblings for person
     */
    function getSiblings() {
    	$q = Doctrine_Query::create()->from('Person p')->where("p.familyid = ".$this->getFamilyID()." AND p.id <> ".$this->getID());
		$result = $q->execute();
		return $result;
    }
	/**
     * Determine the all sibilings for person
     * @return collection the siblings for person
     */
    function getFamilySiblings($familyid) {
    	$q = Doctrine_Query::create()->from('Person p')->where("p.familyid = ".$familyid." AND p.id <> ".$this->getID());
		$result = $q->execute();
		return $result;
    }
	/**
     * Determine the sons for person
     * @return collection the sons for person
     */
    function getSons() {
    	// $result = '';
    	// families
    	if($this->isMale()){
    		$families = $this->getFFamilies();
    	} else {
    		$families = $this->getMFamilies();
    	}
    	
    	// debugMessage($families->toArray());
    	if($families->count() > 0){
    		$fids_array = array();
    		foreach ($families as $family) {
    			$fids_array[] = $family->getID();
    		}
    		$familylist = implode(",", $fids_array);
    		// debugMessage($familylist);
    		$q = Doctrine_Query::create()->from('Person p')->leftJoin('p.family f')->where("p.familyid IN(".$familylist.") AND p.gender = '1' AND p.id <> ".$this->getID());
			$kids = $q->execute();
			// $result = $kids;
			return $kids;
    	}
    	return false;
    }
	/**
     * Determine the daughters for person
     * @return collection the sons for person
     */
    function getDaughters() {
    	$result = '';
    	if($this->isFemale()){
    		$families = $this->getMFamilies();
    	} else {
    		$families = $this->getFFamilies();
    	}
    	// debugMessage($families->toArray());
    	if($families->count() > 0){
    		$fids_array = array();
    		foreach ($families as $family) {
    			$fids_array[] = $family->getID();
    		}
    		$familylist = implode(",", $fids_array);
    		// debugMessage($familylist);
    		$q = Doctrine_Query::create()->from('Person p')->leftJoin('p.family f')->where("p.familyid IN(".$familylist.") AND p.gender = '2' AND p.id <> ".$this->getID());
			$kids = $q->execute();
			// $result = $kids;
			return $kids;
    	}
    	return false;
    }
    # Determine all the children for person
    function getAllChildren() {
    	// $result = '';
    	// families
    	if($this->isMale()){
    		$families = $this->getFFamilies();
    	} else {
    		$families = $this->getMFamilies();
    	}
    	
    	// debugMessage($families->toArray());
    	if($families->count() > 0){
    		$fids_array = array();
    		foreach ($families as $family) {
    			$fids_array[] = $family->getID();
    		}
    		$familylist = implode(",", $fids_array);
    		// debugMessage($familylist);
    		$q = Doctrine_Query::create()->from('Person p')->leftJoin('p.family f')->where("p.familyid IN(".$familylist.") AND p.id <> ".$this->getID());
			$kids = $q->execute();
			// debugMessage($result->toArray());
			// $result = $kids;
			return $kids;
    	}
    	return false;
    }
	# Determine all the children for person
    function getAllGrandChildren($ignore_list = array()) {
    	// $result = '';
    	// families
    	if($this->isMale()){
    		$families = $this->getFFamilies();
    		$ismale = true;
    	} else {
    		$families = $this->getMFamilies();
    		$ismale = false;
    	}
    	
    	// debugMessage($families->toArray());
    	if($families->count() > 0){
    		$fids_array = array();
    		foreach ($families as $family) {
    			$fids_array[] = $family->getID();
    		}
    		$familylist = implode(",", $fids_array);
    		// debugMessage($familylist);
    		$q = Doctrine_Query::create()->from('Person p')->leftJoin('p.family f')->where("p.familyid IN(".$familylist.") AND p.id <> ".$this->getID());
			$kids = $q->execute();
			// debugMessage($result->toArray());
			// $result = $kids;
			$gkids_collection = new Doctrine_Collection(Doctrine_Core::getTable("Person"), 'id');
			
			if($kids){
				foreach ($kids as $kid){
					$gkids_collection->add($kid);
					if($kid->isMale()){
						if($kid->hasMother()){
							//debugMessage('mother is '.$kid->getMotherID());
							if(!$gkids_collection->contains($kid->getMotherID()) && $kid->getMotherID() != $this->getID() && !in_array($kid->getMotherID(), $ignore_list)){
								//debugMessage('added mother');
								$gkids_collection->add($kid->getMother());
							}
						}
					} else {
						if($kid->hasFather()){
							//debugMessage('father is '.$kid->getFatherID());
							if(!$gkids_collection->contains($kid->getFatherID()) && $kid->getFatherID() != $this->getID() && !in_array($kid->getFatherID(), $ignore_list)){
								//debugMessage('added father');
								$gkids_collection->add($kid->getFather());
							}
						}
					}
					
					$gkids = $kid->getAllChildren();
					if($gkids){
						foreach ($gkids as $akid) {
							$gkids_collection->add($akid);
							if($akid->isMale()){
								if($akid->hasMother()){
									//debugMessage('mother is '.$kid->getMotherID());
									if(!$gkids_collection->contains($akid->getMotherID()) && $akid->getMotherID() != $this->getID() && !in_array($akid->getMotherID(), $ignore_list)){
										//debugMessage('added mother');
										$gkids_collection->add($akid->getMother());
									}
								}
							} else {
								if($akid->hasFather()){
									//debugMessage('father is '.$kid->getFatherID());
									if(!$gkids_collection->contains($akid->getFatherID()) && $akid->getFatherID() != $this->getID() && !in_array($akid->getFatherID(), $ignore_list)){
										//debugMessage('added father');
										$gkids_collection->add($akid->getFather());
									}
								}
							}
							
							$ggkids = $akid->getAllChildren();
							if($ggkids){
								foreach ($ggkids as $fkid) {
									$gkids_collection->add($fkid);
									if($fkid->isMale()){
										if($fkid->hasMother()){
											//debugMessage('mother is '.$kid->getMotherID());
											if(!$gkids_collection->contains($fkid->getMotherID()) && $fkid->getMotherID() != $this->getID() && !in_array($fkid->getMotherID(), $ignore_list)){
												//debugMessage('added mother');
												$gkids_collection->add($fkid->getMother());
											}
										}
									} else {
										if($fkid->hasFather()){
											//debugMessage('father is '.$kid->getFatherID());
											if(!$gkids_collection->contains($fkid->getFatherID()) && $fkid->getFatherID() != $this->getID() && !in_array($fkid->getFatherID(), $ignore_list)){
												//debugMessage('added father');
												$gkids_collection->add($fkid->getFather());
											}
										}
									}
								}
							}
						}
					}
				}
			}
			if($gkids_collection->count() == 0){
				return false;
			} else {
				return $gkids_collection;
			}
    	}
    	return false;
    }
	/**
     * Determine all immediate family members of a person
     * @return Person or FALSE if there are no immediate family members 
     */
    function getImmediateFamilyMembers() {
    	$session = SessionWrapper::getInstance(); 
    	$thepersonid = $this->getID();
    	$thefamilyid = $this->getFamilyID();
    	$userid = $session->getVar('userid');
    	$sessionperson = $session->getVar('personid');
    	
    	// select other partner families for person's parents that may exist other than that of user
    	$persons = array();
    	$fatherid = $this->getFatherID();
		$hasfather = false;
		$i = 1;
		# check for father
    	if(!isEmptyString($fatherid)){
    		$persons[$fatherid]['id'] = $fatherid;
			$persons[$fatherid]['Relationship'] = "Father";
			$hasfather = true;
			$i = $i+1;
    	}
		
    	$motherid = $this->getMotherID();
		$hasmother = false;
		# check for mother
    	if(!isEmptyString($motherid)){
    		$persons[$motherid]['id'] = $motherid;
			$persons[$motherid]['Relationship'] = "Mother";
			$hasmother = true;
			$i = $i+1;
    	}
		# check for siblings on father's side
		if($hasfather){
			$fperson = new Person();
			$fperson->populate($fatherid);
			$fkids = $fperson->getAllChildren();
			
			if($fkids){
				if($fkids->count() > 0){
					foreach($fkids as $kid){
						if($kid->getID() != $this->getID()){
							$persons[$kid->getID()]['id'] = $kid->getID();
							$persons[$kid->getID()]['Relationship'] = $kid->isMale() ? "Brother" : "Sister";
							$i = $i+1;
						}
					}
				}
			}
		}
		# check for siblings on mother's side
		if($hasmother){
			$mperson = new Person();
			$mperson->populate($motherid);
			$mkids = $mperson->getAllChildren();
			// debugMessage($fkids->toArray());
			if($mkids){
				if($mkids->count() > 0){
					foreach($mkids as $kid){
						if($kid->getID() != $this->getID() && !in_array($kid->getID(), $persons)){
							$persons[$kid->getID()]['id'] = $kid->getID();
							$persons[$kid->getID()]['Relationship'] = $kid->isMale() ? "Brother" : "Sister";
							$i = $i+1;
						}
					}
				}
			}
		}
		# check for siblings when both parents dont exist
		if(!$hasfather && !$hasmother && !isEmptyString($this->getFamilyID())){
			$sibling = $this->getFamilySiblings($this->getFamilyID()); 
			// debugMessage($sibling->toArray());
			if($sibling->count() > 0){
				foreach($sibling as $kid){
					if($kid->getID() != $this->getID()){
						$persons[$kid->getID()]['id'] = $kid->getID();
						$persons[$kid->getID()]['Relationship'] = $kid->isMale() ? "Brother" : "Sister";
						$i = $i+1;
					}
				}
			}
		}
		# check for any kids
    	$kids = $this->getAllChildren();
    	if($kids){
			if($kids->count() > 0){
				foreach ($kids as $child) {
					$persons[$child->getID()]['id'] = $child->getID();
					$persons[$child->getID()]['Relationship'] = $child->isMale() ? "Son" : "Daughter";
					$i = $i+1;
				}
			}
    	}
		# check for partners
		$partners = $this->getPartners();
		if($partners){
			if($partners->count() > 0){
				foreach ($partners as $partner){
					if($partner->getID() != $this->getID()){
						$persons[$partner->getID()]['id'] = $partner->getID();
						$persons[$partner->getID()]['Relationship'] = "Spouse";
						$i = $i+1;
					}
				}
			}
		}
    	return $persons;
    }
    # randomise immediate family members
    function getRandomImmediateFamilyMembers($limit) {
    	$relatives  = $this->getImmediateFamilyMembers();
    	$relativeids_array = array();
    	foreach ($relatives as $relative){
    		$relativeids_array[] = $relative['id'];
    	}
    	$person_list = implode("','", $relativeids_array);
    	$q = Doctrine_Query::create()->from('Person p')->where("p.id IN('".$person_list."') AND p.photo <> '' ")->limit($limit)->orderBy('rand()');
		$result = $q->execute();
		// debugMessage($result->toArray());
		$count_result = $result->count();
		if($count_result == $limit){
			return $result;
		} 
		$q = Doctrine_Query::create()->from('Person p')->where("p.id IN('".$person_list."') ")->limit($limit)->orderBy('rand()');
		$result = $q->execute();
    	return $result;
    }
    function getAllRelatives($personid) {
    	/*$q = Doctrine_Query::create()->from('FamilyTree t')->where("t.treeid = ".$personid." AND t.relativeid <> ".$personid." ");
		$result = $q->execute();
		return $result;*/
    	$conn = Doctrine_Manager::connection();
		$sql = "SELECT t.relativeid as relative FROM familytree as t WHERE t.focusid = ".$personid." AND t.relativeid <> ".$this->getID(); 
		$result = $conn->fetchAll($sql);
		return $result; 
    }
	/**
     * Determine relationship between two imediate family members
     * @return @string the relationship
     */
    function getImmediateFamilyRelationship($relativeid) {
    	$therelation = "Unknown";
    	if($this->isFather($relativeid)){
    		return RELATIONSHIP_FATHER_LABEL;
    	}
   		if($this->isMother($relativeid)){
    		return RELATIONSHIP_MOTHER_LABEL;
    	}
    	if($this->isBrother($relativeid)){
    		return RELATIONSHIP_BROTHER_LABEL;
    	}
    	if($this->isSister($relativeid)){
    		return RELATIONSHIP_SISTER_LABEL;
    	}
    	if($this->isSon($relativeid)){
    		return RELATIONSHIP_SON_LABEL;
    	}
    	if($this->isDaughter($relativeid)){
    		return RELATIONSHIP_DAUGHTER_LABEL;
    	}
    	if($this->isSpouse($relativeid)){
    		return RELATIONSHIP_PARTNER_LABEL;
    	}     
		return $therelation;
    }
	/**
     * Determine if relative is the father
     * @return true if is a father false otherwise
     */
    function isFather($relativeid) {
    	if($this->getFatherID() == $relativeid){
    		return true;
    	}
    	return false;
    }
	/**
     * Determine if relative is the mother
     * @return true if is a mother false otherwise
     */
    function isMother($relativeid) {
    	if($this->getMotherID() == $relativeid){
    		return true;
    	}
    	return false;
    }
	/**
     * Determine if relative is a brother
     * @return true if is a brother 
     */
    function isBrother($relativeid) {
    	$brothers = $this->getBrothers();
    	if($brothers){
	    	if($brothers->count() > 0){
		    	foreach ($brothers as $brother){
		    		if($brother->getID() == $relativeid){
		    			return true;
		    		}
		    	}
	    	}
    	}
		return false;
    }
	/**
     * Determine if relative is a sister
     * @return true if is a sister 
     */
    function isSister($relativeid) {
    	$sisters = $this->getSisters();
    	if($sisters){
	    	if($sisters->count() > 0){
		    	foreach ($sisters as $sister){
					if($sister->getID() == $relativeid){
						return true;
					}
				}
	    	}
    	}
    }
	/**
     * Determine if relative is a son
     * @return true if is a son 
     */
    function isSon($relativeid) {
    	$sons = $this->getSons();
    	if($sons){
	    	if($sons->count() > 0){
		    	foreach ($sons as $son){
		    		if($son->getID() == $relativeid){
		    			return true;
		    		}
		    	}
	    	}
    	}
		return false;
    }
	/**
     * Determine if relative is a daughter
     * @return true if is a daughter 
     */
    function isDaughter($relativeid) {
    	$daughters = $this->getDaughters();
    	if($daughters){
	    	if($daughters->count() > 0){
		    	foreach ($daughters as $daughter){
		    		if($daughter->getID() == $relativeid){
		    			return true;
		    		}
		    	}
	    	}
    	}
		return false;
    }
	/**
     * Determine if relative is a spouse
     * @return true if is a spouse 
     */
    function isSpouse($relativeid) {
    	$partners = $this->getPartners();
    	if($partners){
	    	if($partners->count() > 0){
		    	foreach ($partners as $partner){
		    		if($partner->getID() == $relativeid){
		    			return true;
		    		}
		    	}
	    	}
    	}
		return false;
    }
    /**
     * Overide  to save persons relationships
     *	@return true if saved, false otherwise
     */
    function afterSave(){
    	// debugMessage('after save start');
    	$session = SessionWrapper::getInstance();
    	$personid = $session->getVar('personid');
    	$userid = $session->getVar('userid');
    	$conn = Doctrine_Manager::connection();
    	$subscriber = new Person();
    	$subscriber->populate($personid);
    	// debugMessage($this->toArray());
	    	// initialize the family tree records
	        // depending create a new family tree entry depending on the relationship type
	        switch ($this->getRelationshipType()) {
	        	case RELATIONSHIP_FATHER:
	        	case RELATIONSHIP_MOTHER:
	                // load the child
	                $family = new Family();
	                $child = new Person();
	                $child->populate($this->getFocusID());
	                // clean up invalid families
	                $child->getInvalidFamiliesForPerson()->delete();
	                
	        		// check if child already belongs to a family
	                if (!isEmptyString($this->getFocusFamilyID())) {
	                	// family alrdy exists with a mother only
	                    $family->populate($child->getFamilyID());
	                    // set the father
	                	if($this->getRelationshipType() == RELATIONSHIP_FATHER){
	                    	$child->getFamily()->setFatherID($this->getID());
	                    }
	                	if($this->getRelationshipType() == RELATIONSHIP_MOTHER) {
	                    	$child->getFamily()->setMotherID($this->getID());
	                    }
	                    $child->getFamily()->setAddedByID($personid);
	                    $child->getFamily()->save();
	                } else {
	                	// family does not exist. set new family
	                	if($this->getRelationshipType() == RELATIONSHIP_FATHER){
	                    	 $child->getFamily()->setFatherID($this->getID());
	                    }
	               		if($this->getRelationshipType() == RELATIONSHIP_MOTHER) {
	                    	$child->getFamily()->setMotherID($this->getID());
	                    }
	                    $child->getFamily()->setAddedByID($personid);
	                    $child->getFamily()->setCreatedBy($userid);
	                    $child->getFamily()->save();
	                    
	                    $child->getFamilyID();
	               		// update familyid the current person
	                	$kid = new Person();
	                	$kid->populate($child->getID());
	                	$kid->setFamilyID($child->getFamily()->getID());
	                	$kid->save();
						// Doctrine_Query::create()->update('Person p')->set('p.familyid','?', $child->getFamily()->getID())->where('p.id=?', $child->getID())->execute();
	                }

	       			// find any duplicated users and delete them
	    			$duplicates = $this->getDuplicatePersons();
			    	if($duplicates->count() > 0){
			    		$duplicates->delete();
			    	}
					    	
           			// update family tree
				    $this->updateFamilyTree($this->getRelationshipType(), $child->getID());
						
	                break;
	             // add siblings 
				case RELATIONSHIP_BROTHER:
				case RELATIONSHIP_SISTER: 
				    $sibling = new Person();
				    $sibling->populate($this->getFocusID());
				    // clean up invalid families
	                $sibling->getInvalidFamiliesForPerson()->delete();
	                
				    // check whether siblings share same parents, different father or mother
				    switch ($this->getSiblingType()){
				    	case '1':
							if(!isEmptyString($this->getFocusFamilyID())){
								$this->setFamilyID($this->getFocusFamilyID());
								$this->save();
							} else {
								$family = new Family();
		               	 		$family->setAddedByID($personid);
		               	 		$family->setCreatedBy($userid);
		    					$family->save();
		    					
			                	$update = new Person();
			                	$update->populate($this->getID());
			                	$update->setFamilyID($family->getID());
			                	$update->save();
			                	
			                	$sibling->setFamilyID($family->getID());
			                	$sibling->save();
							}
					    		
			    			// find any duplicated users and delete them
			    			$duplicates = $this->getDuplicatePersons();
					    	if($duplicates->count() > 0){
					    		$duplicates->delete();
					    	}
    	
				    		// update family tree
				    		$this->updateFamilyTree($this->getRelationshipType(), $sibling->getID());
					
				    		break;
				    	case '2':
				    		switch ($this->getPFatherType()){
				    			case '1': // father already exists. use that of sibling
										
						    		$this->setFamilyID($this->getFSiblingFamilyID());
						    		$this->save();
						    		
						    		// find any duplicated users and delete them
					    			$duplicates = $this->getDuplicatePersons();
							    	if($duplicates->count() > 0){
							    		$duplicates->delete();
							    	}
					    	
						    		// update family tree
				    				$this->updateFamilyTree($this->getRelationshipType(), $sibling->getID());
							    		
						    		break;
				    			case '2': // save new father
				    			case '3': // father is unknown. save only mother if exists or a blank family
				    					
									$family = new Family();
									if(!isEmptyString($this->getFocusMotherID())){
			               	 			$family->setMotherID($this->getFocusMotherID());
			               	 		}
			               	 		// save family
			               	 		$family->setAddedByID($personid);
			               	 		$family->setCreatedBy($userid);
			    					$family->save();
			    					
				                	$update = new Person();
				                	$update->populate($this->getID());
				                	$update->setFamilyID($family->getID());
				                	$update->save();
					               
						    		// find any duplicated users and delete them
					    			$duplicates = $this->getDuplicatePersons();
							    	if($duplicates->count() > 0){
							    		$duplicates->delete();
							    	}
					    	
					                // update family tree
				    				$this->updateFamilyTree($this->getRelationshipType(), $sibling->getID());
						                
						    		break;
				    		}
				    		break;
				    	case '3':
				    		switch ($this->getPMotherType()){
				    			case '1': // mother already exists. use that of sibling
										
				    				$this->setFamilyID($this->getMSiblingFamilyID());
						    		$this->save();
						    		
						    		// update family tree
				    				$this->updateFamilyTree($this->getRelationshipType(), $sibling->getID());
				    					
						    		break;
				    			case 2: // save new mother
								case 3: // mother is unknown. save only father if exists or a blank family
										
									$family = new Family();
									if(!isEmptyString($this->getFocusFatherID())){
			               	 			$family->setFatherID($this->getFocusFatherID());
			               	 		}
			               	 		// save family
			               	 		$family->setAddedByID($personid);
			               	 		$family->setCreatedBy($userid);
			    					$family->save();
			    					
				                	$update = new Person();
				                	$update->populate($this->getID());
				                	$update->setFamilyID($family->getID());
				                	$update->save();
				                	
						    		// find any duplicated users and delete them
					    			$duplicates = $this->getDuplicatePersons();
							    	if($duplicates->count() > 0){
							    		$duplicates->delete();
							    	}
					                // update family tree
				    				$this->updateFamilyTree($this->getRelationshipType(), $sibling->getID());
				    					
					                break;
				    		}
				    		break;
				    	default:
				    		break; 
				    }
				    break;
				// add children
				case RELATIONSHIP_SON:
	           	case RELATIONSHIP_DAUGHTER:
	           		// load the parent
	           		$family = new Family();
	                $parent = new Person();
				    $parent->populate($this->getFocusID());
				    
				     // clean up invalid families
	                $parent->getInvalidFamiliesForPerson()->delete();
	                
	                // no family exists, so we save new families based on the focus person
	                switch ($parent->isMale() ? $this->getCMotherType() : $this->getCFatherType()){
				    	case 1:
				    		$this->setFamilyID($this->getChildFamilyID());
                			$this->save();
                			
	                		// find any duplicated users and delete them
			    			$duplicates = $this->getDuplicatePersons();
					    	if($duplicates->count() > 0){
					    		$duplicates->delete();
					    	}
					    	
                			// update family tree
					    	$this->updateFamilyTree($this->getRelationshipType(), $parent->getID());
							
				    		break;
				    	case 2:
				    		$updateuserclan = false;
	                		if($parent->isMale()){
				    			$family = new Family();
				    			
	                    	 	$family->getMother()->setGender('2');
	                    	 	$family->getMother()->setOwnerID($userid);
	                    	 	$family->getMother()->setCreatedBy($userid);
		                    	$family->getMother()->setAddedByID($personid);
			               	 	$family->getMother()->setLifeStatus('1');
			               	 	$family->getMother()->setFirstName($this->getPFirstName());
	                    	 	$family->getMother()->setLastName($this->getPLastName());
	                    	 	$family->getMother()->setClanName($this->getPClanName());
			               	 	$family->getMother()->setType($this->getPType());
			               	 	if(!isEmptyString($this->getPClanID())){
			               	 		$family->getMother()->setClanID($this->getPClanID());
			               	 	} 
	                    	 	$family->setFatherID($parent->getID());
	                    	 	
	                    	 } else {
	                    	 	$family->getFather()->setGender('1');
	                    	 	$family->getFather()->setOwnerID($userid);
	                    	 	$family->getFather()->setCreatedBy($userid);
		                    	$family->getFather()->setAddedByID($personid);
			               	 	$family->getFather()->setLifeStatus('1');
			               	 	$family->getFather()->setFirstName($this->getPFirstName());
	                    	 	$family->getFather()->setLastName($this->getPLastName());
	                    	 	$family->getFather()->setClanName($this->getPClanName());
			               	 	$family->getFather()->setType($this->getPType());
			               	 	if(!isEmptyString($this->getPClanID())){
			               	 		$family->getFather()->setClanID($this->getPClanID());
			               	 		$updateuserclan = true;
			               	 	} else { 
			               	 		$family->getFather()->setClanID(!isEmptyString($this->getClanID()) ? $this->getClanID() : NULL);
			               	 	}
	                    	 	$family->setMotherID($parent->getID());
	                    	}
	                    	
	                    	// save the family
	                    	$family->setAddedByID($personid);
			               	$family->setCreatedBy($userid);
			               	$family->save();
		               		
			               	// update familyid the current person
		                	$update = new Person();
		                	$update->populate($this->getID());
		                	$update->setFamilyID($family->getID());
		                	if($updateuserclan){
		                		$update->setClanID($this->getPClanID());
		                	}
		                	$update->save();
			               	
		                	if($parent->isMale()){
		                		$newspouse = $family->getMother();
		                	} else {
		                		$newspouse = $family->getFather();
		                	}
		                	
		                	// find any duplicated users and delete them
			    			$duplicates = $this->getDuplicatePersons();
					    	if($duplicates->count() > 0){
					    		$duplicates->delete();
					    	}
	               			$duplicates2 = $newspouse->getDuplicatePersonsCustom($newspouse->getID());
					    	if($duplicates2->count() > 0){
					    		$duplicates2->delete();
					    	}
				    	
		                	// update family tree
					    	$this->updateFamilyTree($this->getRelationshipType(), $parent->getID());
					    	
			               	// update family tree for spouse
					    	$this->updateFamilyTree(7, $parent->getID(), $newspouse->getID());
					    	
				    		break;
				    	case 3:
							$family = new Family();
				    		if($parent->isMale()){
		               	 		$family->setFatherID($parent->getID());
				    		} else {
		               	 		$family->setMotherID($parent->getID());
				    		}
				    		// save family
				    		$family->setAddedByID($personid);
			               	$family->setCreatedBy($userid);
			                $family->save();
			                
		               		// update familyid the current person
		                	$update = new Person();
		                	$update->populate($this->getID());
		                	$update->setFamilyID($family->getID());
		                	$update->save();
			                
	                		// find any duplicated users and delete them
			    			$duplicates = $this->getDuplicatePersons();
					    	if($duplicates->count() > 0){
					    		$duplicates->delete();
					    	}
					    	
			                // update family tree
					    	$this->updateFamilyTree($this->getRelationshipType(), $parent->getID());
								
				    		break;
	                }
	               	break;
			   	// add patners
	           	case RELATIONSHIP_PARTNER:
						
			    		$partner = new Person();
				        $partner->populate($this->getFocusID());
				        
				         // clean up invalid families
                		$partner->getInvalidFamiliesForPerson()->delete();
                
		                // adding a parnter means creating a new family 
		                $family = new Family(); 
						// $family->setPartnerStatus($this->getPartnerStatus());
		                // depends on what gender this person is
		                if ($this->isMale()) {
		                    // male assume the partner is female
		                    $family->setMotherID($partner->getID());
		                    $family->setFatherID($this->getID());
		                }  
		                if ($this->isFemale()) {
		                    // female assume the partner is male
		                    $family->setFatherID($partner->getID());
		                    $family->setMotherID($this->getID());
		                }
		                // set parent of current family to the one which partner belongs to
		                if(!isEmptyString($partner->getFamilyID())){
		                	$family->setParentID($partner->getFamilyID());
		                }
		                // save new family
		                $family->setAddedByID($personid);
				        $family->setCreatedBy($userid);
					    $family->save();
					    // debugMessage($family->toArray());
					    
	       				// find any duplicated users and delete them
		    			$duplicates = $this->getDuplicatePersons();
				    	if($duplicates->count() > 0){
				    		$duplicates->delete();
				    	}
					    	
		                // update family tree
				    	$this->updateFamilyTree($this->getRelationshipType(), $partner->getID());
							
	                break;
	            case "":
	                // no relationship 
	                break;
				default:
	                // do nothing 
	                break;
	        }
    	
        // invite person if preset
        if($this->getIsInvited() == 1){
        	$this->sendProfileInvitationNotification();
        }
    	
    	return true;
    }
    /**
     * Overide  to save persons relationships
     *	@return true if saved, false otherwise
     */
    function afterUpdate(){
    	$session = SessionWrapper::getInstance();
    	$personid = $session->getVar('personid');
    	$userid = $session->getVar('userid');
    	$conn = Doctrine_Manager::connection();
    	
    	// set new emails
    	$pri_email = $this->getPrimaryEmail();
    	if($pri_email){
    		if($this->getEmail() != $pri_email->getValue1()){
    			$this->setEmail($pri_email->getValue1());
    		}
    	}
    	if(!isEmptyString($this->getUserID())){
    		if($pri_email){
    			if($this->getUser()->getEmail() != $pri_email->getValue1()){
    				$this->getUser()->setEmail($pri_email->getValue1());
    			}
    		}
    		if($this->getFirstName() != $this->getUser()->getFirstName()){
    			$this->getUser()->setFirstName($this->getFirstName());
    		}
    		if($this->getLastName() != $this->getUser()->getLastName()){
    			$this->getUser()->setLastName($this->getLastName());
    		}
    		if($this->getOtherNames() != $this->getUser()->getOtherNames()){
    			$this->getUser()->setOtherNames($this->getOtherNames());
    		}
    		if($this->getGender() != $this->getUser()->getGender()){
    			$this->getUser()->setGender($this->getGender());
    		}
    	}
    	// update the direct relationship
    	$thetree = $this->getTreeRelationship($personid);
    	if($thetree){
	    	if(!isEmptyString($this->getDirectRelationShip()) && ($this->getDirectRelationShip() != $thetree->getRelationShipID())){
	    		$thetree->setRelationShipID($this->getDirectRelationShip());
	    		$thetree->save();
	    	}
    	} else {
	    	$thetree->setTreeID($personid);
	    	$thetree->setFocusID($personid);
	    	$thetree->setRelativeID($this->getID());
    		$thetree->setRelationShipID($this->getDirectRelationShip());
	    	$thetree->save();
    	}
    	
    	// debugMessage($this->toArray());
    	$oldfamily = $this->getFamilyID();
    	// check if the father has been updated
    	$fatherupdated = false;
    	if(!isEmptyString($this->getFFamilyID()) && $this->getFFamilyID() != $oldfamily){
    		$fatherupdated = true;
    		$this->setFamilyID($this->getFFamilyID());
    	}
    	
   		// check if the mother has been updated
   		$motherupdated = false;
    	if(!isEmptyString($this->getMFamilyID()) && $this->getMFamilyID() != $oldfamily){
    		$motherupdated = true;
    		$this->setFamilyID($this->getMFamilyID());
    	}
    	// debugMessage($this->toArray());
    	$this->clearRelated();
    	$this->save();
    	
    	// check if migrated family is redundant and has no children and delete it.
    	if($motherupdated || $fatherupdated){
    		$family = new Family();
    		$family->populate($oldfamily);
    		if(!$family->isValidFamily()){
    			// debugMessage('been deleted');
    			$family->delete();
    		}
    	}
    	// check for invalid families of person and delete
    	$this->getInvalidFamiliesForPerson()->delete();
    	
    	return true;
    }
    
    # update family tree
    function updateFamilyTree($type, $focusid, $relativeid="", $otherid ="", $othertype="") {
    	$session = SessionWrapper::getInstance();
    	$personid = $session->getVar('personid');
    	
    	$subscriber = new Person();
    	$subscriber->populate($personid);			
    	$thedefaultline = $subscriber->getFocusTreeLine();
    	
    	$focus = new Person();
    	$focus->populate($focusid);
    	$thetreeline = $focus->getFocusTreeLine();
    			
        $tree = new FamilyTree();
        $tree->setTreeID($subscriber->getID());
		$tree->setFocusID($subscriber->getID());
		if(!isEmptyString($relativeid)){
			$tree->setRelativeID($relativeid);
		} else {
			$tree->setRelativeID($this->getID());
		}
		$tree->setAddedAs($focus->getID());
		$tree->setAddRelationship($type);
		
		$level = NULL;
		if($type == 1 || $type == 2){
			if(!isEmptyString($thetreeline->getLevel())){
				$level = $thetreeline->getLevel() + 1;
			}
		}
		if($type == 3 || $type == 4 || $type == 7){
			if(!isEmptyString($thetreeline->getLevel())){
				$level = $thetreeline->getLevel() + 0;
			}
		}
		if($type == 5 || $type == 6){
			if(!isEmptyString($thetreeline->getLevel())){
				$level = $thetreeline->getLevel() - 1;
			}
		}
		$tree->setLevel($level);
		$path = new MatrixPath();
		
		$relativerelationship = NULL;
		if($focus->getID() == $subscriber->getID()){
			# focus person is the subscriber. determine relationship
			$focusrel = 0;
			$line = $path->getPathRelationship($focusrel, $type);
			if($line){
				// debugMessage($line->toArray());
				$value = $line->getValue();
				if($line->getUseGender() == 1 && $focus->isMale()){
					$value = $line->getMaleValue();
				}
				if($line->getUseGender() == 1 && $focus->isFemale()){
					$value = $line->getFemaleValue();
				}
				$relativerelationship = $value;
			} else {
				$relativerelationship = $type;
			}
			if($type == 1){
				$tree->setPaternity(1);
			}
			if($type == 2){
				$tree->setPaternity(2);
			}
		} else {
			if($thetreeline){
				// family tree entry for focus person  
				$focusrel = $thetreeline->getRelationshipID();
				if(!isEmptyString($focusrel)){
					$line = $path->getPathRelationship($focusrel, $type);
					// debugMessage($thetreeline->toArray()); debugMessage($line->toArray());
					if($line){
						$value = $line->getValue();
						if($line->getUseGender() == 1 && $subscriber->isMale()){
							$value = $line->getMaleValue();
						}
						if($line->getUseGender() == 1 && $subscriber->isFemale()){
							$value = $line->getFemaleValue();
						}
						// set paternity from parent
						$tree->setPaternity($thetreeline->hasPaternity() ? $thetreeline->getPaternity() : NULL);
						if($thetreeline->hasPaternity()){
							if($thetreeline->isOnFatherSide() && $line->determineUsingPaternity()){
								$value = $line->getPaternalValue();
							}
							if($thetreeline->isOnMotherSide() && $line->determineUsingPaternity()){
								$value = $line->getMaternalValue();
							}
						}	
						$relativerelationship = $value;
					}
				}
			}
		}
		
    	// enforce paternity at subscriber's mother and father level
		if($tree->getRelativeID() == $subscriber->getFatherID()){
			$tree->setPaternity(2);
		}
		if($tree->getRelativeID() == $subscriber->getMotherID()){
			$tree->setPaternity(1);
		}
						
		$tree->setRelationShipID($relativerelationship);
		// debugMessage($tree->toArray()); // exit();
		$tree->save(); 
		
		return true;
    }
	/*
	 * Custom update logic after invite
	 */
	function transactionInviteUpdate($isnewparent, $parenttype){
		$conn = Doctrine_Manager::connection();
		// begin transaction to save
		try {
			$conn->beginTransaction();
			// save
			$this->save();
			// set owner and 
			$this->setOwnerID($this->getUserID());
			$this->getUser()->setCreatedBy($this->getUserID());
			
			$this->save();
			// set father and family relationship updates
			if($isnewparent == 1 && $parenttype == 1){
				if(!isEmptyString($this->getFamilyID()) && !isEmptyString($this->getFamily()->getFatherID())){
					$updatefather = true;
					$this->getFamily()->getFather()->setOwnerID($this->getUserID());
					$this->getFamily()->getFather()->setCreatedBy($this->getUserID());
					$this->getFamily()->getFather()->setAddedByID($this->getID());
					//$this->getFamily()->getFather()->save();
					$this->getFamily()->setCreatedBy($this->getUserID());
					$this->getFamily()->save();
				}
			}
			
			// set mother and family relationship updates
			if($isnewparent == 1 && $parenttype == 2){
				if(!isEmptyString($this->getFamilyID()) && !isEmptyString($this->getFamily()->getMotherID())){
					$updatemother = true;
					$this->getFamily()->getMother()->setOwnerID($this->getUserID());
					$this->getFamily()->getMother()->setCreatedBy($this->getUserID());
					$this->getFamily()->getMother()->setAddedByID($this->getID());
					//$this->getFamily()->getMother()->save();
					$this->getFamily()->setCreatedBy($this->getUserID());
					$this->getFamily()->save();
				}
			}
			
			// setup family tree and matrix data if person is a muganda (new tree with father)
			if($parenttype == 1){
				// setup family tree for subscriber
				$ptree1 = $this->getFamilyTrees()->get(0);
				$ptree1->setTreeID($this->getID());
				$ptree1->setFocusID($this->getID());
				$ptree1->setRelativeID($this->getID());
				$ptree1->setLevel('10');
				$ptree1->setRelationShipID('0');
				$ptree1->setAddRelationship('0');
				$ptree1->setAddedAs($this->getID());
				$ptree1->save();
				
				$ptree2 = $this->getFamilyTrees()->get(1);
				$ptree2->setTreeID($this->getID());
				$ptree2->setFocusID($this->getID());
				$ptree2->setRelativeID($this->getFatherID());
				$ptree2->setLevel('9');
				$ptree2->setRelationShipID('1');
				$ptree2->setAddRelationship('1');
				$ptree2->setAddedAs($this->getID());
				$ptree2->save();
				
			}
			// setup family tree and matrix data if person is a mujjwa (new tree with mother)
			if ($parenttype == 2) {
				$ptree1 = $this->getFamilyTrees()->get(0);
				$ptree1->setTreeID($this->getID());
				$ptree1->setFocusID($this->getID());
				$ptree1->setRelativeID($this->getID());
				$ptree1->setLevel('10');
				$ptree1->setRelationShipID('0');
				$ptree1->setAddRelationship('0');
				$ptree1->setAddedAs($this->getID());
				$ptree1->save();
				
				$ptree2 = $this->getFamilyTrees()->get(1);
				$ptree2->setTreeID($this->getID());
				$ptree2->setFocusID($this->getID());
				$ptree2->setRelativeID($this->getMotherID());
				$ptree2->setLevel('9');
				$ptree2->setRelationShipID('2');
				$ptree2->setAddRelationship('2');
				$ptree2->setAddedAs($this->getID());
				$ptree2->save();
			}
			
			// commit changes
			$conn->commit();
		} catch(Exception $e){
			$conn->rollback();
			// debugMessage('Error is '.$e->getMessage());
			throw new Exception($e->getMessage());
		}

		$this->sendInviteConfirmationNotification();
		// exit();
		return true;
	}
	/**
	 * Return the date of birth object from event 
	 * @return $array 
	 */
	function getAllEvents() {
		$q = Doctrine_Query::create()->from('Event e')->where("e.personid = '".$this->getID()."'");
		return $q->execute();
	}
	/**
	 * Return the date of birth object from event 
	 * @return $array 
	 */
	function getBirthDetails() {
		$q = Doctrine_Query::create()->from('Event e')->where("e.personid = '".$this->getID()."' AND e.eventtype = '1'");
		return $q->fetchOne();
	}
	# the date of birth
	function getPersonBirth() {
		$date = '';
		$dobevent = $this->getBirthDetails();
		// debugMessage($dobevent);
		if(!$dobevent){
			if (!isEmptyString($this->getDateOfBirth())){
				$date = $this->getDateOfBirth();
			}
		} else {
			$date = $dobevent->getFullStartDate();
			if(isEmptyString($date) && !isEmptyString($this->getDateOfBirth())){
				$date = $this->getDateOfBirth();
			}
		}
		return $date;
	}
	# format the date of birth in mysql
	function formatFullDateOfBirth(){
		
	}
	/**
	 * Return the exact date of birth 
	 * @return string dateofbirth 
	 */
	function getBirthDateFromEvent() {
		$dobevent = $this->getBirthDetails();
		if(!$dobevent){
			return "--";
		}
		$thedate = "";
		$allmonths = getAllMonthsAsShortNames();
		// debugMessage($allmonths);
		$sdate = ""; $edate = "";
		// start date
		if(!isEmptyString($dobevent->getStartYear()) && !isEmptyString($dobevent->getStartMonth()) && !isEmptyString($dobevent->getStartDay())){
			$fulldate = $dobevent->getStartYear()."-".$dobevent->getStartMonth()."-".$dobevent->getStartDay();
			// debugMessage($fulldate);
			$date = new DateTime($fulldate);
			$sdate = $date->format('M j, Y');
		} else {
			if(!isEmptyString($dobevent->getStartMonth())){
				$sdate .= $allmonths[$dobevent->getStartMonth()]." ";
			}
			if(!isEmptyString($dobevent->getStartYear())){
				$sdate .= $dobevent->getStartYear();
			}
		}
		// end date
		if(!isEmptyString($dobevent->getEndYear()) && !isEmptyString($dobevent->getEndMonth()) && !isEmptyString($dobevent->getEndDay())){
			$efulldate = $dobevent->getEndYear()."-".$dobevent->getEndMonth()."-".$dobevent->getEndDay();
			$date = new DateTime($efulldate);
			$edate = $date->format('M j, Y');
		} else {
			if(!isEmptyString($dobevent->getEndMonth())){
				$edate .= $allmonths[$dobevent->getEndMonth()]." ";
			}
			if(!isEmptyString($dobevent->getEndYear())){
				$edate .= $dobevent->getEndYear();
			}
		}		
		switch ($dobevent->getStartType()){
			case 1:
				$thedate = $sdate;
				break;
			case 2:
				$thedate = "<span class='labeldescription'>(Between) </span>".$sdate." <span class='labeldescription'> (And) </span>".$edate;
				break;
			case 3:
				$thedate = "<span class='labeldescription'>(From) </span>".$sdate." <span class='labeldescription'> (To) </span>".$edate;
				break;
			case 9:
			case 10:
				$thedate = $sdate." <span class='labeldescription'>(".$dobevent->getStartTypeLabel().")</span>";
				break;
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
				$thedate = "<span class='labeldescription'>(".$dobevent->getStartTypeLabel().")</span> ".$sdate;
				break;
			case 11:
				$thedate = $dobevent->getStartText();
				break;
			default:
				break;
		}
		return $thedate;
	}
/**
	 * Return the exact date of birth 
	 * @return string dateofbirth 
	 */
	function getExactDateOfBirth() {
		$dobevent = $this->getBirthDetails();
		if(!$dobevent){
			if(!isEmptyString($this->getDateOfBirth())){
				return changeMySQLDateToPageFormat($this->getDateOfBirth());
			} else {
				return "--";
			}
		}
		$thedate = "";
		$allmonths = getAllMonthsAsShortNames();
		$sdate = ""; $edate = "";
		if(!isEmptyString($dobevent->getStartYear()) && !isEmptyString($dobevent->getStartMonth()) && !isEmptyString($dobevent->getStartDay())){
			$sdate = changeMySQLDateToPageFormat($dobevent->getStartYear()."-".$dobevent->getStartMonth()."-".$dobevent->getStartDay());
			$thedate = $sdate;
		} else {
			if(!isEmptyString($dobevent->getStartMonth())){
				$sdate .= $allmonths[$dobevent->getStartMonth()]." ";
			}
			if(!isEmptyString($dobevent->getStartYear())){
				$sdate .= $dobevent->getStartYear();
			}
			$thedate = $sdate;
		}
		return $thedate;
	}
	/**
	 * Return the birth location 
	 * @return string locationtext 
	 */
	function getBirthPlaceFromEvent() {
		$dobevent = $this->getBirthDetails();
		if(!$dobevent){
			return "";
		}
		return $dobevent->getLocationText();
	}
	/**
	 * Return the date of death object from event 
	 * @return $array 
	 */
	function getDeathDetails() {
		$q = Doctrine_Query::create()->from('Event e')->where("e.personid = '".$this->getID()."' AND e.eventtype = '2'");
		return $q->fetchOne();
	}
	/**
	 * Return the exact date of death 
	 * @return string dateofbirth 
	 */
	function getDeathDateFromEvent() {
		$dodevent = $this->getDeathDetails();
		if(!$dodevent){
			return "--";
		}
		$thedate = "";
		$allmonths = getAllMonthsAsShortNames();
		$sdate = ""; $edate = "";
		if(!isEmptyString($dodevent->getStartYear()) && !isEmptyString($dodevent->getStartMonth()) && !isEmptyString($dodevent->getStartDay())){
			$sdate = changeMySQLDateToPageFormat($dodevent->getStartYear()."-".$dodevent->getStartMonth()."-".$dodevent->getStartDay());
			$edate = changeMySQLDateToPageFormat($dodevent->getEndYear()."-".$dodevent->getEndMonth()."-".$dodevent->getEndDay());
		} else {
			if(!isEmptyString($dodevent->getStartMonth())){
				$sdate .= $allmonths[$dodevent->getStartMonth()]." ";
			}
			if(!isEmptyString($dodevent->getStartYear())){
				$sdate .= $dodevent->getStartYear();
			}
			if(!isEmptyString($dodevent->getEndMonth())){
				$edate .= $allmonths[$dodevent->getEndMonth()]." ";
			}
			if(!isEmptyString($dodevent->getEndYear())){
				$edate .= $dodevent->getEndYear();
			}
		}
				
		switch ($dodevent->getStartType()){
			case 2:
				$thedate = "<span class='labeldescription'>(Between) </span>".$sdate." <span class='labeldescription'> (And) </span>".$edate;
				break;
			case 3:
				$thedate = "<span class='labeldescription'>(From) </span>".$sdate." <span class='labeldescription'> (To) </span>".$edate;
				break;
			case 1:
			case 9:
			case 10:
				// $thedate = $sdate." <span class='labeldescription'>(".$dodevent->getStartTypeLabel().")</span>";
				$thedate = $sdate;
				break;
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
				$thedate = "<span class='labeldescription'>(".$dodevent->getStartTypeLabel().")</span> ".$sdate;
				break;
			case 11:
				$thedate = $dodevent->getStartText();
				break;
			default:
				break;
		}
		return $thedate;
	}
	/**
	 * Return the birth location 
	 * @return string locationtext 
	 */
	function getDeathPlaceFromEvent() {
		$dodevent = $this->getDeathDetails();
		if(!$dodevent){
			return "";
		}
		return $dodevent->getLocationText();
	}
	/**
	 * Return  all contacts
	 *
	 * @return collection the contacts
	 */
	function getAllContacts() {
		$q = Doctrine_Query::create()->from('Contact c')->where("c.personid = '".$this->getID()."'");
		return $q->execute();
	}
	/**
	 * Return the email address contacts
	 *
	 * @return collection the email contacts
	 */
	function getEmailContacts() {
		$q = Doctrine_Query::create()->from('Contact c')->where("c.personid = '".$this->getID()."' AND c.type = '1'");
		return $q->execute();
	}
	/**
	 * Return the primary email address contacts
	 *
	 * @return collection the primary email contact object
	 */
	function getPrimaryEmail() {
		$q = Doctrine_Query::create()->from('Contact c')->where("c.personid = '".$this->getID()."' AND c.type = '1' AND c.isprimary = '1'");
		return $q->fetchOne();
	}
	/**
	 * Return the primary email address text
	 *
	 * @return string the email address
	 */
	function getPrimaryEmailText() {
		if($this->getPrimaryEmail()){
			return $this->getPrimaryEmail()->getValue1();
		}
		if(!$this->getPrimaryEmail() && $this->getEmailContacts()->count() == '1'){
			return $this->getEmailContacts()->get(0)->getValue1();
		}
		if(!isEmptyString($this->getEmail())){
			return $this->getEmail();
		}
		return '';
	}
	/**
	 * Return the phone contacts for profile
	 *
	 * @return collection the phone contacts
	 */
	function getPhoneContacts() {
		$q = Doctrine_Query::create()->from('Contact c')->where("c.personid = '".$this->getID()."' AND c.type = '2'");
		return $q->execute();
	}
	/**
	 * Return the primary phone contact for profile
	 *
	 * @return collection the primary phone contact
	 */
	function getPrimaryPhoneContact() {
		$q = Doctrine_Query::create()->from('Contact c')->where("c.personid = '".$this->getID()."' AND c.type = '2' AND c.isprimary = '1' ");
		return $q->fetchOne();
	}
	/**
	 * Return the primary phone contact text
	 *
	 * @return string the phone number
	 */
	function getPrimaryPhoneText() {
		if($this->getPrimaryPhoneContact()){
			return $this->getPrimaryPhoneContact()->getValue1()." - ".$this->getPrimaryPhoneContact()->getValue2()." - ".$this->getPrimaryPhoneContact()->getValue3();
		}
		if(!$this->getPrimaryPhoneContact() && $this->getPhoneContacts()->count() == '1'){
			$pri = $this->getPhoneContacts()->get(0);
			return $pri->getValue1()." - ".$pri->getValue2()." - ".$pri->getValue3();
		}
		return '';
	}
	/**
	 * Return the web addresses for profile
	 *
	 * @return collection the web addresses
	 */
	function getWebContacts() {
		$q = Doctrine_Query::create()->from('Contact c')->where("c.personid = '".$this->getID()."' AND c.type = '3'");
		return $q->execute();
	}
	/**
	 * Return the physical addresses for profile
	 *
	 * @return collection the physical address
	 */
	function getPhysicalAddresses() {
		$q = Doctrine_Query::create()->from('Address a')->where("a.personid = '".$this->getID()."' AND a.type <> '4'");
		return $q->execute();
	}
	/**
	 * Return the physical addresses for profile
	 *
	 * @return collection the physical address
	 */
	function getAllAddresses() {
		$q = Doctrine_Query::create()->from('Address a')->where("a.personid = '".$this->getID()."'");
		return $q->execute();
	}
	/**
	 * Return the primary physical addresses for profile
	 *
	 * @return collection the physical address
	 */
	function getPrimaryPhysicalAddress() {
		$q = Doctrine_Query::create()->from('Address a')->where("a.personid = '".$this->getID()."' AND a.isprimary = '1' ");
		return $q->fetchOne();
	}
	/**
	 * Return the primary physical addresses for profile
	 *
	 * @return collection the physical address
	 */
	function getButakaAddress() {
		$q = Doctrine_Query::create()->from('Address a')->where("a.personid = '".$this->getID()."' AND a.type = '4'");
		return $q->fetchOne();
	}
	/**
	 * Determine if butaka address for person has been added to database
	 *
	 * @return true if present, false otherwise
	 */
	function hasButakaAddress() {
		if(!$this->getButakaAddress()){
			return false;
		}
		return true;
	}
	/**
	 * Determine if primary address for person has been added to database
	 *
	 * @return true if present, false otherwise
	 */
	function hasPrimaryAddress() {
		if(!$this->getPhysicalAddresses()){
			return false;
		}
		return true;
	}
	/**
	 * Return the primary address text
	 *
	 * @return string the address
	 */
	function getPrimaryAddressText() {
		if($this->getPrimaryPhysicalAddress()){
			return $this->getPrimaryPhysicalAddress()->getFullAddress();
		}
		if(!$this->getPrimaryPhysicalAddress() && $this->getPhysicalAddresses()->count() == '1'){
			$pri = $this->getPhysicalAddresses()->get(0);
			return $pri->getFullAddress();
		}
		return '';
	}
	/**
	 * Return the primary address as country and village
	 *
	 * @return string the address
	 */
	function getPrimaryLocation() {
		$text = '';
		if($this->getPrimaryPhysicalAddress()){
			$add = $this->getPrimaryPhysicalAddress();
			if(!isEmptyString($add->getVillageName())){
				$text .= $add->getVillageName().", ";
			}
			if(!isEmptyString($add->getCountryName())){
				$text .= $add->getCountryName()." ";
			}
		}
		if(!$this->getPrimaryPhysicalAddress() && $this->getPhysicalAddresses()->count() == '1'){
			$pri = $this->getPhysicalAddresses()->get(0);
			if(!isEmptyString($pri->getVillageName())){
				$text .= $pri->getVillageName().", ";
			}
			if(!isEmptyString($pri->getCountryName())){
				$text .= $pri->getCountryName()." ";
			}
		}
		return $text;
	}
	/**
	 * Return the profile languages
	 *
	 * @return collection the profile languages
	 */
	function getProfileInterests() {
		$q = Doctrine_Query::create()->from('Interest i')->where("i.personid = '".$this->getID()."'");
		return $q->execute();
	}
	/**
	 * Return the profile languages
	 *
	 * @return collection the profile languages
	 */
	function getProfileLanguages() {
		$q = Doctrine_Query::create()->from('Interest i')->where("i.personid = '".$this->getID()."' AND i.type = '1'");
		return $q->execute();
	}
	/**
     *Get all language ids  
     *
     * @return array of the language ids on profile
     */
	function getAllLanguages() {
        $profilelanguages = $this->getProfileLanguages();
        if ($profilelanguages->count() == 0) {
            return "";
        }
        $data_array = array(); 
        foreach($profilelanguages as $thelanguage) {
            $data_array[] = $thelanguage->getValue2(); 
        }
        return $data_array; 
    }
	/**
     * Display a list of languages for the profile
     *
     * @return String HTML list of languages for the profile
     */
    function displayLanguageCategories() {
        $profilelanguages = $this->getProfileLanguages();
        if ($profilelanguages->count() == 0) {
            return "None";
        }
        $data_array = array(); 
        foreach($profilelanguages as $thelanguage) {
            $data_array[] = $thelanguage->getValue2(); 
        }
        return createHTMLListFromArray($data_array); 
    }
	function displayLanguageList() {
        $profilelanguages = $this->getProfileLanguages();
        if ($profilelanguages->count() == 0) {
            return "--";
        }
        $data_array = array(); 
        foreach($profilelanguages as $thelanguage) {
            $data_array[] = $thelanguage->getLanguageLabel(); 
        }
        return implode(', ', $data_array); 
    }
	/**
	 * Return profile interests for language
	 * @return $array 
	 */
	function getLanguagesForProfile($personid, $language) {
		$conn = Doctrine_Manager::connection();
		$existing_query = "SELECT * from interest as i where i.personid = '".$personid."' AND i.type = '1' AND i.value2 = '".$language."' ";
		// debugMessage($existing_query);
		$result = $conn->fetchRow($existing_query);
		return $result;
	}
	/**
	 * Return the profile ethinicities
	 *
	 * @return collection the profile ethinicities
	 */
	function getProfileEthinicities() {
		$q = Doctrine_Query::create()->from('Interest i')->where("i.personid = '".$this->getID()."' AND i.type = '2'");
		return $q->execute();
	}
	/**
     *Get all ethinicty ids  
     *
     * @return array of the ethinicity ids on profile
     */
	function getAllEthinicities() {
        $profileethinicities = $this->getProfileEthinicities();
        if ($profileethinicities->count() == 0) {
            return "";
        }
        $data_array = array(); 
        foreach($profileethinicities as $theethinicity) {
            $data_array[] = $theethinicity->getValue2(); 
        }
        return $data_array; 
    }
	/**
     * Display a list of ethinicities for the profile
     *
     * @return String HTML list of ethinicities for the profile
     */
    function displayEthinicityCategories() {
        $profileethinicities = $this->getProfileEthinicities();
        if ($profileethinicities->count() == 0) {
            return "None";
        }
        $data_array = array(); 
        foreach($profileethinicities as $theethinicity) {
            $data_array[] = $theethinicity->getValue2(); 
        }
        return createHTMLListFromArray($data_array); 
    }
	function displayEthinicityList() {
        $profileethinicities = $this->getProfileEthinicities();
        if ($profileethinicities->count() == 0) {
            return "--";
        }
        $data_array = array(); 
        foreach($profileethinicities as $theethinicity) {
            $data_array[] = $theethinicity->getEthinicityLabel(); 
        }
        return implode(', ', $data_array); 
    }
	/**
	 * Return profile interests for ethinicity
	 * @return $array 
	 */
	function getEthinicitiesForProfile($personid, $ethinicity) {
		$conn = Doctrine_Manager::connection();
		$existing_query = "SELECT * from interest as i where i.personid = '".$personid."' AND i.type = '2' AND i.value2 = '".$ethinicity."' ";
		// debugMessage($existing_query);
		$result = $conn->fetchRow($existing_query);
		return $result;
	}
	# return the hair color label
	function getHairColorLabel(){
		$colors = getHairColors();
		return isEmptyString($this->getHairColor()) ? '--' : $colors[$this->getHairColor()];  
	}
	# return the eye color label
	function getEyeColorLabel(){
		$colors = getEyeColors();
		return isEmptyString($this->getEyeColor()) ? '--' : $colors[$this->getEyeColor()];  
	}
	# return the eye color label
	function getReligionLabel(){
		$religions = getReligions();
		return isEmptyString($this->getReligion()) ? '--' : $religions[$this->getReligion()];  
	}
	# invite one user to join. already existing persons
	function inviteOne($invitedbyid) {
		// set inviter 
		$this->setInvitedByID($invitedbyid);
		// send email
		$this->sendProfileInvitationNotification();
		$this->setDateInvited(date('Y-m-d'));
		$this->setHasAcceptedInvite('0');
		$this->setIsInvited('1');
		$this->save();
		
		return true;
	}
	# Send notification to invite person to create an account
	function sendProfileInvitationNotification() {
		$template = new EmailTemplate(); 
		# create mail object
		$mail = getMailInstance();
		$view = new Zend_View(); 

		// assign values
		$template->assign('firstname', $this->getFirstName());
		$template->assign('inviter', $this->getInvitedBy()->getName());
		
		// the actual url will be built in the view
		$viewurl = $template->serverUrl($template->baseUrl('signup/index/profile/'.encode($this->getID())."/")); 
		$template->assign('invitelink', $viewurl);
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		
		// configure base stuff
		$mail->addTo($this->getEmail(), $this->getName());
		// set the send of the email address
		if($this->getStatusFlag() == 2){
			$sendername = "Alexandra Nakazzi";
			$senderemail = "nbnakazzi@gandaancestry.com";
		} else {
			$sendername =  $this->translate->_('useraccount_email_notificationsender');
			$senderemail = $this->config->notification->emailmessagesender;
		}
		$mail->setFrom($senderemail, $sendername);
		
		$mail->setSubject(sprintf($this->translate->_('useraccount_email_subject_invite'), $this->translate->_('appname')));
		// render the view as the body of the email
		if($this->getStatusFlag() == 2){
			$mail->setBodyHtml($template->render('betainvitenotification.phtml'));
			// debugMessage($template->render('betainvitenotification.phtml')); // exit();
		} else {
			$mail->setBodyHtml($template->render('invitenotification.phtml'));
			// debugMessage($template->render('invitenotification.phtml')); // exit();
		}
		$mail->send();
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		$mail->clearFrom();
		
		return true;
	}
	# invite many users to join. already existing persons
	function inviteMany($invitearray) {
		$conn = Doctrine_Manager::connection();
		// send email to all invited
		if($this->sendMultipleProfileInvitationNotification($invitearray)){
			// update profile of persons invited
			$ids = array();
			try {
				// begin transaction
				$conn->beginTransaction();
				
				$person_collection = new Doctrine_Collection(Doctrine_Core::getTable("Person"));
				foreach ($invitearray as $value) {
					$person = new Person();
					$person->populate($value['id']);
					$person->setEmail($value['email']);
					$person->setInvitedByID($this->getID());
					$person->setDateInvited(date('Y-m-d'));
					$person->setHasAcceptedInvite('0');
					$person->setIsInvited('1');
					// $person->save();
					$person_collection->add($person);
				}
				// debugMessage($person_collection->toArray());
				// save all updates to profiles
				$person_collection->save();
				
				$conn->commit();
			} catch(Exception $e){
				$conn->rollback();
				// debugMessage('Error is '.$e->getMessage());
				throw new Exception($e->getMessage());
			}
		}
		
		return true;
	}
	# Send notification to invite person to create an account
	function sendMultipleProfileInvitationNotification($invitearray) {
		$template = new EmailTemplate(); 
		# create mail object
		$mail = getMailInstance();
		$view = new Zend_View(); 
		
		// assign values
		$template->assign('inviter', $this->getName());
		$mail->setSubject(sprintf($this->translate->_('useraccount_email_subject_invite'), $this->translate->_('appname')));
		// set the send of the email address
		$mail->setFrom($this->config->notification->emailmessagesender, $this->translate->_('useraccount_email_notificationsender'));
		
		// set the subject for the different participants and check that user can receive email
		foreach($invitearray as $line) {
			$person = new Person();
			$person->populate($line['id']);
			$template->assign('firstname', $person->getFirstName());
			
			// the actual url will be built in the view
			$viewurl = $template->serverUrl($template->baseUrl('signup/index/profile/'.encode($person->getID())."/")); 
			$template->assign('invitelink', $viewurl);
		
			$mail->setBodyHtml($template->render('invitenotification.phtml'));
			// debugMessage($template->render('invitenotification.phtml'));
			$mail->addTo($line['email'], $person->getName());
			$mail->send();
			// clear body and recipient in each email
			$mail->clearRecipients();
			$mail->setBodyHtml('');
		}
		return true;
	}
	# Send notification to invite persons who are just friends
	function sendFriendsInvitationNotification($invitearray) {
		$template = new EmailTemplate(); 
		# create mail object
		$mail = getMailInstance();
		$view = new Zend_View(); 
		
		// assign values
		$template->assign('inviter', $this->getName());
		$mail->setSubject(sprintf($this->translate->_('useraccount_email_subject_invite'), $this->translate->_('appname')));
		// set the send of the email address
		$mail->setFrom($this->config->notification->emailmessagesender, $this->translate->_('useraccount_email_notificationsender'));
		
		// set the subject for the different participants and check that user can receive email
		foreach($invitearray as $email) {
			$template->assign('firstname', 'Friend');
			
			// the actual url will be built in the view
			$viewurl = $template->serverUrl($template->baseUrl('signup')); 
			$template->assign('invitelink', $viewurl);
		
			$mail->setBodyHtml($template->render('invitenotification.phtml'));
			// debugMessage($template->render('invitenotification.phtml'));
			// $mail->addTo($email);
			$mail->send();
			// clear body and recipient in each email
			$mail->clearRecipients();
			$mail->setBodyHtml('');
		}
		return true;
	}
	/**
	 * Send notification to inform user that profile has been activated
	 * @return bool whether or not the notification email has been sent
	 */
	function sendInviteConfirmationNotification() {
		$template = new EmailTemplate(); 
		# create mail object
		$mail = getMailInstance();
		$view = new Zend_View(); 

		// assign values
		$template->assign('firstname', $this->getFirstName());
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		
		// configure base stuff
		$mail->addTo($this->getEmail(), $this->getName());
		// set the send of the email address
		$mail->setFrom($this->config->notification->emailmessagesender, $this->translate->_('useraccount_email_notificationsender'));
		
		$mail->setSubject(sprintf($this->translate->_('useraccount_email_subject_invite_confirmation'), $this->translate->_('appname')));
		// render the view as the body of the email
		$mail->setBodyHtml($template->render('inviteconfirmation.phtml'));
		// debugMessage($template->render('inviteconfirmation.phtml')); // exit();
		$mail->send();
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		$mail->clearFrom();
		
		return true;
	}
	# send confirmation for application to user
	function sendBetaConfirmationNotification() {
		$template = new EmailTemplate(); 
		# create mail object
		$mail = getMailInstance();
		$view = new Zend_View(); 

		// assign values
		$template->assign('firstname', $this->getFirstName());
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		
		// configure base stuff
		$mail->addTo($this->getEmail(), $this->getName());
		// set the send of the email address
		$sendername = "Alexandra Nakazzi";
		if(ENV == 'DEV'){
			$senderemail = $this->config->notification->emailmessagesender;
		}
		if(ENV == 'STAGING'){
			$senderemail = "nbnakazzi@gandaancestry.com";
		}
		if(ENV == 'PROD'){
			$senderemail = "nbnakazzi@gandaancestry.com";
		}
		
		$mail->setFrom($senderemail, $sendername);
		$mail->setSubject(sprintf($this->translate->_('useraccount_email_subject_beta_confirmation'), $this->translate->_('appname')));
		// render the view as the body of the email
		$mail->setBodyHtml($template->render('betaconfirmation.phtml'));
		// debugMessage($template->render('betaconfirmation.phtml')); // exit();
		$mail->send();
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		$mail->clearFrom();
		
		return true;
	}
	# send confirmation to admin
	function sendBetaAdminNotification() {
		$template = new EmailTemplate(); 
		# create mail object
		$mail = getMailInstance();
		$view = new Zend_View(); 

		// assign values
		$template->assign('name', $this->getName());
		$template->assign('email', $this->getEmail());
		$template->assign('clan', $this->getClan()->getName());
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		
		// set the send of the email address
		if(ENV == 'DEV'){
			$senderemail = $this->config->notification->emailmessagesender;
			$copy1email = $this->config->notification->emailmessagesender;
		}
		if(ENV == 'STAGING'){
			$senderemail = "notifications@gandaancestry.com";
			$copy1email = $this->config->notification->emailmessagesender;
		}
		if(ENV == 'PROD'){
			$senderemail = "notifications@gandaancestry.com";
			$copy1email = "info@gandaancestry.com";
		}
		// configure base stuff
		$mail->addTo($copy1email, 'Admin');
		$mail->setFrom($senderemail, "GandaAncestry");
		$mail->setSubject("New Beta User Application");
		// render the view as the body of the email
		$mail->setBodyHtml($template->render('betaadminconfirmation.phtml'));
		// debugMessage($template->render('betaadminconfirmation.phtml')); // exit();
		$mail->send();
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		$mail->clearFrom();
		
		return true;
	}
	# Send notification to invite a friend
	function tellFriendsNotification($invitearray) {
		$template = new EmailTemplate(); 
		# create mail object
		$mail = getMailInstance();
		$view = new Zend_View(); 
		
		$first = $invitearray[0];
		// debugMessage($first);
		// assign values
		$template->assign('inviter', $first['sendername']);
		$mail->setSubject("Announcing Baganda Website GandaAncestry");
		// set the send of the email address
		$mail->setFrom($first['senderemail'], $first['sendername']);
		
		// set the subject for the different participants and check that user can receive email
		foreach($invitearray as $key => $line) {
			$template->assign('firstname', 'Friend');
			$template->assign('intromsg', $line['intromsg']);
			
			// the actual url will be built in the view
			$viewurl = $template->serverUrl($template->baseUrl('annnouncement')); 
			$template->assign('detailslink', $viewurl);
		
			$mail->setBodyHtml($template->render('tellfriendnotification.phtml'));
			// debugMessage($template->render('tellfriendnotification.phtml'));
			$mail->addTo($line['email']);
			$mail->send();
			// clear body and recipient in each email
			$mail->clearRecipients();
			$mail->setBodyHtml('');
		}
		
		return true;
	}
	# Send notification to invite a friend
	function tellFriendsAboutGANotification($dataarray) {
		$template = new EmailTemplate(); 
		# create mail object
		$mail = getMailInstance();
		$view = new Zend_View(); 
		
		$first = $dataarray[0];
		
		// assign values
		$template->assign('sendername', $first['sendername']);
		$mail->setSubject($first['subject']);
		// set the send of the email address
		$mail->setFrom($first['senderemail'], $first['sendername']);
		
		// set the subject for the different participants and check that user can receive email
		foreach($dataarray as $key => $line) {
			$template->assign('themessage', $line['message']);
			
			// the actual url will be built in the view
			// $viewurl = $template->serverUrl($template->baseUrl('annnouncement')); 
			// $template->assign('detailslink', $viewurl);
			$mail->addTo($line['email']);
			$mail->setBodyHtml($template->render('emailfriendsnotification.phtml'));
			// debugMessage($template->render('emailfriendsnotification.phtml'));
			
			$mail->send();
			// clear body and recipient in each email
			$mail->clearRecipients();
			$mail->setBodyHtml('');
		}
		
		return true;
	}
	# Send contact us notification
	function sendContactNotification($dataarray) {
		$template = new EmailTemplate(); 
		# create mail object
		$mail = getMailInstance();
		$view = new Zend_View(); 
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		
		// debugMessage($first);
		// assign values
		$template->assign('name', $dataarray['name']);
		$template->assign('email', $dataarray['email']);
		$template->assign('subject', $dataarray['subject']);
		$template->assign('message', nl2br($dataarray['message']));
		
		$mail->setSubject("GandaAncestry: ".$dataarray['subject']);
		// set the send of the email address
		$mail->setFrom($dataarray['email'], $dataarray['name']);
		
		// configure base stuff
		$mail->addTo($this->config->notification->supportemailaddress);
		// render the view as the body of the email
		$mail->setBodyHtml($template->render('contactconfirmation.phtml'));
		// debugMessage($template->render('contactconfirmation.phtml')); //exit();
		$mail->send();
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		$mail->clearFrom();
		
		return true;
	}
	# determine if person has profile image
	function hasProfileImage(){
		$real_path = APPLICATION_PATH.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."user_";
		$real_path = $real_path.$this->getID().DIRECTORY_SEPARATOR."avatar".DIRECTORY_SEPARATOR."large_".$this->getPhoto();
		if(file_exists($real_path) && !isEmptyString($this->getPhoto())){
			return true;
		}
		return false;
	}
	# determine path to small profile picture
	function getSmallPicturePath() {
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$path = "";
		if($this->isMale()){
			$path = $baseUrl.'/uploads/user_0/avatar/default_small_male.jpg';
		}  
		if($this->isFemale()){
			$path = $baseUrl.'/uploads/user_0/avatar/default_small_female.jpg'; 
		}
		if($this->hasProfileImage()){
			$path = $baseUrl.'/uploads/user_'.$this->getID().'/avatar/small_'.$this->getPhoto();
		}
		return $path;
	}
	# determine path to thumbnail profile picture
	function getThumbnailPicturePath() {
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$path = "";
		if($this->isMale()){
			$path = $baseUrl.'/uploads/user_0/avatar/default_thumbnail_male.jpg';
		}  
		if($this->isFemale()){
			$path = $baseUrl.'/uploads/user_0/avatar/default_thumbnail_female.jpg'; 
		}
		if($this->hasProfileImage()){
			$path = $baseUrl.'/uploads/user_'.$this->getID().'/avatar/thumbnail_'.$this->getPhoto();
		}
		return $path;
	}
	# determine path to medium profile picture
	function getMediumPicturePath() {
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$path = "";
		if($this->isMale()){
			$path = $baseUrl.'/uploads/user_0/avatar/default_medium_male.jpg';
		}  
		if($this->isFemale()){
			$path = $baseUrl.'/uploads/user_0/avatar/default_medium_female.jpg'; 
		}
		if($this->hasProfileImage()){
			$path = $baseUrl.'/uploads/user_'.$this->getID().'/avatar/medium_'.$this->getPhoto();
		}
		return $path;
	}
	function getDefaultMediumPicturePath() {
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$path = "";
		if($this->isMale()){
			$path = $baseUrl.'/uploads/user_0/avatar/default_medium_male.jpg';
		}  
		if($this->isFemale()){
			$path = $baseUrl.'/uploads/user_0/avatar/default_medium_female.jpg'; 
		}
		return $path;
	}
	# determine path to large profile picture
	function getLargePicturePath() {
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$path = "";
		if($this->isMale()){
			$path = $baseUrl.'/uploads/user_0/avatar/default_large_male.jpg';
		}  
		if($this->isFemale()){
			$path = $baseUrl.'/uploads/user_0/avatar/default_large_female.jpg'; 
		}
		if($this->hasProfileImage()){
			$path = $baseUrl.'/uploads/user_'.$this->getID().'/avatar/large_'.$this->getPhoto();
		}
		// debugMessage($path);
		return $path;
	}
	
	# determine all the uninvited people added by person
	function getUnInvitedPeople(){
		$q = Doctrine_Query::create()->from('Person p')->where("p.addedbyid = '".$this->getID()."' AND p.lifestatus = '1' AND p.isinvited <> '1' ");
		return $q->execute();
	}
	# determine tree using focus
	function getTreeRelationship($focusid) {
		// debugMessage($relativeid);
		$q = Doctrine_Query::create()->from('FamilyTree ft')->where("ft.focusid = '".$focusid."' AND ft.relativeid = '".$this->getID()."' ");
		$result = $q->fetchOne();
		// debugMessage($result->toArray());
		if(!$result){
			$tree = new FamilyTree();
			return $tree;
		}
		return $result;
	}
	# return collection of person family tree record in family tree model
	function getFocusTreeLine() {
		$session = SessionWrapper::getInstance();
    	$personid = $session->getVar('personid');
		$q = Doctrine_Query::create()->from('FamilyTree ft')->where("ft.focusid = '".$personid."' AND ft.relativeid = '".$this->getID()."' ");
		$result = $q->fetchOne();
		if(!$result){
			$tree = new FamilyTree();
			return $tree;
		}
		// debugMessage($result->toArray());
		return $result;
	}
	function getTheRelationshipID($focusid) {
		$result = $this->getTreeRelationship($focusid);
		if($result){
			$rel = $result->getRelationShip()->getID();
			return $rel;
		} else {
			return '';
		}
	}
	function getTheRelationship() {
		$session = SessionWrapper::getInstance();
    	$personid = $session->getVar('personid');
    	
		$result = $this->getTreeRelationship($personid);
		if($result){
			$rel = $result->getRelationShip()->getEName();
			return isEmptyString($rel) ? 'Relative' : $rel;
		} else {
			return '';
		}
	}
	function getRelationLine($focusid){
		$tree = new MatrixPath();
		$treeline = $this->getTreeRelationship($focusid);
		if($treeline){
			$tree = $treeline;
		}
		return $tree;
	}
	function getRelationShipText($focusid){
		$text = 'Relative';
		$treeline = $this->getTreeRelationship($focusid);
		if($treeline){
			if(!isEmptyString($treeline->getRelationShipID())){
				if(!isEmptyString($treeline->getRelationShip()->getLName())){
					$text = $treeline->getRelationShip()->getEname()."<br /> (".$treeline->getRelationShip()->getLName().")";
				} else {
					$text = $treeline->getRelationShip()->getEname();
				}
			}
		}
		return $text;
	}
	function getRelationShipLabel($focusid){
		$text = 'Relative';
		$treeline = $this->getTreeRelationship($focusid);
		if($treeline){
			if(!isEmptyString($treeline->getRelationShipID())){
				if(!isEmptyString($treeline->getRelationShip()->getLName())){
					$text = $treeline->getRelationShip()->getEname()." (".$treeline->getRelationShip()->getLName().")";
				} else {
					$text = $treeline->getRelationShip()->getEname();
				}
			}
		}
		return $text;
	}
	function getRelationShipShortName($focusid){
		$text = 'Relative';
		$treeline = $this->getTreeRelationship($focusid);
		if($treeline){
			if(!isEmptyString($treeline->getRelationShipID())){
				$text = $treeline->getRelationShip()->getEname();	
			}
		}
		return $text;
	}
	# determine invalid families for a person
    function getInvalidFamiliesForPerson() {
    	$fams = $this->isMale() ? $this->getFFamilies() : $this->getMFamilies();
    	// debugMessage($fams->toArray());
    	$invalid_collection = new Doctrine_Collection("Family");
    	foreach ($fams as $family){
    		if(!$family->isValidFamily()){
    			$invalid_collection->add($family);
    		}
    	}
    	// debugMessage($invalid_collection->toArray());
		return $invalid_collection;
    }
    # determine if person is deletable
    function isdeletable(){
    	$allow = false;
    	// families
    	$families = $this->isMale() ? $this->getFFamilies() : $this->getMFamilies();
		
    	// no families, allow delete
    	if($families->count() == 0){
    		$allow = true;
    	}
    	// debugMessage($families->toArray());
    	if($families->count() > 0){
			$allkids = $this->getAllChildren();
			if($allkids){
				if($allkids->count() == 0){
					$allow = true;
				} else{
					foreach ($families as $family){
						if(($family->getFatherID() == $this->getID() && !isEmptyString($family->getMotherID())) || 
							($family->getMotherID() == $this->getID() && !isEmptyString($family->getFatherID()))
						){
							$allow = true;
						}
					}
				}
			}
    	}
    	return $allow;
    }
    # delete person and clean up partner families
    function deletePerson(){
    	$personsaffected = $this->getDeletablePersons($this->getID());
    	// debugMessage($personsaffected);
    	
    	$count_affected = count($personsaffected);
    	$familiesaffected = array();
    	if($count_affected > 0){
    		// debugMessage($personsaffected->toArray());
    		foreach ($personsaffected as $key => $value){
    			$person = new Person();
    			$person->populate($value);
    			if(!isEmptyString($person->getFamilyID())){
    				$familiesaffected[] = $person->getFamilyID();
    			}
    			$person->delete();
    		}
    	}
    	// $personsaffected->delete();
    	
    	// check integrity of related families and delete them too
    	if(count($familiesaffected)){
    		foreach ($familiesaffected as $famid){
	    		$family = new Family();
	    		$family->populate($famid);
	    		if($family->isDeletable()){
	    			$family->delete();
	    		}
    		}
    	}
    	$cleanupfamily = $this->getFamilyID(); // family person was born in
    	# delete person
    	$this->delete();
    	
    	# cleanup previous family belonged to
    	if(!isEmptyString($cleanupfamily)){
    		$family = new Family();
    		$family->populate($cleanupfamily);
    		if($family->isDeletable()){
    			$family->delete();
    		}
    	}
    	
    	# clean up partner families if any
    	$families = $this->isMale() ? $this->getFFamilies() : $this->getMFamilies(); // partner families for person
    	if($families->count() > 0){
	    	foreach ($families as $fam){
	    		if($fam->isDeletable()){
	    			$fam->delete();
	    		}
	    	}
    	}
    	
    	return true;
    }
    # recursively determine all the pple added via a person as focus
	function getDeletablePersons($focusid, $list = array()) {
		$result = $this->fetchPeopleAdded($focusid);
		if($result->count() > 0){
			foreach($result as $tree){
				if(!isEmptyString($tree->getRelativeID())){
					if(!in_array($tree->getRelativeID(), $list)){
						$list[] = $tree->getRelativeID();
					}
					if($tree->getRelative()->hasPeopleAdded()){
						$list = $this->getDeletablePersons($tree->getRelativeID(), $list);
					} else {
						continue;
					}
				}
			}
		}
		// debugMessage($list);
		return $list;
	}
	# determine list of people added by person
	function fetchPeopleAdded($focusid){
    	$q = Doctrine_Query::create()->from('FamilyTree ft')->where("ft.addedas = '".$focusid."' ");
		$result = $q->execute();
		return $result;
    }
    # determine if there are any people 
    function hasPeopleAdded(){
    	$people = $this->fetchPeopleAdded($this->getID());
    	return $people->count() > 0 ? true : false;
    }
	# get formatted list of all persons to be deleted
	function getFormattedListOfDeleteables() {
		$personsaffected = $this->getDeletablePersons($this->getID()); 
		// debugMessage($personsaffected->toArray());
		$count_affected = count($personsaffected);
    	$peoplearray = array();
    	if($count_affected > 0){
    		// debugMessage($personsaffected->toArray());
    		foreach ($personsaffected as $key => $value){
    			$person = new Person();
    			$person->populate($value);
    			$peoplearray[$person->getID()] = $person->getName();
    		}
    	}
    	return createHTMLListFromArray($peoplearray);
	} 
    # determine the current privacy value
    function getCurrentPrivacyLevel($section, $securitylevel) {
    	$value = '';
    	switch($section) {
    		case 'namesection':
    			$policy = $this->getPrivacyLine()->getnamesection();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyLetter($policy);
    			break;
    		case 'familysection':
    			$policy =  $this->getPrivacyLine()->getfamilysection();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyLetter($policy);
    			break;
    		case 'clansection':
    			$policy = $this->getPrivacyLine()->getclansection();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyLetter($policy);
    			break;
    		case 'personalsection':
    			$policy = $this->getPrivacyLine()->getpersonalsection();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyLetter($policy);
    			break;
    		case 'emailaddresssection':
    			$policy = $this->getPrivacyLine()->getemailaddresssection();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyLetter($policy);
    			break;
    		case 'phonesection':
    			$policy = $this->getPrivacyLine()->getphonesection();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyLetter($policy);
    			break;
    		case 'physicaladdresssection':
    			$policy = $this->getPrivacyLine()->getphysicaladdresssection();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyLetter($policy);
    			break;
    		case 'webaddresssection':
    			$policy = $this->getPrivacyLine()->getwebaddresssection();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyLetter($policy);
    			break;
    		case 'birthsection':
    			$policy = $this->getPrivacyLine()->getbirthsection();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyLetter($policy);
    			break;
    		case 'profilepicture':
    			$policy = $this->getPrivacyLine()->getprofilepicture();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyLetter($policy);
    			break;
    		default:
    			break;
    	}
    	
    	return $value;
    }
	# determine the current privacy description
    function getCurrentPrivacyDetail($section, $securitylevel) {
    	$value = '';
    	switch($section) {
    		case 'namesection':
    			$policy = $this->getPrivacyLine()->getnamesection();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyText($policy);
    			break;
    		case 'familysection':
    			$policy =  $this->getPrivacyLine()->getfamilysection();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyText($policy);
    			break;
    		case 'clansection':
    			$policy = $this->getPrivacyLine()->getclansection();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyText($policy);
    			break;
    		case 'personalsection':
    			$policy = $this->getPrivacyLine()->getpersonalsection();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyText($policy);
    			break;
    		case 'emailaddresssection':
    			$policy = $this->getPrivacyLine()->getemailaddresssection();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyText($policy);
    			break;
    		case 'phonesection':
    			$policy = $this->getPrivacyLine()->getphonesection();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyText($policy);
    			break;
    		case 'physicaladdresssection':
    			$policy = $this->getPrivacyLine()->getphysicaladdresssection();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyText($policy);
    			break;
    		case 'webaddresssection':
    			$policy = $this->getPrivacyLine()->getwebaddresssection();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyText($policy);
    			break;
    		case 'birthsection':
    			$policy = $this->getPrivacyLine()->getbirthsection();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyText($policy);
    			break;
    		case 'profilepicture':
    			$policy = $this->getPrivacyLine()->getprofilepicture();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyText($policy);
    			break;
    		default:
    			$policy = $this->getPrivacyLine()->getdefaultprivacy();
    			$value = $this->getPrivacyLine()->getCurrentPrivacyText($policy);
    			break;
    	}
    	
    	return $value;
    }
    # determine if person has privacy 
    function cleanUpPrivacy(){
    	if(isEmptyString($this->getPrivacyLine()->getID())){
	    	$privacy = new Privacy();
	    	$privacy->setPersonID($this->getID());
	    	if(!isEmptyString($this->getOwnerID())) {
	    		$privacy->setUserID($this->getOwnerID());
	    	}
	    	// debugMessage($privacy->toArray());
	    	try {
	    		$privacy->save();
	    	} catch (Exception $e) {
	    		debugMessage($e->getMessage()); 
	    	}
	    	
    	} 
    	return true;
    }
    # determine if application for beta is a duplicate
    function hasAlreadyApplied() {
    	$conn = Doctrine_Manager::connection();
    	
    	// unique email
		$email_query = "SELECT email FROM person WHERE email = '".$this->getEmail()."' AND statusflag = 2 ";
		$email_result = $conn->fetchOne($email_query);
		if(!isEmptyString($email_result)){
			return true;
		}
    	return false;
    }
	# Send notification to invite a friend
	function unsubscribeNotification($dataarray) {
		$template = new EmailTemplate(); 
		# create mail object
		$mail = getMailInstance(); 
		$default_sender = $mail->getDefaultFrom(); 
		// assign values
		$template->assign('name', $dataarray['name']);
		$template->assign('intromsg', $dataarray['intromsg']);
		
		// configure base stuff
		$mail->addTo($dataarray['youremail']);
		//$mail->addCc($default_sender['email']); // send email to the admin as well 
		// $mail->addCc('nbnakazzi@gandaancestry.com'); // send email to the admin as well 
		// set the sender of the email address
		//$mail->setFrom($this->config->notification->emailmessagesender, $this->translate->_('useraccount_email_notificationsender'));
		$mail->setFrom('nbnakazzi@gandaancestry.com', 'GandaAncestry');
		$mail->setSubject("Unsubscribe From GandaAncestry");
		// render the view as the body of the email
		$mail->setBodyHtml($template->render('unsubscribenotification.phtml'));
		//debugMessage($template->render('unsubscribenotification.phtml')); exit();
		$mail->send();
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		$mail->clearFrom();
		
		return true;
	}
	# determine people person has invited
	function getInvited(){
		$all_results_query = "SELECT p.* FROM person p where (p.addedbyid = '".$this->getID()."' AND p.invitedbyid = '".$this->getID()."') ";   
    	
    	$conn = Doctrine_Manager::connection(); 
		$result = $conn->fetchAll($all_results_query);
		return $result;
	}
	# determine number of people invited
	function getInvitedCount(){
		$relatives = $this->getInvited();   
		return count($relatives);
	}
	# determine all the relatives featured on person's profile
  	function getFeaturedProfileRelatives($limit) {
  		$q = Doctrine_Query::create()->from('Person p')->where("p.addedbyid = '".$this->getID()."' AND p.photo <> '' ")->limit($limit)->orderBy('rand()');
		$result = $q->execute();
		$count_result = $result->count();
		if($count_result == $limit){
			return $result;
		} 
		$q = Doctrine_Query::create()->from('Person p')->where("p.addedbyid = '".$this->getID()."' ")->limit($limit)->orderBy('rand()');
		$result = $q->execute();
		return $result;
  	}
	# function determine number of relatives for person
	function getFeaturedRelativeCount($limit){
		$relatives = $this->getFeaturedProfileRelatives($limit);   
		return $relatives->count();
	}
	# determine featured deceased relatives for a person
  	function getFeaturedDeceased() {
  		$q = Doctrine_Query::create()->from('Person p')->where("p.addedbyid = '".$this->getID()."' AND p.lifestatus = 2 AND p.photo <> '' ")->limit(4)->orderby('rand()');
		$result = $q->execute();
		$count_result = $result->count();
		if($count_result == 4){
			return $result;
		} 
		$q = Doctrine_Query::create()->from('Person p')->where("p.addedbyid = '".$this->getID()."' AND p.lifestatus = 2")->limit(4)->orderby('rand()');
		$result = $q->execute();
		return $result;
  	}
	# determine all the relatives added by a person
	function getRelatives(){
		$session = SessionWrapper::getInstance();
    	$personid = $session->getVar('personid');
		$all_results_query = "SELECT p.* FROM person p inner join familytree f on (f.relativeid = p.id) where (f.focusid = '".$this->getID()."') GROUP BY f.relativeid ";   
    	
    	$conn = Doctrine_Manager::connection(); 
		$result = $conn->fetchAll($all_results_query);
		return $result;
	}
	# determine all subscriber relatives
	function getSubscriberRelatives($ignorearray = array()){
		$custom_where = "";
		if(count($ignorearray) > 0){
			$ignore_list = implode("','", $ignorearray);
			$custom_where .= " AND f.relativeid NOT IN('".$ignore_list."') ";
			
		}
		$all_results_query = "SELECT p.* FROM person p inner join familytree f on (f.relativeid = p.id) where (f.focusid = '".$this->getID()."' ".$custom_where.") GROUP BY f.relativeid ";   
    	$conn = Doctrine_Manager::connection(); 
    	// debugMessage($all_results_query);
		$result = $conn->fetchAll($all_results_query);
		return $result;
	}
	# determine all relatives using other person as focus
	function getRelativesForPerson($ownerid, $personid, $ignorearray){
		$custom_where = "";
		if(count($ignorearray) > 0){
			$ignore_list = implode("','", $ignorearray);
			$custom_where .= " AND f.relativeid NOT IN('".$ignore_list."') ";
			
		}
		$all_results_query = "SELECT p.* FROM person p inner join familytree f on (f.treeid = p.id) where (f.treeid = '".$ownerid."' AND f.focusid = '".$personid."' ".$custom_where.") ";
		// debugMessage($all_results_query);
    	$conn = Doctrine_Manager::connection(); 
		$result = $conn->fetchAll($all_results_query);
		return $result;
	}
	# all relatives using the addedby field
	function getRelativesAdded(){
		$q = Doctrine_Query::create()->from('Person p')->where("p.addedbyid = '".$this->getID()."' AND p.id <> '".$this->getID()."' ");
		$result = $q->execute();
		return $result;
	}
	# all relatives using the addedby field
	function getFamiliesAdded(){
		$q = Doctrine_Query::create()->from('Family f')->where("f.addedbyid = '".$this->getID()."' AND f.id <> '".$this->getFamilyID()."' ");
		$result = $q->execute();
		return $result;
	}
	# all relatives for person
	function getAllFamilies(){
		$q = Doctrine_Query::create()->from('Family f')->where("f.addedbyid = '".$this->getID()."' OR f.createdby = '".$this->getUserID()."' ");
		$result = $q->execute();
		return $result;
	}
	# function determine number of relatives for person
	function getRelativeCount(){
		$relatives = $this->getRelatives();   
		return count($relatives);
	}
	# determine all the relatives who are clannmates to a person
	function getClanRelatives(){
		$all_results_query = "SELECT p.id FROM person p where (p.addedbyid = '".$this->getID()."' AND p.clanid = '".$this->getClanID()."') OR p.id = '".$this->getID()."' ";   
    	
    	$conn = Doctrine_Manager::connection(); 
		$result = $conn->fetchAll($all_results_query);
		return $result;
	}
	# feature some of the relatives who are clannmates to a person
	function getFeaturedClanRelatives($limit){
		$q = Doctrine_Query::create()->from('Person p')->where("p.addedbyid = '".$this->getID()."' AND p.clanid = '".$this->getClanID()."' AND p.photo <> '' ")->limit($limit)->orderby('rand()');
		$result = $q->execute();
		$count_result = $result->count();
		if($count_result == $limit){
			return $result;
		} 
		$q = Doctrine_Query::create()->from('Person p')->where("p.addedbyid = '".$this->getID()."' AND p.clanid = '".$this->getClanID()."'")->limit($limit)->orderby('rand()');
		$result = $q->execute();
		return $result;
	}
	# function determine number of clan relatives for person
	function getClanRelativeCount(){
		$relatives = $this->getClanRelatives();   
		return count($relatives);
	}
	# determine all ancestors of person
	function getAncestors(){
		$all_results_query = "SELECT p.* FROM person p inner join familytree f on (f.treeid = p.id) where (f.treeid = '".$this->getID()."' AND f.level < 10) ";   
    	
    	$conn = Doctrine_Manager::connection(); 
		$result = $conn->fetchAll($all_results_query);
		return $result;
	}
	# function determine number of ancestors
	function getAncestorCount(){
		$relatives = $this->getAncestors();   
		return count($relatives);
	}
	# determine all Decendants of person
	function getDecendants(){
		$all_results_query = "SELECT p.* FROM person p inner join familytree f on (f.treeid = p.id) where (f.treeid = '".$this->getID()."' AND f.level > 10) ";   
    	
    	$conn = Doctrine_Manager::connection(); 
		$result = $conn->fetchAll($all_results_query);
		return $result;
	}
	# function determine number of ancestors
	function getDecendantCount(){
		$relatives = $this->getDecendants();   
		return count($relatives);
	}
	# determine all the relative to be featured under upcoming birthdays
	function getRelativesOrderByDOB(){
		$session = SessionWrapper::getInstance();
    	$personid = $session->getVar('personid');
    	$userid = $session->getVar('userid');
    	
		$all_results_query = "SELECT p.* FROM person p inner join event e on (e.personid = p.id) where (p.addedbyid = '".$this->getID()."' OR p.ownerid = '".$userid."') AND e.startday is not null AND e.startmonth is not null AND p.addedbyid <> ".$this->getID()." ";   
    	// debugMessage($all_results_query);
    	$conn = Doctrine_Manager::connection(); 
		$result = $conn->fetchAll($all_results_query);
		return $result;
	}
	# find relatives that could have been saved as a result of duplicates
	function getDuplicatePersons(){
		$q = Doctrine_Query::create()->from('Person p')->where("p.firstname = '".$this->getFirstName()."' AND p.lastname = '".$this->getLastName()."' AND p.clanname = '".$this->getClanName()."' AND p.addedbyid = '".$this->getAddedByID()."' AND p.id <> '".$this->getID()."' ");
		$result = $q->execute();
		return $result;
	}
	function getDuplicatePersonsCustom($id){
		$p = new Person();
		$p->populate($id);
		
		$q = Doctrine_Query::create()->from('Person p')->where("p.firstname = '".$p->getFirstName()."' AND p.lastname = '".$p->getLastName()."' AND p.clanname = '".$p->getClanName()."' AND p.addedbyid = '".$p->getAddedByID()."' AND p.id <> '".$p->getID()."' ");
		$result = $q->execute();
		return $result;
	}
	# determine the percentage completion of a profile
	function getCurrentPercentageCompletion(){
		# initial percentage
		$total = 10;
		# total number of relatives
		$noofrelatives = $this->getRelativeCount();
		# total invited 
		$noofinvited = $this->getInvitedCount();
		# dateofbirth event
		$dobevent = $this->getBirthDetails();
		# emails
		$count_emails = $this->getEmailContacts()->count();
		# phones
		$count_phones = $this->getPhoneContacts()->count();
		# physical address
		$count_addresses = $this->getPhysicalAddresses()->count();
		# web address
		$count_services = $this->getWebContacts()->count();			
		
		
		if($this->hasFather()){
			$total += 10;		
		}
		if($this->hasMother()){
			$total += 10;
		}
		if(!isEmptyString($this->getPhoto())){
			$total += 10;
		}
		if(!isEmptyString($this->getScreenName())){
			$total += 5;
		}
		if(!isEmptyString($this->getBio())){
			$total += 5;
		}
		if(!isEmptyString($this->getSsigaID())){
			$total += 5;
		}
		if(!isEmptyString($this->getDateOfBirth())){
			$total += 5;
		}
		if($this->hasButakaAddress()){
			$total += 5;
		}
		if($count_emails > 0){
			$total += 5;
		}
		if($count_phones > 0){
			$total += 5;
		}
		if($count_addresses > 0){
			$total += 5;
		}
		if($count_services > 0){
			$total += 5;
		}
		if($noofrelatives >= 5){
			$total += 10;
		}
		if($noofinvited > 1){
			$total += 10;
		}
		return $total;
	}
	# add test data to model
	function testPreProcess($formvalues) {
		if(!isArrayKeyAnEmptyString('focusid', $formvalues)){
			$this->setFocusID($formvalues['focusid']);
		}
		if(!isArrayKeyAnEmptyString('relationshiptype', $formvalues)){	
			$this->setRelationshipType($formvalues['relationshiptype']);
		}	
		if(!isArrayKeyAnEmptyString('childfamilyid', $formvalues)){	
			$this->setChildFamilyID($formvalues['childfamilyid']);
		}
		if(!isArrayKeyAnEmptyString('partnerstatus', $formvalues)){	
			$this->setPartnerStatus($formvalues['partnerstatus']);
		}
		if(!isArrayKeyAnEmptyString('focusfamilyid', $formvalues)){	
			$this->setFocusFamilyID($formvalues['focusfamilyid']);
		}
		if(!isArrayKeyAnEmptyString('focusfatherid', $formvalues)){	
			$this->setFocusFatherID($formvalues['focusfatherid']);
		}
		if(!isArrayKeyAnEmptyString('focusmotherid', $formvalues)){	
			$this->setFocusMotherID($formvalues['focusmotherid']);
		}
		if(!isArrayKeyAnEmptyString('focustreeid', $formvalues)){	
			$this->setFocusTreeID($formvalues['focustreeid']);
		}
		if(!isArrayKeyAnEmptyString('siblingtype', $formvalues)){	
			$this->setSiblingType($formvalues['siblingtype']);
		}
		if(!isArrayKeyAnEmptyString('pfirstname', $formvalues)){	
			$this->setPFirstName($formvalues['pfirstname']);
		}
		if(!isArrayKeyAnEmptyString('plastname', $formvalues)){	
			$this->setPLastName($formvalues['plastname']);
		}
		if(!isArrayKeyAnEmptyString('pclanname', $formvalues)){	
			$this->setPClanName($formvalues['pclanname']);
		}
		if(!isArrayKeyAnEmptyString('pclanid', $formvalues)){	
			$this->setPClanID($formvalues['pclanid']);
		}
		if(!isArrayKeyAnEmptyString('ptype', $formvalues)){	
			$this->setPType($formvalues['ptype']);
		}
		if(!isArrayKeyAnEmptyString('pfathertype', $formvalues)){	
			$this->setPFatherType($formvalues['pfathertype']);
		}
		if(!isArrayKeyAnEmptyString('pmothertype', $formvalues)){	
			$this->setPMotherType($formvalues['pmothertype']);
		}
		if(!isArrayKeyAnEmptyString('cfathertype', $formvalues)){	
			$this->setCFatherType($formvalues['cfathertype']);
		}
		if(!isArrayKeyAnEmptyString('cmothertype', $formvalues)){	
			$this->setCMotherType($formvalues['cmothertype']);
		}	
		if(!isArrayKeyAnEmptyString('fsiblingfamilyid', $formvalues)){	
			$this->setFSiblingFamilyID($formvalues['fsiblingfamilyid']);
		}
		if(!isArrayKeyAnEmptyString('msiblingfamilyid', $formvalues)){	
			$this->setMSiblingFamilyID($formvalues['msiblingfamilyid']);
		}
		if(!isArrayKeyAnEmptyString('ffamilyid', $formvalues)){	
			$this->setFFamilyID($formvalues['ffamilyid']);
		}
		if(!isArrayKeyAnEmptyString('mfamilyid', $formvalues)){	
			$this->setMFamilyID($formvalues['mfamilyid']);
		}
		if(!isArrayKeyAnEmptyString('directrelationship', $formvalues)){	
			$this->setDirectRelationShip($formvalues['directrelationship']);
		}
	}
	# feature some of the relatives who are clannmates to a person
	function determineBetaUsers($data){
		$customquery = "";
		$list = implode("','", $data);
		debugMessage($list);
		if(is_array($data)){
			$customquery = "AND p.email IN('".$list."')";
		}
		debugMessage($customquery);
		$q = Doctrine_Query::create()->from('Person p')->where("p.statusflag = '2' ".$customquery." ")->limit(1)->orderby('rand()');
		$result = $q->execute();
		return $result;
	}
	# feature some of the relatives who are clannmates to a person
	function findByEmail($email){
		$q = Doctrine_Query::create()->from('Person p')->where("p.email = '".$email."' ");
		$result = $q->fetchOne();
		return $result;
	}
	# determine upcoming birthdays
	function getUpcomingBirthdays($month){
		
    	$relatives = $this->getRelativesAdded();
    	$birthdata = array();
    	$upcomingdata = array();
    	$id = 1;
    	
    	$currentstamp = strtotime(date("Y-m-d 00:00:00", strtotime("now")));
    	$today = date('Y-m-d');
    	$endstamp = strtotime(date("Y-m-d", strtotime($today)) . " + 8 weeks ");
    	
    	if($relatives->count() > 0){
    		// debugMessage($currentstamp);
	    	foreach($relatives as $relative){
	    		if(!isEmptyString($relative->getPersonBirth())){
		    		$birthdata[$id]['id'] = $relative->getID();
		    		$birthdata[$id]['name'] = $relative->getName();
		    		$birthdata[$id]['birth'] = $relative->getPersonBirth();
		    		
		    		$test[$id] = explode('-', $relative->getPersonBirth());
		    		$test[$id][0] = date('Y');
		    		$birthdata[$id]['monthnumber'] = $test[$id][1];
		    		$yeardate[$id] = implode('-', $test[$id]);
		    		$birthdata[$id]['currentbirth'] = $yeardate[$id];
		    		
		    		$birthdata[$id]['cur_stamp'] = $currentstamp;
		    		$birthdata[$id]['cur_birth'] = convertMySqlDateToUnixTimestamp($yeardate[$id]);
		    		$birthdata[$id]['cur_diff'] = convertMySqlDateToUnixTimestamp($yeardate[$id]) - $currentstamp;
		    		if($birthdata[$id]['cur_birth'] >= $currentstamp && $birthdata[$id]['cur_birth'] <= $endstamp && $birthdata[$id]['monthnumber'] == $month){
		    			$upcomingdata[$id] = $birthdata[$id];
		    		}
	    		}
	    		$id++;
	    	}
	    	// debugMessage($birthdata);
	    	if(count($upcomingdata) > 0){
	    		$upcomingdata = sort_multi_array($upcomingdata, 'cur_diff', 'ASC');
	    	}
    	}
    	return $upcomingdata;
	}
}
?>