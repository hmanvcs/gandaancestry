<?php

class TestController extends IndexController  {
	
	function personAction() {
		$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$person = new Person();
		$person->populate(455);
		
		$test = $person->getImmediateFamilyMembers();
		debugMessage($test);
	}
	function signupAction(){
    	$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
    	$user = new UserAccount();
    	
    	$deluser = $user->findEntryByField('test@devmail.infomacorp.com');
    	if($deluser){
    		// debugMessage($deluser->toArray());
    		$deluser->getPerson()->getFamily()->delete();
	    	$deluser->delete();
	    	$deluser->autoReset();
    	}
    	exit();
    	$data = array(
    		"action" => "create",
			"module" => "default",
			"isactive" => "0",
			"type" => "2",
			"entityname" => "UserAccount",
			"failureurl" => "L2dhbmRhYW5jZXN0cnkvcHVibGljL3NpZ251cA==",
			"successurl" => "L2dhbmRhYW5jZXN0cnkvcHVibGljL3NpZ251cC9jb25maXJt",
			"person" => array (
				"firstname" => "George",
				"lastname" => "Kazibwe",
				"clanname" => "Sekassi",
				"type" => "1",
				"gender" => "1",
				"createdby" => "1",
				"addresses" => array("0" => array("country" => "UG", "createdby" => "1")),
				"events" => array("0" => array("eventtype" => "1","startday" => "12","startmonth" => "6","startyear" => "1982","starttype" => "1","createdby" => "1")),
				"clanid" => "27",
				"family" => array(
                    "father" => array(
                            "type" => "1",
                            "firstname" => "Paul",
                            "lastname" => "Nsagi",
                            "clanname" => "Kaliika",
                            "clanid" => "27",
                            "gender" => "1",
                            "lifestatus" => "1",
                            "dateofbirth" => "1941-11-18",
                            "events" => array(
                                    "0" => array("eventtype" => "1","startday" => "18","startmonth" => "11","startyear" => "1941","starttype" => "1","createdby" => "1")
							),
                            "createdby" => "1"
                        ),
                  	"createdby" => "1"
                )
        	),
    		"createdby" => "1",
    		"usergroups_groupid" => array("0" => "2"),
		    "gender" => "1",
		    "firstname" => "George",
		    "lastname" => "Kazibwe",
		    "clanname" => "Sekassi",
		    "email" => "test@devmail.infomacorp.com",
		    "password" => "password",
		    "confirmpassword" => "password",
		    "country" => "UG",
		    "birthday" => "12",
		    "birthmonth" => "6",
		    "birthyear" => "1982",
		    "dateofbirth" => "",
		    "agreedtoterms" => "1",
		    "code" => "dnefh",
		    "validcode" => "dnefh",
		    "ffirstname" => "Paul",
		    "flastname" => "Nsagi",
		    "fclanid" => "27",
		    "fclanname" => "Kaliika",
		    "fbirthday" => "18",
		    "fbirthmonth" => "11",
		    "fbirthyear" => "1941",
		    "fdateofbirth" => "",
		    "flifestatus" => "1",
		    "usecustomsave" => "true",
		    "personid" => "",
		    "fatherid" => "",
		    "motherid" => "",
		    "familyid" => "",
		    "membershipplanid" => "1",
		    "birtheventid" => "",
		    "fbirtheventid" => "",
		    "mbirtheventid" => "",
		    "addressid" => ""
    	);
    	
    	// debugMessage($data);
    	
    	$user->processPost($data);
    	//debugMessage('after');
    	//debugMessage('errors are '.$user->getErrorStackAsString());
    	// debugMessage($user->toArray());
    	
    	if($user->isValid()){
    		if($user->transactionSave()){
    			$user->testActivate();
    			// debugMessage($user->toArray());
    		}
    	}
    	
    	$treeline = $user->getPerson()->getTreeRelationship($user->getPersonID());
    	$treeid = "";
    	if($treeline){
    		$treeid = $treeline->getID();
    	}
    	
    	# save mother
    	$userid = $user->getID();
    	$personid = $user->getPersonID();
    	$mum = array (
		    "relationshiptype" => "2",
		    "firstname" => "Benny",
		    "lastname" => "Himmm",
		    "type" => "1",
		    "clanname" => "Nalule",
		    "clanid" => "7",
		    "theclanid" => "",
		    "gender" => "2",
		    "lifestatus" => "1",
		    "entityname" => "Person",
		    "id" => "",
    		"createdby" => $userid,
		    "ownerid" => $userid,
		    "focusid" => $personid,
		    "addedbyid" => $personid,
		    "focusfamilyid" => $user->getPerson()->getFamilyID(),
		    "focusfatherid" => $user->getPerson()->getFatherID(),
		    "focusmotherid" => $user->getPerson()->getMotherID(),
		    "focustreeid" => $treeid,
		    "email" => "",
		    "cfathertype" => "2",
		    "pfathertype" => "2",
		    "pmothertype" => "2",
		    "pfirstname" => "",
		    "plastname" => "",
		    "ptype" => "1",
		    "pclanname" => "",
		    "pclanid" => "",
		    "thepclanid" => ""
		);
    	
		// debugMessage($mum);
		$mperson = new Person();
		$mperson->testPreProcess($mum);
		$mperson->processPost($mum);
		//debugMessage($mperson->toArray());
		//debugMessage('errors - '.$mperson->getErrorStackAsString());
		if($mperson->isValid()){
			$mperson->save();
    		$mperson->afterSave();
    	}
    	
		# daughter
    	$dat = array(
		    "relationshiptype" => "6",
    		"siblingtype" => "1",
		    "firstname" => "Nuriat",
		    "lastname" => "Kabex",
		    "type" => "1",
		    "clanname" => "Tarema",
		    "clanid" => "27",
		    "theclanid" => "",
		    "gender" => "2",
		    "lifestatus" => "1",
		    "entityname" => "Person",
		    "id" => "",
    		"createdby" => $userid,
		    "ownerid" => $userid,
		    "focusid" => $personid,
		    "addedbyid" => $personid,
		    "focusfamilyid" => $user->getPerson()->getFamilyID(),
		    "focusfatherid" => $user->getPerson()->getFatherID(),
		    "focusmotherid" => $user->getPerson()->getMotherID(),
		    "focustreeid" => $treeid,
		    "email" => "",
		    "cfathertype" => "1",
    		"childfamilyid" => $user->getPerson()->getFamilyID()+1,
		    "pfathertype" => "2",
		    "pmothertype" => "2",
		    "pfirstname" => "",
		    "plastname" => "",
		    "ptype" => "1",
		    "pclanname" => "",
		    "pclanid" => "",
		    "thepclanid" => ""
		);
		
		// debugMessage($dat);
		$dperson = new Person();
		$dperson->testPreProcess($dat);
		$dperson->processPost($dat);
		debugMessage($dperson->toArray());
		debugMessage('errors - '.$dperson->getErrorStackAsString());
		if($dperson->isValid()){
			$dperson->save();
    		$dperson->afterSave();
    	}
    	
    	exit();
    	# wife
    	$wife = array(
		    "relationshiptype" => "7",
		    "firstname" => "Carol",
		    "lastname" => "Kazibwe",
		    "type" => "1",
		    "clanname" => "Nabuguzi",
		    "clanid" => "35",
		    "theclanid" => "",
		    "gender" => "2",
		    "lifestatus" => "1",
		    "entityname" => "Person",
		    "id" => "",
    		"createdby" => $userid,
		    "ownerid" => $userid,
		    "focusid" => $personid,
		    "addedbyid" => $personid,
		    "focusfamilyid" => $user->getPerson()->getFamilyID(),
		    "focusfatherid" => $user->getPerson()->getFatherID(),
		    "focusmotherid" => $user->getPerson()->getMotherID(),
		    "focustreeid" => $treeid,
		    "email" => "",
		    "cfathertype" => "2",
		    "pfathertype" => "2",
		    "pmothertype" => "2",
		    "pfirstname" => "",
		    "plastname" => "",
		    "ptype" => "1",
		    "pclanname" => "",
		    "pclanid" => "",
		    "thepclanid" => ""
		);
		
		// debugMessage($mum);
		$wperson = new Person();
		$wperson->testPreProcess($wife);
		$wperson->processPost($wife);
		if($wperson->isValid()){
			$wperson->save();
    		$wperson->afterSave();
    	}
		
    	
    	exit();
    }
    
