<?php
class ReportController extends SecureController   {
	
	/**
	 * Get the name of the resource being accessed 
	 *
	 * @return String 
	 */
	function getActionforACL() {
		return ACTION_VIEW;
	}
	/**
	 * @see SecureController::getResourceForACL()
	 * 
	 * Return the Application Settings since we need to make the url more friendly 
	 *
	 * @return String
	 */
	function getResourceForACL() {
		/*$resource = strtolower($this->getRequest()->getActionName()); 
		if ($resource == "subscription") {
			return "Subscriptions Report";
		}
		return parent::getResourceForACL();*/
		return "Report"; 
	}	
}
