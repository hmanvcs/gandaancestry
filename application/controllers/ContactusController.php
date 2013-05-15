<?php

class ContactusController extends IndexController  {
	
	/**
	 * Sends the details of the support form by email 
	 */
	public function processcontactusAction() {
		$session = SessionWrapper::getInstance(); 
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		// debugMessage($formvalues);
		
		$person = new Person();
		// $person->tellFriendsNotification($data);
		if($person->sendContactNotification($formvalues)){
			// after send events
			$session->setVar(SUCCESS_MESSAGE, "Thank you for your interest in the GandaAncestry program. We shall be getting back to you shortly.");
			
			$this->_redirect($this->view->baseUrl('contactus/index/result/success'));
		} else {
			$session->setVar(ERROR_MESSAGE, 'Sorry! An error occured in sending the message. Please try again later ');
			
			$this->_redirect($this->view->baseUrl('contactus/index/result/error'));
		}
		// exit();
	}
	public function tellfriendAction() {
		
	}
	public function processtellfriendAction() {
		$session = SessionWrapper::getInstance(); 
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		// debugMessage($formvalues);
		
		/*$formvalues['name'] = "jojo hman";
		$formvalues['youremail'] = "hmanmstw@devmail.infomacorp.com";
		$formvalues['intromsg'] = "This is the test message";*/
		// debugMessage($formvalues);
		$dataarray = array(
			"name" => $formvalues['name'],
			"youremail" => $formvalues['youremail'],
			"message" => $formvalues['message'],
			"subject" => $formvalues['subject']
		);
		
		// $mails = "test1@devmail.infomacorp.com,test2@devmail.infomacorp.com";
		$mails = $formvalues['email'];
		$emailsarray = explode(",", str_replace(" ", "", $mails));
		
		$data = array();
		foreach ($emailsarray as $key => $value){
			$data[$key]['sendername'] = $dataarray['name'];
			$data[$key]['senderemail'] = $dataarray['youremail'];
			$data[$key]['email'] = $value;
			$data[$key]['message'] = $dataarray['message'];
			$data[$key]['subject'] = $dataarray['subject'];
		}
		// debugMessage($data);
		$person = new Person();
		// $person->tellFriendsNotification($data);
		if($person->tellFriendsAboutGANotification($data)){
			// save event
			$tellfriend = new TellFriend();
			$tellfriend->setFromName($formvalues['name']);
			$tellfriend->setFromEmail($formvalues['youremail']);
			$tellfriend->setEmails($formvalues['email']);
			$tellfriend->setMessage($formvalues['subject']."<br /><br />".$formvalues['message']);
			$tellfriend->save();
			
			$session->setVar(SUCCESS_MESSAGE, "Thank you for your interest in the GandaAncestry program.");
			
			$this->_redirect($this->view->baseUrl('contactus/tellfriend/result/success'));
			
		} else {
			$session->setVar(ERROR_MESSAGE, 'Sorry! An error occured in sending the message. Please try again later ');

			$this->_redirect($this->view->baseUrl('contactus/tellfriend/result/error'));
		}
		// exit();
	}
	public function reportAction() {
		
	}
	public function processreportAction() {
		$session = SessionWrapper::getInstance(); 
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		debugMessage($formvalues);
		
		$report = new Violation();
		$report->processPost($formvalues);
		debugMessage($report->toArray());
		
		if($report->hasError()){
			debugMessage('error is '.$report->getErrorStackAsString());
		}
		$report->setReportDate(date('Y-m-d'));
		$report->save();
		
		$report->reportViolationNotification();
		// exit();
		if($report->reportViolationNotification()){
			// after send events
			$session->setVar(SUCCESS_MESSAGE, "Thank you for your interest in the GandaAncestry program. We shall be getting back to you shortly.");
			
			$this->_redirect($this->view->baseUrl('contactus/report/result/success'));
		} else {
			$session->setVar(ERROR_MESSAGE, 'Sorry! An error occured in submitting the report. Please try again later ');
			
			$this->_redirect($this->view->baseUrl('contactus/report/result/error'));
		}
		// exit();
	}
}

