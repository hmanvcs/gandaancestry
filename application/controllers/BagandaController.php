<?php

class BagandaController extends SecureController  {
	
	public function getResourceForACL(){
        return "Person Profile"; 
    }
    
	public function getActionforACL() {
		return ACTION_VIEW; 
    }
    
	public function indexAction() {
		
		$listcount = new LookupType();
    	$listcount->setName("LIST_ITEM_COUNT_OPTIONS");
    	$values = $listcount->getOptionValues(); 
    	asort($values, SORT_NUMERIC); 
    	$session = SessionWrapper::getInstance();
    	
    	$dropdown = new Zend_Form_Element_Select('itemcountperpage',
							array(
								'multiOptions' => $values, 
								'view' => new Zend_View(),
								'decorators' => array('ViewHelper'),
							     'class' => array('span1')
							)
						);
		if (isEmptyString($this->_getParam('itemcountperpage'))) {
			$dropdown->setValue(50);
			if(!isEmptyString($session->getVar('itemcountperpage'))){
				$dropdown->setValue($session->getVar('itemcountperpage'));
			}
		} else {
			$session->setVar('itemcountperpage', $this->_getParam('itemcountperpage'));
			$dropdown->setValue($session->getVar('itemcountperpage'));
		}  
	    $this->view->listcountdropdown = '<span class="pull-right">'.$this->_translate->translate("global_list_itemcount_dropdown").$dropdown->render().'</span>';
	}
	
	function indexsearchAction(){
		$this->_helper->redirector->gotoSimple("index", "baganda", 
    											$this->getRequest()->getModuleName(),
    											array_remove_empty(array_merge_maintain_keys($this->_getAllParams(), $this->getRequest()->getQuery())));
	}
	
	function homesearchAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		$session = SessionWrapper::getInstance(); 
		
		// debugMessage($formvalues);
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		if(!isArrayKeyAnEmptyString('searchterm', $formvalues)){
			$this->_helper->redirector->gotoUrl($baseUrl.'/baganda/index/searchterm/'.$formvalues['searchterm']);
		}
		$this->_helper->redirector->gotoUrl($baseUrl.'/baganda/index/firstname/'.$formvalues['firstname'].'/lastname/'.$formvalues['lastname']); 
	}
}

