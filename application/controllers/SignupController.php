<?php

class SignupController extends IndexController   {	
	
	function processstep1Action() {
		// the group to which the user is to be added
		$formvalues = $this->_getAllParams();
		// debugMessage($this->_getAllParams());
		// exit();		
		$this->_setParam("isactive", 0);
		// $this->_setParam("type", 2); 
		$this->_setParam('entityname', 'UserAccount');
		$this->_setParam(URL_FAILURE, encode($this->view->baseUrl("signup")));
		$this->_setParam(URL_SUCCESS, encode($this->view->baseUrl("signup/confirm")));
		$this->_setParam("action", ACTION_CREATE); 
		
		if(isEmptyString($formvalues['personid'])){
			$formvalues['id'] = NULL;
		} else {
			$formvalues['id'] = $formvalues['personid'];
		}
		
		if(!isEmptyString($formvalues['birthday']) && !isEmptyString($formvalues['birthmonth']) && !isEmptyString($formvalues['birthyear'])){
			$formvalues['dateofbirth'] = $formvalues['birthyear']."-".$formvalues['birthmonth']."-".$formvalues['birthday'];
		} else {
			$formvalues['dateofbirth'] = NULL;
		}
		if(isEmptyString($formvalues['birthday'])){
			$formvalues['birthday'] = NULL;
		}
		if(isEmptyString($formvalues['birthmonth'])){
			$formvalues['birthmonth'] = NULL;
		}
		if(isEmptyString($formvalues['birthmonth'])){
			$formvalues['birthyear'] = NULL;
		}
		
		if($formvalues['type'] == 1){
			if(!isEmptyString($formvalues['fbirthday']) && !isEmptyString($formvalues['fbirthmonth']) && !isEmptyString($formvalues['fbirthyear'])){
				$formvalues['fdateofbirth'] = $formvalues['fbirthyear']."-".$formvalues['fbirthmonth']."-".$formvalues['fbirthday'];
			} else {
				$formvalues['fdateofbirth'] = NUll;
			}
			if(isEmptyString($formvalues['fbirthday'])){
				$formvalues['fbirthday'] = NULL;
			}
			if(isEmptyString($formvalues['fbirthmonth'])){
				$formvalues['fbirthmonth'] = NULL;
			}
			if(isEmptyString($formvalues['fbirthyear'])){
				$formvalues['fbirthyear'] = NULL;
			}
		}
		
		// set parent's gender from person type
		$post = array(
			"createdby" => "1",
			"usergroups_groupid" => array(2),
			"person" => array(
				"firstname" => $formvalues['firstname'], 
				"lastname" => $formvalues['lastname'],
				"clanname" => $formvalues['clanname'],
				"type" => $formvalues['type'],
				"gender" => $formvalues['gender'],
				"dateofbirth" => $formvalues['dateofbirth'],
				"createdby" => "1",
				"addresses" => array(
					array("country" => $formvalues['country'], "createdby" => "1","isprimary"=>1)
				),
				"events" => array(
					array("eventtype" => '1', "startday"=>$formvalues['birthday'], "startmonth"=>$formvalues['birthmonth'], "startyear"=>$formvalues['birthyear'], "starttype"=>"1", "createdby" => "1")
				)
			)
		);
		
		if($formvalues['type'] == '1'){
			// person is a muganda. Father is known
			$family = array(
					"father" => array(
						"type" => $formvalues['type'],
						"firstname" => $formvalues['ffirstname'], 
						"lastname" => $formvalues['flastname'],
						"clanname" => $formvalues['fclanname'],
						"clanid" => $formvalues['fclanid'],
						"gender" => 1,
						"lifestatus" => $formvalues['flifestatus'],
						"dateofbirth" => $formvalues['fdateofbirth'],
						"events" => array(
							array("eventtype" => '1', "startday"=>$formvalues['fbirthday'], "startmonth"=>$formvalues['fbirthmonth'], "startyear"=>$formvalues['fbirthyear'], "starttype"=>"1","createdby" => "1")
						),
						"createdby" => "1"
					),
					"createdby" => "1"
				);
			// person takes same clan as father
			$post['person']['clanid'] = $formvalues['fclanid'];
		}
		
		$post['person']['family'] = $family;
		$this->_setParam('person', $post['person']);		
		$this->_setParam('createdby', $post['createdby']);
		$this->_setParam('usergroups_groupid', $post['usergroups_groupid']);
		
		/*debugMessage($this->_getAllParams());
		exit();*/
		parent::createAction();
	}
	
	function homejoinAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		$session = SessionWrapper::getInstance(); 
		
