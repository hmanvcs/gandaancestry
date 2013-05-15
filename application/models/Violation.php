<?php

class Violation extends BaseRecord {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('violation');
		$this->hasColumn('type', 'integer', null, array('notnull' => true, 'notblank' => true));
		$this->hasColumn('name', 'string', 255, array('notnull' => true, 'notblank' => true));
		$this->hasColumn('gender', 'integer', null); // 1=Male, 2=Female, 3=Unknown
		$this->hasColumn('email', 'string', 255);
		$this->hasColumn('youremail', 'string', 255, array('notnull' => true, 'notblank' => true));
		$this->hasColumn('country', 'string', 2, array('default' => 'UG'));
		$this->hasColumn('yourcountry', 'string', 2, array('default' => 'UG'));
		$this->hasColumn('message', 'string', 1000);
		$this->hasColumn('reportdate','date');
	}
	/**
	 * Contructor method for custom initialization
	 */
	public function construct() {
		parent::construct();
		
		$this->addDateFields(array("reportdate"));
		
		// set the custom error messages
       	$this->addCustomErrorMessages(array(
       									"type.notblank" => $this->translate->_("contactus_reporttype_error"),
       									"name.notblank" => "Please enter your Name",
       									"youremail.notblank" => $this->translate->_("contactus_reportyouremail_error")
       	       						));
	}
/**
	 * Preprocess model data
	 */
	function processPost($formvalues){
		// set default values for integers, dates, decimals
		if(isArrayKeyAnEmptyString('reportdate', $formvalues)){
			unset($formvalues['reportdate']); 
		}
		// debugMessage($formvalues);
		// exit();			
		parent::processPost($formvalues);
	}
	# Send contact us notification
	function reportViolationNotification() {
		$template = new EmailTemplate(); 
		# create mail object
		$mail = getMailInstance();
		$view = new Zend_View(); 
		$countries = getCountries();
		$types = getViolationTypes();
		
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
		
		// debugMessage($first);
		// assign values
		$template->assign('type', $types[$this->getType()]);
		$template->assign('name', $this->getName());
		$template->assign('email', isEmptyString($this->getEmail()) ? '--': $this->getEmail());
		$template->assign('gender', $this->getGender() == 1 ? 'Male' : 'Female');
		$template->assign('country', $countries[$this->getCountry()]);
		$template->assign('youremail', $this->getYourEmail());
		$template->assign('yourcountry', $countries[$this->getYourCountry()]);
		$template->assign('message', nl2br($this->getMessage()));
		$template->assign('reportdate', isEmptyString($this->getReportDate()) ? '--': changeMySQLDateToPageFormat($this->getReportDate()));
		
		$subject = "New user agreement violation report";
		$mail->setSubject($subject);
		// set the send of the email address
		$mail->setFrom($senderemail, "GandaAncestry");
		
		// configure base stuff
		$mail->addTo($copy1email);
		// render the view as the body of the email
		$mail->setBodyHtml($template->render('violationconfirmation.phtml'));
		// debugMessage($template->render('violationconfirmation.phtml')); // exit();
		$mail->send();
		
		$mail->clearRecipients();
		$mail->clearSubject();
		$mail->setBodyHtml('');
		$mail->clearFrom();
		
		return true;
	}
}
?>