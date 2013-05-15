<?php

class MatrixPath extends BaseRecord {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('matrixpath');
		$this->hasColumn('focusrel', 'integer', null);
		$this->hasColumn('relativerel', 'integer', null);
		$this->hasColumn('value', 'integer', null);
		$this->hasColumn('usegender', 'integer', null);
		$this->hasColumn('malevalue', 'integer', null);
		$this->hasColumn('femalevalue', 'integer', null);
		$this->hasColumn('usepaternity', 'integer', null);
		$this->hasColumn('paternalvalue', 'integer', null);
		$this->hasColumn('maternalvalue', 'integer', null);
		$this->hasColumn('level', 'integer', null);
		$this->hasColumn('altpath', 'string', 255);
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
		
		$this->hasOne('RelationShip as focusrelationship', 
								array(
									'local' => 'focusrel',
									'foreign' => 'id'
								)
						);
		$this->hasOne('RelationShip as relrelationship', 
								array(
									'local' => 'relativerel',
									'foreign' => 'id'
								)
						);
		$this->hasOne('RelationShip as valuerelationship', 
								array(
									'local' => 'value',
									'foreign' => 'id'
								)
						);
		$this->hasOne('RelationShip as malerelationship', 
								array(
									'local' => 'malevalue',
									'foreign' => 'id'
								)
						);
		$this->hasOne('RelationShip as femalerelationship', 
								array(
									'local' => 'femalevalue',
									'foreign' => 'id'
								)
						);
		$this->hasOne('RelationShip as prelationship', 
								array(
									'local' => 'paternalvalue',
									'foreign' => 'id'
								)
						);
		$this->hasOne('RelationShip as mrelationship', 
								array(
									'local' => 'maternalvalue',
									'foreign' => 'id'
								)
						);
	}
	/**
	 * Preprocess model data
	 */
	function processPost($formvalues){
		// set default values for integers, dates, decimals
		if(isArrayKeyAnEmptyString('focusrel', $formvalues)){
			unset($formvalues['focusrel']); 
		}
		if(isArrayKeyAnEmptyString('relativerel', $formvalues)){
			unset($formvalues['relativerel']); 
		}
		if(isArrayKeyAnEmptyString('value', $formvalues)){
			unset($formvalues['value']); 
		}
		if(isArrayKeyAnEmptyString('malevalue', $formvalues)){
			unset($formvalues['malevalue']); 
		}
		if(isArrayKeyAnEmptyString('femalevalue', $formvalues)){
			unset($formvalues['femalevalue']); 
		}
		if(isArrayKeyAnEmptyString('level', $formvalues)){
			unset($formvalues['level']); 
		}
		if(isArrayKeyAnEmptyString('usepaternity', $formvalues)){
			unset($formvalues['usepaternity']); 
		}
		if(isArrayKeyAnEmptyString('paternalvalue', $formvalues)){
			unset($formvalues['paternalvalue']); 
		}
		if(isArrayKeyAnEmptyString('maternalvalue', $formvalues)){
			unset($formvalues['maternalvalue']); 
		}
		parent::processPost($formvalues);
	}
	# determine tree using focus
	function getPathRelationship($focusrel, $relativerel) {
		// debugMessage($relativeid);
		$q = Doctrine_Query::create()->from('MatrixPath mp')->where("mp.focusrel = '".$focusrel."' AND mp.relativerel = '".$relativerel."' ");
		$result = $q->fetchOne();
		// debugMessage($result->toArray());
		return $result;
	}
	function determineUsingPaternity(){
		return $this->getUsepaternity() == 1 ? true : false;
	}
}
?>