		// debugMessage($formvalues);
		$session->setVar('joinfromhome', 1);
		$session->setVar('firstname', $formvalues['firstname']);
		$session->setVar('lastname', $formvalues['lastname']);
		$session->setVar('email', $formvalues['email']);
		$session->setVar('password', $formvalues['password']);
		
		$this->_helper->redirector->gotoUrl(decode($this->_getParam(URL_SUCCESS))); 
		// exit();
	}
	
	function processinviteAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		$session = SessionWrapper::getInstance(); 
		$formvalues['id'] = $formvalues['personid'];
		// debugMessage($this->_getAllParams());
		
		$this->_setParam('entityname', 'Person');
		$this->_setParam(URL_FAILURE, encode($this->view->baseUrl('signup/index/profile/'.encode($formvalues['id'])."/")));
		$this->_setParam(URL_SUCCESS, encode($this->view->baseUrl("signup/inviteconfirmation")));
		$this->_setParam("action", ACTION_EDIT); 
		
		$person = new Person(); 
		$person->populate($formvalues['id']);
		
		if(isArrayKeyAnEmptyString('birthday', $formvalues)){
			$formvalues['birthday'] = NULL;
		}
		if(isArrayKeyAnEmptyString('birthmonth', $formvalues)){
			$formvalues['birthmonth'] = NULL;
		}
		if(isArrayKeyAnEmptyString('birthyear', $formvalues)){
			$formvalues['birthyear'] = NULL;
		}
		/*if(!isEmptyString($formvalues['birthday']) && !isEmptyString($formvalues['birthmonth']) && !isEmptyString($formvalues['birthyear'])){
			$formvalues['dateofbirth'] = $formvalues['birthyear']."-".$formvalues['birthmonth']."-".$formvalues['birthday'];
		} else {
			
		}*/
		if(isArrayKeyAnEmptyString('birthyear', $formvalues)){
			$formvalues['dateofbirth'] = NULL;
		}
		if($formvalues['type'] == 1 && isEmptyString($formvalues['personid'])){
			if(isArrayKeyAnEmptyString('fbirthday', $formvalues)){
				$formvalues['fbirthday'] = NULL;
			}
			if(isArrayKeyAnEmptyString('fbirthmonth', $formvalues)){
				$formvalues['fbirthmonth'] = NULL;
			}
			if(isArrayKeyAnEmptyString('fbirthyear', $formvalues)){
				$formvalues['fbirthyear'] = NULL;
			}
			if(!isEmptyString($formvalues['fbirthday']) && !isEmptyString($formvalues['fbirthmonth']) && !isEmptyString($formvalues['fbirthyear'])){
				$formvalues['fdateofbirth'] = $formvalues['fbirthyear']."-".$formvalues['fbirthmonth']."-".$formvalues['fbirthday'];
			} else {
				if(!isEmptyString($formvalues['fdateofbirth'])){
					$formvalues['fdateofbirth'] = changeDateFromPageToMySQLFormat($formvalues['fdateofbirth']); 
				} else {
					$formvalues['fdateofbirth'] = NULL;
				}
			}
			if(isEmptyString($formvalues['fdateofbirth'])){
				$formvalues['fdateofbirth'] = NULL;
			}
		}
		if($formvalues['type'] == 2 && isEmptyString($formvalues['personid'])){
			if(isArrayKeyAnEmptyString('mbirthday', $formvalues)){
				$formvalues['mbirthday'] = NULL;
			}
			if(isArrayKeyAnEmptyString('mbirthmonth', $formvalues)){
				$formvalues['mbirthmonth'] = NULL;
			}
			if(isArrayKeyAnEmptyString('mbirthyear', $formvalues)){
				$formvalues['mbirthyear'] = NULL;
			}
			if(!isEmptyString($formvalues['mbirthday']) && !isEmptyString($formvalues['mbirthmonth']) && !isEmptyString($formvalues['mbirthyear'])){
				$formvalues['mdateofbirth'] = $formvalues['mbirthyear']."-".$formvalues['mbirthmonth']."-".$formvalues['mbirthday'];
			} else {
				if(!isEmptyString($formvalues['mdateofbirth'])){
					$formvalues['mdateofbirth'] = changeDateFromPageToMySQLFormat($formvalues['mdateofbirth']); 
				} else {
					$formvalues['mdateofbirth'] = NULL;
				}
			}
		}
		if(isEmptyString($formvalues['familyid'])){
			$formvalues['familyid'] = NULL;
		}
		if(isEmptyString($formvalues['fatherid'])){
			$formvalues['fatherid'] = NULL;
		}
		if(isEmptyString($formvalues['motherid'])){
			$formvalues['motherid'] = NULL;
		}
		if(isEmptyString($formvalues['addressid'])){
			$formvalues['addressid'] = NULL;
		}
		if($formvalues['type'] == '1'){
			// person is a muganda. check if father does not exist and create entry 
			if(isEmptyString($formvalues['fatherid'])){
				$family = array(
					"father" => 
						array(
							"type" => $formvalues['type'],
							"firstname" => $formvalues['ffirstname'], 
							"lastname" => $formvalues['flastname'],
							"clanname" => $formvalues['fclanname'],
							"clanid" => $formvalues['fclanid'],
							"gender" => 1,
							"lifestatus" => $formvalues['flifestatus'],
							"dateofbirth" => $formvalues['fdateofbirth'],
							"events" => array(
								array(
									"eventtype" => 1,
									"startday" => isEmptyString($formvalues['fbirthday']) ? NULL : $formvalues['fbirthday'], 
									"startmonth" => isEmptyString($formvalues['fbirthmonth']) ? NULL : $formvalues['fbirthmonth'], 
									"startyear" => isEmptyString($formvalues['fbirthyear']) ? NULL : $formvalues['fbirthyear'], 
									"starttype" => 1,
									"createdby" => 1
								)
							),
							"createdby" => "1"
						),
					"createdby" => "1",
					"addedbyid" => $formvalues['id']
				);
			} else {
				$family = array();
				$fam = new Family();
				$fam->populate($formvalues['familyid']);
				$fam_array = $fam->toArray();
				
			}
			// person takes same clan as father
			$formvalues['clanid'] = $formvalues['fclanid'];
			if(count($family) > 0){
				$formvalues['family'] = $family;
			}
		}
		
		if($formvalues['type'] == '2'){
			// person is a muganda. check if mother does not exist and create entry 
			if(isEmptyString($formvalues['motherid'])){
				$family = array(
					"mother" => 
						array(
							"type" => $formvalues['type'],
							"firstname" => $formvalues['mfirstname'], 
							"lastname" => $formvalues['mlastname'],
							"clanname" => $formvalues['mclanname'],
							"clanid" => $formvalues['mclanid'],
							"gender" => 1,
							"lifestatus" => $formvalues['mlifestatus'],
							"dateofbirth" => $formvalues['mdateofbirth'],
							"events" => array(
								array(
									"eventtype" => 1,
									"startday" => isEmptyString($formvalues['mbirthday']) ? NULL : $formvalues['mbirthday'], 
									"startmonth" => isEmptyString($formvalues['mbirthmonth']) ? NULL : $formvalues['mbirthmonth'], 
									"startyear" => isEmptyString($formvalues['mbirthyear']) ? NULL : $formvalues['mbirthyear'], 
									"starttype" => 1,
									"createdby" => 1
								)
							),
							"createdby" => "1"
						),
					"createdby" => "1",
					"addedbyid" => $formvalues['id']
				);
			} else {
				$family = array();
				$fam = new Family();
				$fam->populate($formvalues['familyid']);
				$fam_array = $fam->toArray();
			}
		}
		
		$theaddresses = $person->getPhysicalAddresses();
		$existing_addresses = $theaddresses->toArray();
		$synchronised_addresses = array();
		// debugMessage($existing_addresses);
		if(isEmptyString($formvalues['addressid']) || !$existing_addresses){
			$addresses  = array(
					md5(0) => array("country" => $formvalues['country'], "createdby" => "1")
				);
			$synchronised_addresses = $addresses;
		} else {
			$d_key = array_search_key_by_id($existing_addresses, $formvalues['addressid']);
			$address  = array(
					$d_key => array("id" => $formvalues['addressid'], "country" => $formvalues['country'], "createdby" => "1")
				);
			// sync addresses	
			$synchronised_addresses = multidimensional_array_merge($existing_addresses, $address);
		}
		
		if(count($synchronised_addresses) > 0){
			$formvalues['invite_addresses'] = $synchronised_addresses;
		} else {
			unset($formvalues['addresses']);
		}
		
		// user account data
		$userarray = array(
						"personid" => $formvalues['personid'],
						"gender" => $formvalues['gender'],
					    "firstname" => $formvalues['firstname'],
					    "lastname" => $formvalues['lastname'],
					    "email" => $formvalues['email'],
					    "password" => sha1($formvalues['password']),
					    "agreedtoterms" => $formvalues['agreedtoterms'], 
					    "membershipplanid" => $formvalues['membershipplanid'],
						"isactive" => 1,
						"activationkey" => NULL,
		  				"activationdate" => date("Y-m-d"),
						"usergroups" => array(
							array("groupid" => 2)
						),
						"createdby" => "1"
					);
					
		$formvalues['user'] = $userarray;
		$formvalues['hasacceptedinvite'] = '1';
		// debugMessage($formvalues);
		
		$person->processPost($formvalues);
		// debugMessage($person->toArray()); // exit();
		
		// check for processing errors
		if($person->hasError()) {
			// debugMessage('process errors are '.$person->getErrorStackAsString()); exit();
			$session->setVar(FORM_VALUES, $this->_getAllParams());
    		$session->setVar(ERROR_MESSAGE, $person->getErrorStackAsString()); 
			$this->_helper->redirector->gotoUrl(decode($this->_getParam(URL_FAILURE)));
		} else {
			
			try {
				$newpar = "0";
				$partype = "0";
				if(($formvalues['type'] == '1' && isEmptyString($formvalues['fatherid'])) || ($formvalues['type'] == '2' && isEmptyString($formvalues['motherid']) )){
					$newpar = "1";
				}
				if($formvalues['type'] == '1' && isEmptyString($formvalues['fatherid'])){
					$partype = "1";
				}
				if($formvalues['type'] == '2' && isEmptyString($formvalues['motherid'])){
					$partype = "2";
				}
				// debugMessage($newpar." and ".$partype);
				if($person->transactionInviteUpdate($newpar, $partype)){
					$this->_helper->redirector->gotoUrl(decode($this->_getParam(URL_SUCCESS))); 
				}
			} catch (Exception $e) {
				$session->setVar(FORM_VALUES, $this->_getAllParams());
    			$session->setVar(ERROR_MESSAGE, $e->getMessage()); 
    			// debugMessage('save errors are '.$e->getMessage());
				$this->_helper->redirector->gotoUrl(decode($this->_getParam(URL_FAILURE)));
			}
		}
		// parent::createAction();
		return false;
	}
	
	function activateAction() {
		$user = new UserAccount(); 
		$user->populate(decode($this->_getParam("id")));
		
		$this->view->result = $user->activateAccount($this->_getParam('actkey'));
		// debugMessage($this->_getAllParams());
		
		if (!$this->view->result) {
			// activation failed
			$this->view->message = $user->getErrorStackAsString();
		}
	}
	
	function activateaccountAction() {
		$session = SessionWrapper::getInstance(); 
		// replace the decoded id with an undecoded value which will be used during processPost() 
		$id = decode($this->_getParam('id')); 
		$this->_setParam('id', $id); 
		
		$user = new UserAccount(); 
		$user->populate($id);
		$user->processPost($this->_getAllParams());
		
		if ($user->hasError()) {
			$session->setVar(FORM_VALUES, $this->_getAllParams());
    		$session->setVar(ERROR_MESSAGE, $user->getErrorStackAsString()); 
			$this->_helper->redirector->gotoUrl(decode($this->_getParam(URL_FAILURE)));
		}
		
		$result = $user->activateAccount($this->_getParam('activationkey'));
		if ($result) {
			// go to sucess page 
			$this->_helper->redirector->gotoUrl(decode($this->_getParam(URL_SUCCESS))); 
		} else {
			$session->setVar(FORM_VALUES, $this->_getAllParams());
    		$session->setVar(ERROR_MESSAGE, $user->getErrorStackAsString()); 
			$this->_helper->redirector->gotoUrl(decode($this->_getParam(URL_FAILURE)));
		}
	}
	
	function inviteconfirmationAction() {
		
	}
	
	function confirmAction() {
		
	}
	
	function activationerrorAction() {
		
	}
	
	function activationconfirmationAction() {
		
	}
	
	function getcaptchaAction(){
		$this->view->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(true);
		$session = SessionWrapper::getInstance(); 
		
		$string = '';
		for ($i = 0; $i < 5; $i++) {
			$string .= chr(rand(97, 122));
		}
		$session->setVar('random_number', $string);
		// $_SESSION['random_number'] = $string;

		echo $session->getVar('random_number');
	}
	function captchaAction() {
		$this->view->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(true);
		$session = SessionWrapper::getInstance();
		//include('dbcon.php');
		// debugMessage('scre is '.strtolower($this->_getParam('code')));
		// debugMessage('rand is '.strtolower($session->getVar('random_number')));
		if(strtolower($this->_getParam('code')) == strtolower($session->getVar('random_number'))){
			echo 1;// submitted 
		} else {
			echo 0; // invalid code
		}
	}
}
