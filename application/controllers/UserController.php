<?php

class UserController extends IndexController  {

    function checkloginAction() {
    	$session = SessionWrapper::getInstance(); 
    	# check that an email has been provided
		if (isEmptyString($this->_getParam("email"))) {
			$session->setVar(ERROR_MESSAGE, $this->_translate->translate("useraccount_email_error")); 
			$session->setVar(FORM_VALUES, $this->_getAllParams());
			// return to the home page
    		$this->_helper->redirector->gotoSimpleAndExit('login', "user");
		}
		if (isEmptyString($this->_getParam("password"))) {
			$session->setVar(ERROR_MESSAGE, $this->_translate->translate("useraccount_password_error")); 
			$session->setVar(FORM_VALUES, $this->_getAllParams());
			// return to the home page
    		$this->_helper->redirector->gotoSimpleAndExit('login', "user");
		}	

		$authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get("dbAdapter"));
		// define the table, fields and additional rules to use for authentication 
		$authAdapter->setTableName('useraccount')
				->setIdentityColumn('email')
				->setCredentialColumn('password')
				->setCredentialTreatment("sha1(?) AND isactive = '1'"); 
				
		// set the credentials from the login form
		$authAdapter->setIdentity($this->_getParam("email"))->setCredential($this->_getParam("password")); 

		// new class to audit the type of Browser and OS that the visitor is using
		$browser = new Browser();
		$audit_values = array("browserdetails" => $browser->getBrowserDetailsForAudit());
		
		if(!$authAdapter->authenticate()->isValid()) {
			// add failed login to audit trail
    		$audit_values['transactiontype'] = USER_LOGIN;
    		$audit_values['success'] = "N";
    		$audit_values['transactiondetails'] = "Login for user with email '".$this->_getParam("email")."' failed. Invalid username or password";
			$this->notify(new sfEvent($this, USER_LOGIN, $audit_values));
			
			$session->setVar(ERROR_MESSAGE, $this->_translate->translate('useraccount_login_error')); 
			$session->setVar(FORM_VALUES, $this->_getAllParams());
			// return to the home page
    		$this->_helper->redirector->gotoSimple('login', "user");
    		return false; 
		}
		// user is logged in sucessfully so add information to the session 
		$user = $authAdapter->getResultRowObject();
		
		$session->setVar("userid", $user->id);
		$session->setVar("firstname",$user->firstname);
		$session->setVar("lastname", $user->lastname);
		$session->setVar("email", $user->email);
		$session->setVar("gender", $user->gender);
		
		$useraccount = new UserAccount(); 
		$useraccount->populate($user->id);
		$session->setVar("personid", $user->personid);
		$session->setVar("familyid", $useraccount->getPerson()->getFamilyID());
		$session->setVar('type', $useraccount->getType());
		
		# clear user specific cache, before it is used again
    	$this->clearUserCache();
    
		// Add successful login event to the audit trail
		/*$audit_values['transactiontype'] = USER_LOGIN;
    	$audit_values['success'] = "Y";
		$audit_values['userid'] = $user->id;
		$audit_values['executedby'] = $user->id;
   		$audit_values['transactiondetails'] = "Login for user with email '".$this->_getParam("email")."' successful";
		$this->notify(new sfEvent($this, USER_LOGIN, $audit_values));*/
		
		if (isEmptyString($this->_getParam("redirecturl"))) {
			# forward to the dashboard
			$this->_helper->redirector->gotoSimple("index", "dashboard");
		} else {
			# redirect to the page the user was coming from 
			$this->_helper->redirector->gotoUrl(decode($this->_getParam("redirecturl")));
		}
    }
    
	/**
     * Action to display the Login page 
     */
    public function loginAction()  {
        // do nothing 
        $session = SessionWrapper::getInstance(); 
   		if(!isEmptyString($session->getVar('userid'))){
			$this->_helper->redirector->gotoUrl($this->view->baseUrl("dashboard"));	
		} 
    }
    public function recoverpasswordAction() {
    	
    }
    public function processrecoverpasswordAction(){
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		// debugMessage($this->_getAllParams());
    	if (!isEmptyString($this->_getParam('email'))) {
    		// process the password recovery 
    		$user = new UserAccount(); 
    		$user->setEmail($this->_getParam('email')); 
    		
    		// debugMessage($user->toArray());
    		if ($user->recoverPassword()) {
    			// send a link to enable the user to recover their password 
    			$this->_helper->redirector->gotoUrl($this->view->baseUrl("user/recoverpasswordconfirmation"));	
    		} else {
    			// send an error message that no user with that email was found 
    			$session = SessionWrapper::getInstance(); 
    			$session->setVar(FORM_VALUES, $this->_getAllParams()); 
    			$session->setVar(ERROR_MESSAGE, $this->_translate->translate("useraccount_user_invalid_error"));
    			$this->_helper->redirector->gotoUrl($this->view->baseUrl("user/recoverpassword"));
    		}
    	}
    	// exit();
    }
    
    public function resetpasswordAction() {
    	$user = new UserAccount(); 
    	$user->populate(decode($this->_getParam('id')));

    	// verify that the activation key in the url matches the one in the database
	    if ($user->getActivationKey() != $this->_getParam('actkey')) {
    		// send a link to enable the user to recover their password 
    		$this->_helper->redirector->gotoUrl($this->view->baseUrl("user/activationerror"));
    	} 
    	
    }
    
    public function processresetpasswordAction(){
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		// debugMessage($this->_getAllParams());
		$user = new UserAccount(); 
    	$user->populate(decode($this->_getParam('id')));
    	// debugMessage($user->toArray());
    	
   		if ($user->resetPassword($this->_getParam('password'))) {
    		// send a link to enable the user to recover their password 
    		$this->_helper->redirector->gotoUrl($this->view->baseUrl("user/resetpasswordconfirmation/id/".$this->_getParam('id')));
    	} else {
    		// echo "cannot reset password"; 
    		// send an error message that no user with that email was found 
    		$session = SessionWrapper::getInstance(); 
    		$session->setVar(ERROR_MESSAGE, $user->getErrorStackAsString());
    		$session->setVar(FORM_VALUES, $this->_getAllParams());
    		$this->_helper->redirector->gotoUrl(decode($this->_getParam(URL_FAILURE)));
    	}
    	// exit();
    }
    public function resetpasswordconfirmationAction() {}
    
    public function activationerrorAction() {}
    	
    public function recoverpasswordconfirmationAction() {}
	
	public function changepasswordconfirmationAction() {}
    
	/**
     * Action to display the Login page 
     */
    public function logoutAction()  {
    	$this->clearSession();
        // redirect to the login page 
        $this->_helper->redirector("login", "user");
    }
    
	public function changeemailAction(){
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		$session = SessionWrapper::getInstance(); 
		
		$formvalues = $this->_getAllParams();
		// debugMessage($formvalues);
		
		$newemail = decode($formvalues['ref']);
		$contactid = $formvalues['cid'];
		
		$user = new UserAccount();
		$user->populate(decode($formvalues['id']));
		
		$user->setActivationKey('');
		$user->setEmail($newemail);
		$user->getPerson()->setEmail($newemail);
		$user->save();
		// debugMessage($user->toArray());
		
		$contact = new Contact();
    	$contact->populate($contactid);
    	$contact->setUnConfirmed(NULL);
    	$contact->setIsPrimary(1);
    	$contact->save();
		
    	$this->clearSession();
   		$this->_helper->redirector->gotoUrl($this->view->baseUrl('user/login/refmsg/1'));
    }
}