    function testresetAction(){
    	$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		$formvalues['id'] = 449;
		
    	$person = new Person();
    	$person->populate($formvalues['id']);
    	// debugMessage($person->toArray());
    	$families = $person->getFamiliesAdded();
    	$people = $person->getRelativesAdded();
    	//debugMessage($families->toArray());
    	//debugMessage($people->toArray());
    	$families->delete();
    	$people->delete();
    	$person->getUser()->autoReset();
    	
    }
    
	function testdeactivateAction(){
    	$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		$formvalues['id'] = 449;
		
    	$person = new Person();
    	$person->populate($formvalues['id']);
    	$user = $person->getUser();
    	$dperson = $person;
    	
    	$families = $person->getAllFamilies();
    	$families->delete();
    	$user->delete();
    	
    	// debugMessage($user->toArray());
    	$user->sendDeactivateNotification();
    	// debugMessage($dperson->toArray());
    	$this->clearSession();
        // redirect to the login page 
        $this->_helper->redirector->gotoUrl($this->view->baseUrl('user/login/delete/1'));
    }
    
    function testcleansaveAction(){
    	$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		$formvalues['id'] = 455;
		
    	$person = new Person();
    	$person->populate($formvalues['id']);
    	// debugMessage($person->toArray());
    	
    	/*$duplicates = $person->getDuplicatePersons();
    	if($duplicates->count() > 0){
    		$duplicates->delete();
    	}*/
    	// debugMessage($duplicates->toArray());
    }
    
