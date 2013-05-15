<?php

class Category extends BaseRecord {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('category');
		$this->hasColumn('name', 'string', 255);
		
	}
	/**
	 * Contructor method for custom initialization
	 */
	public function construct() {
		parent::construct();
		
	}
	/**
	 * Model relationships
	 */
	public function setUp() {
		parent::setUp();
		
		$this->actAs('NestedSet');
	}
	/**
	 * Preprocess model data
	 */
	function processPost($formvalues){
		
		
		parent::processPost($formvalues);
	}	
}
?>