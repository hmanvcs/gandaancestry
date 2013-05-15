<?php

class Unsubscribe extends BaseRecord {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('unsubscribe');
		$this->hasColumn('name', 'string', 255);
		$this->hasColumn('email', 'string', 255);
		$this->hasColumn('message', 'string', 255);
		$this->hasColumn('dateunsubscribed', 'date');
	}
	/**
	 * Contructor method for custom functionality - add the fields to be marked as dates
	 */
	public function construct() {
		parent::construct();
		// specify the date columns
		$this->addDateFields(array("dateunsubscribed"));
	}	
}
?>