	function removeAction(){
    	$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		$delete_array = array(339,402,446,451);
		
		$people_array = array();
		foreach ($delete_array as $value){
	    	$person = new Person();
	    	$person->populate($value);
	    	// debugMessage($person->toArray());
	    	// debugMessage($person->getAllGrandChildren()->toArray());
	    	if($value == 339 || $value == 451){
	    		foreach ($person->getAllGrandChildren(array(300,299)) as $pers){
		    		debugMessage($pers->getID().' - '.$pers->getName());
		    		$people_array[] = $pers->getID();
		    		$pers->delete();
		    	}
	    	} else {
		    	foreach ($person->getAllGrandChildren() as $pers){
		    		debugMessage($pers->getID().' - '.$pers->getName());
		    		$pers->delete();
		    		$people_array[] = $pers->getID();
		    	}
	    	}
	    	
	    	debugMessage('and');
	    	debugMessage($person->getID().' - '.$person->getName());
	    	$person->delete();
	    	$people_array[] = $person->getID();
	    	debugMessage($people_array);
		}
		
		$still_exists = array(0=> 338, 1 => 342, 2=> 343, 3=> 363, 4=> 364, 5=> 365, 6=> 366, 7=> 411, 8=> 339, 9=> 406, 10=> 405, 11=> 407, 12=> 408, 13=> 410, 14=> 402, 15=> 445, 16=> 446, 17=> 452, 18=> 451);
    	foreach ($still_exists as $value){
    		// debugMessage($value);
    		$person = new Person();
    		$person->populate($value);
    		if(!isEmptyString($person->getID())){
    			$person->delete();
    		}
    	}
    }
    
	function grandAction(){
    	$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		$formvalues['id'] = 281;
		
    	$person = new Person();
    	$person->populate($formvalues['id']);
    	// debugMessage($person->toArray());
    	debugMessage($person->getAllGrandChildren()->toArray());
    }
    
