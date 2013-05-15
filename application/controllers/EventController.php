<?php

class EventController extends SecureController   {
	
	function createAction(){
		$session = SessionWrapper::getInstance();
		$formvalues = $this->_getAllParams();
		
		// exit();
		parent::createAction();
	}
	function viewAction(){
		if($this->_getParam('returntoperson') == 1){
			$session = SessionWrapper::getInstance();
			$session->setVar(SUCCESS_MESSAGE, translateKey("event_save_success"));
			
			$event = loadEntity('Event', decode($this->_getParam('id')));
			$this->_helper->redirector->gotoUrl($this->view->baseUrl('person/view/id/'.decode($event->getPersonID())."/tab/events"));
		}
	}
	function deleteAction(){		
		// disable rendering of the view and layout so that we can just echo the AJAX output 
	    $this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		//  $this->_translate = Zend_Registry::get("translate"); 
		$session = SessionWrapper::getInstance(); 
		
		// populate event to be deleted
		$event = new Event();
		$event = loadEntity('Event', decode($this->_getParam('id')));
		$personid = $event->getPersonID();
		
		if(removeEntity($event)){
			$session->setVar(SUCCESS_MESSAGE, translateKey("event_delete_success")); 
			$this->_helper->redirector->gotoUrl($this->view->baseUrl("person/view/id/".encode($personid)."/tab/events"));
		} else {
			debugMessage("An error occured in deleting the event. Please try again");
		}
		return false;
	}
}

