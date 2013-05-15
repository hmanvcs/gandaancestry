<?php

class ClansController extends IndexController  {
	
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
			$dropdown->setValue('ALL');
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
		$this->_helper->redirector->gotoSimple("index", "clans", 
    											$this->getRequest()->getModuleName(),
    											array_remove_empty(array_merge_maintain_keys($this->_getAllParams(), $this->getRequest()->getQuery())));
	}
}