	function photosAction(){
    	$this->_helper->layout->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(TRUE);
	    
	    $destination_path = APPLICATION_PATH.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."uploads";
	    $path = $destination_path;
	    
		
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path),
                                              RecursiveIteratorIterator::CHILD_FIRST);
	    foreach ($iterator as $fileinfo) {
	    	if(strpos($fileinfo->getFilename(), 'archive') !== false && $fileinfo->isDir()){
	    		// debugMessage($fileinfo->getFilename());
	    		// debugMessage($fileinfo->getPathname());
	    		$files = glob($fileinfo->getPathname().DIRECTORY_SEPARATOR.'*');
				foreach($files as $file){
					debugMessage("Deleting file ".$file);
				 	if(is_file($file)){
				 		unlink($file);
				 	}
				}
	    	}
	    }
	    
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path),
                                              RecursiveIteratorIterator::CHILD_FIRST);
	    foreach ($iterator as $fileinfo) {
	    	if(strpos($fileinfo->getFilename(), 'user_') !== false && $fileinfo->isDir()){
	    		// debugMessage($fileinfo->getFilename());
	    		$resultarray = explode('_',  $fileinfo->getFilename());
		        $resultarray2 = $resultarray[1];
		        
		        $person = new Person();
		        if($resultarray2 != 0){
			        $person->populate($resultarray2);
			        if(isEmptyString($person->getID()) || (!isEmptyString($person->getID()) && isEmptyString($person->getOwnerID()))){
			        	debugMessage($resultarray2." not found");
			        	deleteDir($fileinfo->getPathname());
			        }
		        }
		        
	    		if(!isEmptyString($person->getPhoto())){
		        	$teststring = substr($person->getPhoto(), 0, -4);
		        	// debugMessage($teststring);
		        	$niterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($fileinfo->getPathname()),
                                              RecursiveIteratorIterator::CHILD_FIRST);
    				foreach ($niterator as $afileinfo) {
    					if($afileinfo->isFile()){
    						// debugMessage($afileinfo->getFilename());
    						if(strpos($afileinfo->getFilename(), $teststring) === false){
				        	 	debugMessage($teststring." not found in ".$afileinfo->getPathname()." -> Now deleting");
				        	 	unlink($afileinfo->getPathname());
				        	}
    					}
    					
    				}
		        	 
		        	 
		        }
	    	}
	    }
    }
    
	function pathAction(){
    	$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$allusers = new UserAccount();
		$users = $allusers->findAllRegisteredPersons();
		// debugMessage($users->toArray());
		
		/*$formvalues = $this->_getAllParams();
		$formvalues['id'] = 97;*/
		if($users->count() > 0){
			foreach ($users as $user){
				// $formvalues['id'] = 106;	
				$formvalues['id'] = $user->getPersonID();			
		    	$person = new Person();
		    	$person->populate($formvalues['id']);
		    	// debugMessage($person->toArray());
		    	
		    	$mother = $person->getMother();
		    	// debugMessage($mother->toArray());
		    	$father = $person->getFather();
		    	
		    	$list = $mother->getDeletablePersons($mother->getID());
		    	$list2 = $father->getDeletablePersons($father->getID());
		    	// debugMessage($list);
		    	// debugMessage(count($list));
		    	$list2 = array_diff($list2, $list);
		    	// debugMessage($list2);
		    	// debugMessage(count($list2));
		    	
		    	// debugMessage($mother->getFormattedListOfDeleteables());
		    	foreach ($list as $key => $value) {
		    		$person = new Person();
		    		$person->populate($value);
		    		$tree = $person->getRelationLine($formvalues['id']);
		    		if(!isEmptyString($tree->getID())){
		    			$tree->setPaternity(2);
		    			$tree->save();
		    			if($person->getID() == $mother->getFatherID()){
		    				debugMessage('found - '.$person->getID());
		    				$kids = $person->getAllChildren();
		    				// debugMessage($sons->toArray());
		    				if($kids){
		    					foreach ($kids as $kid){
		    						if($kid->getID() != $mother->getID()){
			    						$skid = $kid->getRelationLine($formvalues['id']);
			    						if(!isEmptyString($skid->getID())){
			    							$skid->setRelationshipID($kid->isMale() ? 50 : 9);
			    							// debugMessage($skid->toArray());
			    							// $skid->save();
			    						}
			    						
			    						$l2kids = $kid->getAllChildren();
			    						if($l2kids){
			    							foreach ($l2kids as $kid2){
			    								$s2kid = $kid2->getRelationLine($formvalues['id']);
			    								// debugMessage($s2kid->toArray());
					    						if(!isEmptyString($s2kid->getID())){
					    							$s2kid->setRelationshipID($kid2->isMale() ? 20 : 21);
					    							// debugMessage($s2kid->toArray());
					    							// $s2kid->save();
					    						}
					    						
					    						$l3kids = $kid2->getAllChildren();
		    									if($l3kids){
		    										foreach ($l3kids as $kid3){
			    										$s3kid = $kid3->getRelationLine($formvalues['id']);
			    										// debugMessage($s3kid->toArray());
		    											if(!isEmptyString($s3kid->getID())){
							    							$s3kid->setRelationshipID($kid3->isMale() ? 56 : 58);
							    							// debugMessage($s3kid->toArray());
							    							// $s3kid->save();
							    						}
							    						
							    						$l4kids = $kid3->getAllChildren();
				    									if($l4kids){
				    										foreach ($l4kids as $kid4){
					    										$s4kid = $kid4->getRelationLine($formvalues['id']);
					    										// debugMessage($s4kid->toArray());
					    										if(!isEmptyString($s4kid->getID())){
									    							$s4kid->setRelationshipID($kid3->isMale() ? 59 : 60);
									    							// debugMessage($s3kid->toArray());
									    							// $s4kid->save();
									    						}
				    										}
				    									}
		    										}
		    									}
			    							}
			    							
			    						}
			    						
		    						}
		    					}
		    				}
		    			}
		    		}
		    	}
		    	
			foreach ($list2 as $key => $value) {
		    		$person = new Person();
		    		$person->populate($value);
		    		$tree = $person->getRelationLine($formvalues['id']);
		    		if(!isEmptyString($tree->getID())){
		    			$tree->setPaternity(1);
		    			// $tree->save();
		    			if($person->getID() == $father->getFatherID()){
		    				debugMessage('found - '.$person->getID());
		    				$kids = $person->getAllChildren();
		    				if($kids){
		    					// debugMessage($kids->toArray());
		    					foreach ($kids as $kid){
		    						if($kid->getID() != $father->getID()){
			    						$skid = $kid->getRelationLine($formvalues['id']);
			    						debugMessage($skid->toArray());
			    						if(!isEmptyString($skid->getID())){
			    							$skid->setRelationshipID($kid->isMale() ? 49 : 10);
			    							debugMessage($skid->toArray());
			    							// $skid->save();
			    						}
			    						
			    						$l2kids = $kid->getAllChildren();
			    						if($l2kids){
			    							foreach ($l2kids as $kid2){
			    								$s2kid = $kid2->getRelationLine($formvalues['id']);
			    								// debugMessage($s2kid->toArray());
					    						if(!isEmptyString($s2kid->getID())){
					    							$s2kid->setRelationshipID($kid2->isMale() ? 22 : 18);
					    							// debugMessage($s2kid->toArray());
					    							//$s2kid->save();
					    						}
					    						
					    						$l3kids = $kid2->getAllChildren();
		    									if($l3kids){
		    										foreach ($l3kids as $kid3){
			    										$s3kid = $kid3->getRelationLine($formvalues['id']);
			    										// debugMessage($s3kid->toArray());
		    											if(!isEmptyString($s3kid->getID())){
							    							$s3kid->setRelationshipID($kid3->isMale() ? 35 : 36);
							    							// debugMessage($s3kid->toArray());
							    							//$s3kid->save();
							    						}
							    						
							    						$l4kids = $kid3->getAllChildren();
				    									if($l4kids){
				    										foreach ($l4kids as $kid4){
					    										$s4kid = $kid4->getRelationLine($formvalues['id']);
					    										// debugMessage($s4kid->toArray());
					    										if(!isEmptyString($s4kid->getID())){
									    							$s4kid->setRelationshipID($kid3->isMale() ? 29 : 30);
									    							// debugMessage($s3kid->toArray());
									    							// $s4kid->save();
									    						}
				    										}
				    									}
		    										}
		    									}
			    							}
			    							
			    						}
			    						
		    						}
		    					}
		    				}
		    			}
		    		}
		    	}
			}
		}
    	
    	debugMessage('completed');
    }
    
    function demoflagAction(){
    	$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		$newflag = 1; 
		$formvalues = $this->_getAllParams();
		debugMessage($formvalues);
	    
		$lookup = new LookupTypeValue();
		$lookup->populate(92);
		debugMessage($lookup->toArray());
		$lookup->setLookupTypeValue($newflag);
		$lookup->save();
		debugMessage($lookup->toArray());
		
    }
}

