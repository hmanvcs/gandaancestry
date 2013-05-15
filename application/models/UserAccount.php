<?php

class UserAccount extends BaseEntity {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('useraccount');
		$this->hasColumn('type', 'integer', null, array('default' => '2'));	
		$this->hasColumn('personid', 'integer', null);
		$this->hasColumn('firstname', 'string', 255, array( 'notnull' => true, 'notblank' => true));
		$this->hasColumn('lastname', 'string', 255, array('notnull' => true, 'notblank' => true));
		$this->hasColumn('othernames', 'string', 255);	
		$this->hasColumn('username', 'string', 50);
		$this->hasColumn('email', 'string', 50, array('notnull' => true, 'notblank' => true));
		$this->hasColumn('password', 'string', 50);
		$this->hasColumn('gender', 'integer', null); // 1=Male, 2=Female, 3=Unknown
		$this->hasColumn('isactive', 'integer', null, array('default' => '0')); // 0=noactivated, 1=active, 2=deactivated
		$this->hasColumn('activationkey', 'string', 15);
		$this->hasColumn('activationdate', 'date');
		$this->hasColumn('agreedtoterms', 'integer', null, array('default' => '0'));	// 0=NO, 1=YES
		$this->hasColumn('membershipplanid', 'integer', null, array('default' => '1'));
		$this->hasColumn('paymenttype', 'integer', null);	
		$this->hasColumn('expirydate','date');
		$this->hasColumn('autorenew', 'integer', null);	// 0=NO, 1=YES
		$this->hasColumn('notes', 'string', 1000);
		
