<?php

class FamilyTree extends BaseRecord {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('familytree');
		$this->hasColumn('treeid', 'integer', null);
		$this->hasColumn('focusid', 'integer', null);
		$this->hasColumn('relativeid', 'integer', null);
		$this->hasColumn('level', 'integer', null);
		$this->hasColumn('relationshipid', 'integer', null);
		$this->hasColumn('nestedpath', 'string', 50);
		$this->hasColumn('addrelationship', 'integer', null);
		$this->hasColumn('addedas', 'integer', null);
		$this->hasColumn('paternity', 'integer', null);
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
		
		$this->hasOne('Person as tree', 
								array(
									'local' => 'treeid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Person as focus', 
								array(
									'local' => 'focusid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Person as relative', 
								array(
									'local' => 'relativeid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Person as insertfocus', 
								array(
									'local' => 'addedas',
									'foreign' => 'id',
								)
						);
		$this->hasOne('RelationShip as relationship', 
								array(
									'local' => 'relationshipid',
									'foreign' => 'id',
								)
						);
	}
	/**
	 * Preprocess model data
	 */
	function processPost($formvalues){
		// set default values for integers, dates, decimals
		if(isArrayKeyAnEmptyString('treeid', $formvalues)){
			unset($formvalues['treeid']); 
		}
		if(isArrayKeyAnEmptyString('focusid', $formvalues)){
			unset($formvalues['focusid']); 
		}
		if(isArrayKeyAnEmptyString('relativeid', $formvalues)){
			unset($formvalues['relativeid']); 
		}
		if(isArrayKeyAnEmptyString('addrelationship', $formvalues)){
			unset($formvalues['addrelationship']); 
		}
		if(isArrayKeyAnEmptyString('addedas', $formvalues)){
			unset($formvalues['addedas']); 
		}
		if(isArrayKeyAnEmptyString('level', $formvalues)){
			unset($formvalues['level']); 
		}
		if(isArrayKeyAnEmptyString('relationshipid', $formvalues)){
			unset($formvalues['relationshipid']); 
		}
		if(isArrayKeyAnEmptyString('paternity', $formvalues)){
			$formvalues['paternity'] = NULL; 
		}
		parent::processPost($formvalues);
	}
	/**
     * Determine if a person is muganda
     * @return bool
     */
    /*function isImmediatePerson(){
    	return $this->getRelationShip()->getIsImmediate() == '1' ? true : false; 
    }*/	
	# determine if person is on paternity side of subscriber	
	function isOnFatherSide(){
		return $this->getPaternity() == 1 ? true : false;
	}
	function isOnMotherSide(){
		return $this->getPaternity() == 2 ? true : false;
	}
	function hasPaternity(){
		return !isEmptyString($this->getPaternity()) ? true : false;
	}
}
?>