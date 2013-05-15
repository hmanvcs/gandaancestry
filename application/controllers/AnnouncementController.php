<?php

class AnnouncementController extends IndexController  {
	
	function indexAction() {
		$this->_helper->layout->disableLayout();
		// $this->_helper->viewRenderer->setNoRender(TRUE);
	}
	function applyAction() {
		
	}
	
	function processapplyAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		$session = SessionWrapper::getInstance();
		
		$dataarray = array(
			"firstname" => $formvalues['firstname'],
			"lastname" => $formvalues['lastname'],
			"clanname" => $formvalues['clanname'],
			"clanid" => $formvalues['clanid'],
			"email"=> $formvalues['email'],
			"createdby" => 1,
			"type" => 1,
			"statusflag" => 2/*,
			"betaentry" => array(0 => array("ssiga" =>$formvalues['ssiga']))*/
		);
		// debugMessage($formvalues);
		
		$person = new Person();
		$person->processPost($dataarray);
		# validate already exists
		if($person->hasAlreadyApplied()){
			$dupperson = $person->findByEmail($formvalues['email']);
			$id = $dupperson->getID();
			
			$session->setVar("dup_id", $id);
			$session->setVar(ERROR_MESSAGE, 'Something went wrong!');
			$session->setVar(FORM_VALUES, $this->_getAllParams());
			
			$this->_redirect($this->view->baseUrl('index/join/id/'.encode($id).'/result/error'));
		}
		//debugMessage($person->toArray());
		//debugMessage($person->getErrorStackAsString());exit();
		if($person->isValid()){
			$person->save();
			$person->sendBetaConfirmationNotification();
			$person->sendBetaAdminNotification();
			$session->setVar(SUCCESS_MESSAGE, "Application successfully submitted");
			$this->_redirect($this->view->baseUrl('index/join/result/success'));
		} else {
			$session->setVar(ERROR_MESSAGE, 'Something went wrong! <br /><br />'.$person->getErrorStackAsString());
			$session->setVar(FORM_VALUES, $this->_getAllParams());
			$this->_redirect($this->view->baseUrl('index/join/result/error'));
		}
	}
	
	function tellfriendAction() {
		
	}
	function processtellfriendAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		$session = SessionWrapper::getInstance();
		
		/*$formvalues['name'] = "jojo hman";
		$formvalues['youremail'] = "hmanmstw@devmail.infomacorp.com";
		$formvalues['intromsg'] = "This is the test message";*/
		// debugMessage($formvalues);
		$dataarray = array(
			"name" => $formvalues['name'],
			"youremail" => $formvalues['youremail'],
			"intromsg" => $formvalues['intromsg']
		);
		
		// $mails = "test1@devmail.infomacorp.com,test2@devmail.infomacorp.com";
		$mails = $formvalues['email'];
		$emailsarray = explode(",", str_replace(" ", "", $mails));
		
		$data = array();
		foreach ($emailsarray as $key => $value){
			$data[$key]['sendername'] = $dataarray['name'];
			$data[$key]['senderemail'] = $dataarray['youremail'];
			$data[$key]['email'] = $value;
			$data[$key]['intromsg'] = $dataarray['intromsg'];
		}
		// debugMessage($data);
		$person = new Person();
		// $person->tellFriendsNotification($data);
		if($person->tellFriendsNotification($data)){
			// save event
			$tellfriend = new TellFriend();
			$tellfriend->setFromName($formvalues['name']);
			$tellfriend->setFromEmail($formvalues['youremail']);
			$tellfriend->setEmails($formvalues['email']);
			$tellfriend->setMessage($formvalues['intromsg']);
			$tellfriend->save();
			
			$session->setVar(SUCCESS_MESSAGE, "Application successfully submitted");
			$this->_redirect($this->view->baseUrl('announcement/tellfriend/success/true'));
		} else {
			$session->setVar(ERROR_MESSAGE, 'Sorry! Currently unavailable. Please try again later ');
			$this->_redirect($this->view->baseUrl('announcement/tellfriend/error/true'));
		}
	}
	function unsubscribeAction() {
	}
	function processunsubscribeAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		$session = SessionWrapper::getInstance();
		
		$person = new Person();
		if($person->unsubscribeNotification($formvalues)){
			// save the details to the DB
			$unsubscribe = new Unsubscribe();
			$unsubscribe->setName($formvalues['name']);
			$unsubscribe->setEmail($formvalues['youremail']);
			$unsubscribe->setMessage($formvalues['intromsg']);
			$unsubscribe->setDateUnSubscribed(date('Y-m-d h:i:s'));
			//debugMessage($unsubscribe->toArray());
			$unsubscribe->save();
		
			$session->setVar(SUCCESS_MESSAGE, "Application successfully submitted");
			
			$this->_redirect($this->view->baseUrl('announcement/unsubscribe/success/true'));
		} else {
			$session->setVar(ERROR_MESSAGE, 'Sorry! Could not process your request. Please try again later ');
			
			$this->_redirect($this->view->baseUrl('announcement/unsubscribe/error/true'));
		}
	}
}