		// override the not null and not blank properties for the createdby column in the BaseEntity
		$this->hasColumn('createdby', 'integer', 11);
	}
	
	/**
	 * Contructor method for custom initialization
	 */
	public function construct() {
		parent::construct();
		
		$this->addDateFields(array("expirydate","activationdate"));
		
		// set the custom error messages
       	$this->addCustomErrorMessages(array(
       									"firstname.notblank" => $this->translate->_("useraccount_firstname_error"),
       									"lastname.notblank" => $this->translate->_("useraccount_lastname_error"),
       									"email.notblank" => $this->translate->_("useraccount_email_error"),
       									"email.email" => $this->translate->_("useraccount_email_invalid_error")										
       	       						));
	}
	/**
	 * Model relationships
	 */
	public function setUp() {
		parent::setUp(); 
		// copied directly from BaseEntity since the createdby can be NULL when a user signs up 
		// automatically set timestamp column values for datecreated and lastupdatedate 
		$this->actAs('Timestampable', 
						array('created' => array(
												'name' => 'datecreated',    
											),
							 'updated' => array(
												'name'     =>  'lastupdatedate',    
												'onInsert' => false,
												'options'  =>  array('notnull' => false)
											)
						)
					);
		$this->hasMany('UserGroup as usergroups',
							array('local' => 'id',
									'foreign' => 'userid'
							)
						);
		$this->hasOne('UserAccount as creator', 
								array(
									'local' => 'createdby',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Person as person', 
								array(
									'local' => 'personid',
									'foreign' => 'id',
								)
						);
		$this->hasMany('Event as events',
								array(
									'local' => 'id',
									'foreign' => 'userid'
								)
						);
	}
	/**
	 * Custom model validation
	 */
	function validate() {
		// add the address for the unique email
		$this->addCustomErrorMessages(array("email.unique" => sprintf($this->translate->_("useraccount_email_unique_error"), $this->getEmail())));
		// execute the column validation 
		parent::validate();
		
		$conn = Doctrine_Manager::connection();
	
		// validate unique username and email
		$email_id_check = "";
		if(!isEmptyString($this->getID())){
			$email_id_check = " AND id <> '".$this->getID()."' ";
		}
		// unique email
		$email_query = "SELECT email FROM useraccount WHERE email = '".$this->getEmail()."' ".$email_id_check."";
		$email_result = $conn->fetchOne($email_query);
		if(!isEmptyString($email_result)){
			$this->getErrorStack()->add("email.unique", sprintf($this->translate->_("useraccount_email_unique_error"), $this->getEmail()));
		}
		
		// check that at least one group has been specified
		if ($this->getUserGroups()->count() == 0) {
			$this->getErrorStack()->add("groups", $this->translate->_("useraccount_group_error"));
		}
	}
	/**
	 * Preprocess model data
	 */
	function processPost($formvalues){
		// if the passwords are not changed , set value to null
		if(isArrayKeyAnEmptyString('password', $formvalues)){
			unset($formvalues['password']); 
		} else {
			$formvalues['password'] = sha1($formvalues['password']); 
		}
		# force setting of default none string column values. enum, int and date 	
		if(isArrayKeyAnEmptyString('isactive', $formvalues)){
			unset($formvalues['isactive']); 
		}
		if(isArrayKeyAnEmptyString('type', $formvalues)){
			unset($formvalues['type']); 
		}
		if(isArrayKeyAnEmptyString('agreedtoterms', $formvalues)){
			unset($formvalues['agreedtoterms']); 
		}
		if(isArrayKeyAnEmptyString('gender', $formvalues)){
			unset($formvalues['gender']); 
		}
		if(isArrayKeyAnEmptyString('activationdate', $formvalues)){
			unset($formvalues['activationdate']); 
		}
		if(isArrayKeyAnEmptyString('membershipplanid', $formvalues)){
			unset($formvalues['membershipplanid']); 
		}
		if(isArrayKeyAnEmptyString('paymenttype', $formvalues)){
			unset($formvalues['paymenttype']); 
		}
		if(isArrayKeyAnEmptyString('expirydate', $formvalues)){
			unset($formvalues['expirydate']); 
		}
		if(isArrayKeyAnEmptyString('autorenew', $formvalues)){
			unset($formvalues['autorenew']); 
		}
		if(isArrayKeyAnEmptyString('personid', $formvalues)){
			unset($formvalues['personid']); 
		}
		// move the data from $formvalues['usergroups_groupid'] into $formvalues['usergroups'] array
		// the key for each group has to be the groupid
		if (array_key_exists('usergroups_groupid', $formvalues)) {
			$groupids = $formvalues['usergroups_groupid']; 
			$usergroups = array(); 
			foreach ($groupids as $id) {
				$usergroups[]['groupid'] = $id; 
			}
			$formvalues['usergroups'] = $usergroups; 
			// remove the usergroups_groupid array, it will be ignored, but to be on the safe side
			unset($formvalues['usergroups_groupid']); 
		}
		
		// add the userid if the useraccount is being edited
		if (!isArrayKeyAnEmptyString('id', $formvalues)) {
			if (array_key_exists('usergroups', $formvalues)) {
				$usergroups = $formvalues['usergroups']; 
				foreach ($usergroups as $key=>$value) {
					$formvalues['usergroups'][$key]["userid"] = $formvalues["id"];
				}
			} 
		}
		// debugMessage($formvalues); exit();
		parent::processPost($formvalues);
	}
	/*
	 * Custom save logic
	 */
	function transactionSave(){
		$conn = Doctrine_Manager::connection();
		// begin transaction to save
		try {
			$conn->beginTransaction();
			// initial save
			$this->save();
			
			$updatefather = false; 
			$updatemother = false;
			// update the ids on the profiles
			if(!isEmptyString($this->getPersonID())){
				$this->setCreatedBy($this->getID());
				$this->setType(2);
				$this->getPerson()->setUserID($this->getID());
				$this->getPerson()->setEmail($this->getEmail());
				$this->getPerson()->setOwnerID($this->getID());
				$this->getPerson()->setCreatedBy($this->getID());
				$this->getPerson()->getEvents()->get(0)->setUserID($this->getID());
				$this->getPerson()->getEvents()->get(0)->setCreatedBy($this->getID());
				$this->getPerson()->getAddresses()->get(0)->setCreatedBy($this->getID());
				$this->getPerson()->getAddresses()->get(0)->setIsPrimary(1);
				
				if(!isEmptyString($this->getPerson()->getFamilyID()) && !isEmptyString($this->getPerson()->getFamily()->getFatherID())){
					$this->getPerson()->getFamily()->getFather()->setOwnerID($this->getID());
					$this->getPerson()->getFamily()->getFather()->setCreatedBy($this->getID());
					$this->getPerson()->getFamily()->getFather()->setAddedByID($this->getPersonID());
					$updatefather = true;
				}
				if(!isEmptyString($this->getPerson()->getFamilyID()) && !isEmptyString($this->getPerson()->getFamily()->getMotherID())){
					$this->getPerson()->getFamily()->getMother()->setOwnerID($this->getID());
					$this->getPerson()->getFamily()->getMother()->setCreatedBy($this->getID());
					$this->getPerson()->getFamily()->getMother()->setAddedByID($this->getPersonID());
					$updatemother = true;
				}
				$this->getPerson()->getFamily()->setAddedByID($this->getPersonID());
				// set activation key
				$this->setActivationKey($this->generateActivationKey());
			}
			// save current profile changes
			$this->save();
			
			// save parent profile changes
			if($updatefather){
				$this->getPerson()->getFamily()->getFather()->getEvents()->get(0)->setCreatedBy($this->getID());
				$this->getPerson()->getFamily()->getFather()->setAddedByID($this->getPersonID());
				$this->getPerson()->getFamily()->getFather()->save();
				
				// setup family tree for subscriber
				$ptree1 = $this->getPerson()->getFamilyTrees()->get(0);
				$ptree1->setTreeID($this->getPersonID());
				$ptree1->setFocusID($this->getPersonID());
				$ptree1->setRelativeID($this->getPersonID());
				$ptree1->setLevel('10');
				$ptree1->setRelationShipID('0');
				$ptree1->setAddRelationship('0');
				$ptree1->setAddedAs($this->getPersonID());
				$ptree1->save();
				
				$ptree2 = $this->getPerson()->getFamilyTrees()->get(1);
				$ptree2->setTreeID($this->getPersonID());
				$ptree2->setFocusID($this->getPersonID());
				$ptree2->setRelativeID($this->getPerson()->getFatherID());
				$ptree2->setLevel('9');
				$ptree2->setRelationShipID('1');
				$ptree2->setAddRelationship('1');
				$ptree2->setAddedAs($this->getPersonID());
				$ptree2->setPaternity(1);
				$ptree2->save();
				
				/*$ftree1 = $this->getPerson()->getFamilyTrees()->get(3);
				$ftree1->setTreeID($this->getPersonID());
				$ftree1->setFocusID($this->getPerson()->getFatherID());
				$ftree1->setRelativeID($this->getPerson()->getFatherID());
				$ftree1->setLevel('10');
				$ftree1->setRelationShipID('0');
				$ftree1->setAddRelationship('0');
				$ftree1->setAddedAs($this->getPersonID());
				$ftree1->save();
				
				$ftree2 = $this->getPerson()->getFamilyTrees()->get(4);
				$ftree2->setTreeID($this->getPersonID());
				$ftree2->setFocusID($this->getPerson()->getFatherID());
				$ftree2->setRelativeID($this->getPersonID());
				$ftree2->setLevel('11');
				$ftree2->setRelationShipID($this->getPerson()->isMale() ? 5 : 6);
				$ftree2->setAddRelationship($this->getPerson()->isMale() ? 5 : 6);
				$ftree2->setAddedAs($this->getPersonID());
				$ftree2->setPaternity(1);
				$ftree2->save();*/
			}
			if($updatemother){
				$this->getPerson()->getFamily()->getMother()->getEvents()->get(0)->setCreatedBy($this->getID());
				$this->getPerson()->getFamily()->getMother()->setAddedByID($this->getPersonID());
				$this->getPerson()->getFamily()->getMother()->save();
				
				// setup family tree for subscriber
				$ptree1 = $this->getPerson()->getFamilyTrees()->get(0);
				$ptree1->setTreeID($this->getPersonID());
				$ptree1->setFocusID($this->getPersonID());
				$ptree1->setRelativeID($this->getPersonID());
				$ptree1->setLevel('10');
				$ptree1->setRelationShipID('0');
				$ptree1->setAddRelationship('0');
				$ptree1->setAddedAs($this->getPersonID());
				$ptree1->save();
				
				$ptree2 = $this->getPerson()->getFamilyTrees()->get(1);
				$ptree2->setTreeID($this->getPersonID());
				$ptree2->setFocusID($this->getPersonID());
				$ptree2->setRelativeID($this->getPerson()->getMotherID());
				$ptree2->setLevel('9');
				$ptree2->setRelationShipID('2');
				$ptree2->setAddRelationship('2');
				$ptree2->setAddedAs($this->getPersonID());
				//$ptree2->setPaternity(2);
				$ptree2->save();
				
				/*$mtree1 = $this->getPerson()->getFamilyTrees()->get(3);
				$mtree1->setTreeID($this->getPersonID());
				$mtree1->setFocusID($this->getPerson()->getMotherID());
				$mtree1->setRelativeID($this->getPerson()->getMotherID());
				$mtree1->setLevel('10');
				$mtree1->setRelationShipID('0');
				$mtree1->setAddRelationship('0');
				$mtree1->setAddedAs($this->getPersonID());
				$mtree1->save();
				
				$mtree2 = $this->getPerson()->getFamilyTrees()->get(4);
				$mtree2->setTreeID($this->getPersonID());
				$mtree2->setFocusID($this->getPerson()->getMotherID());
				$mtree2->setRelativeID($this->getPersonID());
				$mtree2->setLevel('11');
				$mtree2->setRelationShipID($this->getPerson()->isMale() ? 5 : 6);
				$mtree2->setAddRelationship($this->getPerson()->isMale() ? 5 : 6);
				$mtree2->setAddedAs($this->getPersonID());
				$mtree2->save();*/
			}
		 	// commit changes
			$conn->commit();
			
		} catch(Exception $e){
			$conn->rollback();
			// debugMessage('Error is '.$e->getMessage());
			throw new Exception($e->getMessage());
		}
		
		// send notification
		$this->sendSignupNotification();
			
		return true;
	}
	# reset auto increment ids
	function autoReset() {
		$tables = array("useraccount","aclusergroup","person","family","familytree","address","contact","event");
		foreach($tables as $table){
			$query = "ALTER TABLE ".$table." AUTO_INCREMENT = 1";
			$conn = Doctrine_Manager::connection(); 
			$conn->execute($query);
		}
		return true;
	}
	/**
	 * Reset the password for  the user. All checks and validations have been completed
	 * 
	 * @param String $newpassword The new password. If the new password is not defined, a new random password is generated
	 *
	 * @return Boolean TRUE if the password is changed, FALSE if it fails to change the user's password.
	 */
	 function resetPassword($newpassword = "") {
	 	// check if the password is empty 
	 	if (isEmptyString($newpassword)) {
	 		// generate a new random password
	 		$newpassword = $this->generatePassword(); 
	 	}
	 	return $this->changePassword("", $newpassword, false); 
	}
	/**
	 * Change the password for the user. All checks and validations have been completed
	 * 
	 * @param String $providedpassword The password provided on the screen
	 * @param String $newpassword The new password
     * @param boolean $verify Verify whether the provided password is the same as the user's current password
	 *
	 * @return TRUE if the password is changed, FAlSE if it fails to change the user's password.
	 */
	function changePassword($providedpassword, $newpassword, $verify = true){
		// check if the provided password is the same as that for the user
      	if ($verify) {
          /*if ($this->getPassword() != sha1($providedpassword)) {
              $this->getErrorStack()->add("oldpassword.invalid", $this->translate->_("useraccount_oldpassword_invalid_error"));
              return false;
          }*/
     	}
		// now change the password
		$this->setPassword(sha1($newpassword));
      	$this->setActivationKey('');
      	
      	try {
      		$this->save();
      		# Log to audit trail that a password has been changed.
			$audit_values = array("transactiontype" => USER_CHANGE_PASSWORD, "userid" => $this->getID(), "executedby" => $this->getID(), "success" => 'Y');
			$audit_values['transactiondetails'] = $this->getName()." changed account password";
			$this->notify(new sfEvent($this, USER_CHANGE_PASSWORD, $audit_values));
      	
      	} catch (Exception $e){
      		# Log to audit trail that user has failed to change password
			$audit_values = array("transactiontype" => USER_CHANGE_PASSWORD, "userid" => $this->getID(), "executedby" => $this->getID(), "success" => 'N');
			$audit_values['transactiondetails'] = $this->getName()." failed to change account password". $e->getMessage();
			$this->notify(new sfEvent($this, USER_CHANGE_PASSWORD, $audit_values));
      	}
		return true;
	}
	/*
	 * Reset the user's password and send a notification to complete the recovery  
	 *
	 * @return Boolean TRUE if resetting is successful and FALSE if emailaddress security questions and answer is invalid or has no record in the database
	 */
	function recoverPassword() {
      // save to the audit trail
		$audit_values = array("transactiontype" => USER_RECOVER_PASSWORD); 
		// set the updater of the account only when specified
		if (!isEmptyString($this->getLastUpdatedBy())) {
			$audit_values['executedby'] = $this->getLastUpdatedBy();
		}
		
		# check that the user's email exists and that they are signed up
		if(!$this->findByEmail($this->getEmail())){
			$audit_values['transactiondetails'] = "Recovery of password for '".$this->getEmail()."' failed - user not found";
			$this->notify(new sfEvent($this, USER_RECOVER_PASSWORD, $audit_values));
			return false;
		}
			
		# reset the password and set the next password change date
		$this->setActivationKey($this->generateActivationKey());
		# save the activation key for the user 
		$this->save();
		
		# Send the user the reset password email
		$this->sendRecoverPasswordEmail();
		
		// save the audit trail record
		// the transaction details is the email address being used to
		$audit_values['userid'] = $this->getID(); 
		$audit_values['transactiondetails'] = "Password Recovery link for '".$this->getEmail()."' sent to '".$this->getEmail()."'";
		$audit_values['success'] = 'Y';
		$this->notify(new sfEvent($this, USER_RECOVER_PASSWORD, $audit_values));
		
		return true;
	}
	/**
	 * Send an email with a link to activate the members' account
	 */
	function sendRecoverPasswordEmail() {
		$template = new EmailTemplate(); 
		// create mail object
		$mail = getMailInstance(); 

		// assign values
		$template->assign('firstname', $this->getFirstName());
		// just send the parameters for the activationurl, the actual url will be built in the view 
		$template->assign('resetpasswordurl', array("controller"=> "user","action"=> "resetpassword", "actkey" => $this->getActivationKey(), "id" => encode($this->getID())));
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		
		// configure base stuff
		$mail->addTo($this->getEmail());
		// set the send of the email address
		$mail->setFrom($this->config->notification->emailmessagesender, $this->translate->_('useraccount_email_notificationsender'));
		
		$mail->setSubject($this->translate->_('useraccount_email_subject_recoverpassword'));
		// render the view as the body of the email
		$mail->setBodyHtml($template->render('recoverpassword.phtml'));
		// debugMessage($template->render('recoverpassword.phtml')); 
		$mail->send();
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		$mail->clearFrom();
		
		return true;
   }
   /**
    * Process the activation key from the activation email
    * 
    * @param $actkey The activation key 
    * 
    * @return bool TRUE if the signup process completes successfully, false if activation key is invalid or save fails
    */
   function activateAccount($actkey) {
   		# save to the audit trail

		# validate the activation key 
		/*if($this->getActivationKey() != $actkey){
			# Log to audit trail when an invalid activation key is used to activate account
			$audit_values = array("executedby" => $this->getID(), "transactiontype" => USER_SIGNUP, "success" => "N");
			$audit_values["transactiondetails"] = "Invalid activation key used in activating User ".$this->getFirstName()." ".$this->getLastName(). " (".$this->getEmail()."). "; 
			$this->notify(new sfEvent($this, USER_SIGNUP, $audit_values));
			$this->getErrorStack()->add("user.activationkey", $this->translate->_("useraccount_invalid_actkey_error"));
			return false;
		}*/
   		
		# set active to true and blank out activation key
		$this->setIsActive(1);		
		$this->setActivationKey("");
		$this->setActivationDate(date("Y-m-d H:i:s"));
		
		# save
		try {
			$this->save();
			
			# Add to audittrail that a new user has been activated.
			$audit_values = array("executedby" => $this->getID(), "transactiontype" => USER_SIGNUP, "success" => "Y");
			$audit_values["transactiondetails"] = $this->getName()." (".$this->getEmail().") has completed the sign up process"; 
			$this->notify(new sfEvent($this, USER_SIGNUP, $audit_values));
		
			return true;
			
		} catch (Exception $e){
			$this->getErrorStack()->add("user.activation", $this->translate->_("useraccount_activation_error"));
			$this->logger->err("Error activating useraccount ".$this->getEmail()." ".$e->getMessage());
			# log to audit trail when an error occurs in updating payee details on user account
			$audit_values = array("executedby" => $this->getID(), "transactiontype" => USER_SIGNUP, "success" => "N");
			$audit_values["transactiondetails"] = "An error occured in activating account for ".$this->getFirstName()." ".$this->getLastName(). " (".$this->getEmail()."). ".$e->getMessage(); 
			$this->notify(new sfEvent($this, USER_SIGNUP, $audit_values));
			return false;
		}
   }
   # test activate account
   function testActivate() {
   		$this->setIsActive(1);		
		$this->setActivationKey("");
		$this->setActivationDate(date("Y-m-d H:i:s"));
   		
		$this->save();
		
		return true;
   }
	/**
    * Process the deactivation for an agent
    * 
    * @param $actkey The activation key 
    * 
    * @return bool TRUE if the signup process completes successfully, false if activation key is invalid or save fails
    */
   function deactivateAccount() {
   		# save to the audit trail
   		
		# set active to true and blank out activation key
		$this->setIsActive('0');		
		$this->setActivationKey('');
		// $this->setActivationDate(NULL);
		
		# save
		try {
			$this->save();
			return true;
			
		} catch (Exception $e){
			$this->getErrorStack()->add("user.activation", $this->translate->_("useraccount_activation_error"));
			$this->logger->err("Error activating useraccount ".$this->getEmail()." ".$e->getMessage());
			# log to audit trail when an error occurs in updating payee details on user account
			$audit_values = array("executedby" => $this->getID(), "transactiontype" => USER_SIGNUP, "success" => "N");
			$audit_values["transactiondetails"] = "An error occured in activating account for ".$this->getFirstName()." ".$this->getLastName(). " (".$this->getEmail()."). ".$e->getMessage(); 
			$this->notify(new sfEvent($this, USER_SIGNUP, $audit_values));
			return false;
		}
   }	
	/**
	 * Send a notification to agent that their account will be approved shortly
	 * 
	 * @return bool whether or not the signup notification email has been sent
	 *
	 */
	function sendSignupNotification() {
		$template = new EmailTemplate(); 
		# create mail object
		$mail = getMailInstance(); 

		// assign values
		$template->assign('firstname', $this->getFirstName());
		/*$viewurl = $template->serverUrl($template->baseUrl('signup/activate/id/'.encode($this->getID())."/actkey/".$this->getActivationKey()."/cid/".$contactid."/ref/".encode($newemail)."/"));
		$template->assign('activationurl', $viewurl);*/
		
		$template->assign('activationurl', array("action"=> "activate", "actkey" => $this->getActivationKey(), "id" => encode($this->getID())));
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		
		// configure base stuff
		$mail->addTo($this->getEmail(), $this->getName());
		// set the send of the email address
		$mail->setFrom($this->config->notification->emailmessagesender, $this->translate->_('useraccount_email_notificationsender'));
		
		$mail->setSubject(sprintf($this->translate->_('useraccount_email_subject_signup'), $this->translate->_('appname')));
		// render the view as the body of the email
		$mail->setBodyHtml($template->render('signupnotification.phtml'));
		// debugMessage($template->render('signupnotification.phtml')); exit();
		$mail->send();
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		$mail->clearFrom();
		
		return true;
	}
	/**
	 * Send a notification to agent that their account will be approved shortly
	 * 
	 * @return bool whether or not the signup notification email has been sent
	 *
	 */
	function sendDeactivateNotification() {
		$template = new EmailTemplate(); 
		# create mail object
		$mail = getMailInstance(); 

		// assign values
		$template->assign('firstname', $this->getFirstName());
		// $template->assign('activationurl', array("action"=> "activate", "actkey" => $this->getActivationKey(), "id" => encode($this->getID())));
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		
		// configure base stuff
		$mail->addTo($this->getEmail(), $this->getName());
		// set the send of the email address
		$mail->setFrom($this->config->notification->emailmessagesender, $this->translate->_('useraccount_email_notificationsender'));
		
		$mail->setSubject("Account Deactivation");
		// render the view as the body of the email
		$mail->setBodyHtml($template->render('accountdeactivationconfirmation.phtml'));
		// debugMessage($template->render('accountdeactivationconfirmation.phtml')); // exit();
		$mail->send();
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		$mail->clearFrom();
		
		return true;
	}
	# change email notification to new address
	function sendNewEmailNotification($newemail, $contactid) {
		$template = new EmailTemplate(); 
		# create mail object
		$mail = getMailInstance(); 
		
		// assign values
		$template->assign('firstname', $this->getFirstName());
		$template->assign('fromemail', $this->getEmail());
		$template->assign('toemail', $newemail);
		$viewurl = $template->serverUrl($template->baseUrl('user/changeemail/id/'.encode($this->getID())."/actkey/".$this->getActivationKey()."/cid/".$contactid."/ref/".encode($newemail)."/"));
		$template->assign('activationurl', $viewurl);
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		
		// configure base stuff
		$mail->addTo($newemail, $this->getName());
		// set the send of the email address
		$mail->setFrom($this->config->notification->emailmessagesender, $this->translate->_('useraccount_email_notificationsender'));
		
		$mail->setSubject("Email Change Request");
		// render the view as the body of the email
		$mail->setBodyHtml($template->render('changeemail_newnotification.phtml'));
		// debugMessage($template->render('changeemail_newnotification.phtml')); // exit();
		$mail->send();
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		$mail->clearFrom();
		
		return true;
	}
	
	# change email notification to old address
	function sendOldEmailNotification($newemail, $contactid) {
		$template = new EmailTemplate(); 
		# create mail object
		$mail = getMailInstance(); 
		
		// assign values
		$template->assign('firstname', $this->getFirstName());
		$template->assign('fromemail', $this->getEmail());
		$template->assign('toemail', $newemail);
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		
		// configure base stuff
		$mail->addTo($this->getEmail(), $this->getName());
		// set the send of the email address
		$mail->setFrom($this->config->notification->emailmessagesender, $this->translate->_('useraccount_email_notificationsender'));
		
		$mail->setSubject("Email Change Request");
		// render the view as the body of the email
		$mail->setBodyHtml($template->render('changeemail_oldnotification.phtml'));
		// debugMessage($template->render('changeemail_oldnotification.phtml')); //exit();
		$mail->send();
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		$mail->clearFrom();
		
		return true;
	}
	/**
	 * Generate a new password incase a user wants a new password
	 * 
	 * @return String a random password 
	 */
    function generatePassword() {
		return $this->generateRandomString($this->config->password->minlength);
    }
	/**
	 * Generate a 10 digit activation key  
	 * 
	 * @return String An activation key
	 */
    function generateActivationKey() {
		return substr(md5(uniqid(mt_rand(), 1)), 0, 10);
    }
   /**
    * Find a user account either by their email address 
    * 
    * @param String $email The email
    * @return UserAccount or FALSE if the user with the specified email does not exist 
    */
	function findByEmail($email) {
		# query active user details using email
		$q = Doctrine_Query::create()->from('UserAccount u')->where('u.email = ?', $email);
		$result = $q->fetchOne(); 
		
		// check if the user exists 
		if(!$result){
			// user with specified email does not exist, therefore is valid
			$this->getErrorStack()->add("user.invalid", $this->translate->_("useraccount_user_invalid_error"));
			return false;
		}
		
		$data = $result->toArray(); 

		// merge all the data including the user groups 
		$this->merge($data);
		// also assign the identifier for the object so that it can be updated
		$this->assignIdentifier($data['id']); 
		
		return true; 
	}
	# populate with another field
	function findEntryByField($email){
		$q = Doctrine_Query::create()->from('UserAccount u')->where("u.email = '".$email."'");
		$result = $q->fetchOne();
		return $result;
	}
	/**
	 * Return the user's full names, which is a concatenation of the first and last names
	 *
	 * @return String The full name of the user
	 */
	function getName() {
		return $this->getFirstName()." ".$this->getLastName();
	}
	/*
	 * TODO Put proper comments
	 */
	function generateRandomString($length) {
		$rand_array = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0","1","2","3","4","5","6","7","8","9");
		$rand_id = "";
		for ($i = 0; $i <= $length; $i++) {
			$rand_id .= $rand_array[rand(0, 35)];
		}
		return $rand_id;
	}
 	/**
     * Return an array containing the IDs of the groups that the user belongs to
     *
     * @return Array of the IDs of the groups that the user belongs to
     */
    function getGroupIDs() {
        $ids = array();
        $groups = $this->getUserGroups();
        //debugMessage($groups->toArray());
        foreach($groups as $thegroup) {
            $ids[] = $thegroup->getGroupID();
        }
        return $ids;
    }
    /**
     * Display a list of groups that the user belongs
     *
     * @return String HTML list of the groups that the user belongs to
     */
    function displayGroups() {
        $groups = $this->getUserGroups();
        $str = "";
        if ($groups->count() == 0) {
            return $str;
        }
        $str .= '<ul class="list">';
        foreach($groups as $thegroup) {
            $str .= "<li>".$thegroup->getGroup()->getName()."</li>"; 
        }
        $str .= "</ul>";
        return $str; 
    }

	# Determine if user is Male
	function isMale(){
		return $this->getGender() == '1';
	}
	# Determine if user is female
	function isFemale(){
		return $this->getGender() == '2';
	}
	# Determine gender text depending on the gender
	function getGenderText(){
		if($this->isMale()){
			return 'Male';
		} else {		
			return 'Female';
		}
	}
	# Determine if user profile has been activated
	function isActivated(){
		return $this->getChangePassword() == 1;
	}
	# Determine if user has accepted terms
	function hasAcceptedTerms(){
		return $this->getAcceptedTerms() == 1;
	}
    # Determine if user is active	 
	function isUserActive() {
		return $this->getIsActive() == 1;
	}
    # Determine if user is deactivated
	function isUserInActive() {
		return $this->getIsActive() == 0;
	}
	function findAllRegisteredPersons() {
		/*$all_results_query = "SELECT u.id FROM useraccount u where u.type = 2 AND u.personid <> ''";   
    	// debugMessage($all_results_query);
    	$conn = Doctrine_Manager::connection(); 
		$result = $conn->fetchAll($all_results_query);
		return $result;*/
		$q = Doctrine_Query::create()->from('UserAccount u')->where("u.type = 2 AND u.personid <> '' ");
		$result = $q->execute();
		return $result;
	}
}
?>
