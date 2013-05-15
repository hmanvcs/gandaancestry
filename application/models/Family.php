<?php

class Family extends BaseEntity {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('family');
		$this->hasColumn('fatherid', 'integer', null);
		$this->hasColumn('motherid', 'integer', null);
		$this->hasColumn('addedbyid', 'integer', null);
		$this->hasColumn('partnerstatus', 'integer', null, array('default' => '1'));
		$this->hasColumn('parentid', 'integer', null);	
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
		
		$this->hasOne('Person as father', 
								array(
									'local' => 'fatherid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Person as mother', 
								array(
									'local' => 'motherid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Person as addedby', 
								array(
									'local' => 'addedbyid',
									'foreign' => 'id',
								)
						);
		$this->hasOne('Family as parent', 
								array(
									'local' => 'parentid',
									'foreign' => 'id',
								)
						);
	}
	/**
	 * Preprocess model data
	 */
	function processPost($formvalues){
		// set default values for integers, dates, decimals
		if(isArrayKeyAnEmptyString('fatherid', $formvalues)){
			unset($formvalues['fatherid']); 
		}
		if(isArrayKeyAnEmptyString('motherid', $formvalues)){
			unset($formvalues['motherid']); 
		}
		if(isArrayKeyAnEmptyString('addedbyid', $formvalues)){
			unset($formvalues['addedbyid']); 
		}
		if(isArrayKeyAnEmptyString('partnerstatus', $formvalues)){
			unset($formvalues['partnerstatus']); 
		}
		if(isArrayKeyAnEmptyString('parentid', $formvalues)){
			unset($formvalues['parentid']); 
		}		
		parent::processPost($formvalues);
	}
	# Return the family name. By default use the lastname of the father
	function getFamilyName() {
		$name = $this->getName();
		if(isEmptyString($this->getName())){
			if(!isEmptyString($this->getFatherID())){
				$name = $this->getFather()->getLastName();
			}
			if(isEmptyString($this->getFatherID()) && !isEmptyString($this->getMotherID())){
				$name = $this->getMother()->getLastName();
			}
		}
	    return $name;
	}
	/**
     * Determine the sons for family
     * @return collection the persons
     */
    function getChildren() {
    	$q = Doctrine_Query::create()->from('Person p')->where("p.familyid = ".$this->getID()." ");
		$result = $q->execute();
		return $result;
    }
    # determine if family has children
    function hasChildren() {
    	$children = $this->getChildren();
    	if($children->count() == 0){
    		return false;
    	}
    	return true;
    }
    # determine if family has father
    function hasFather() {
    	return !isEmptyString($this->getFatherID()) ? true : false;
    }
    # determine if family has mother
 	function hasMother() {
    	return !isEmptyString($this->getMotherID()) ? true : false;
    }
	# determine if family has atleast one partner
 	function hasAtleastOnePartner() {
    	return (!isEmptyString($this->getFatherID()) || !isEmptyString($this->getMotherID())) ? true : false;
    }
	# determine if family has both partners
 	function hasBothPartners() {
    	return (!isEmptyString($this->getFatherID()) && !isEmptyString($this->getMotherID())) ? true : false;
    }
	# determine if family is a single father family
 	function isSingleFather() {
    	return (!isEmptyString($this->getFatherID()) && isEmptyString($this->getMotherID())) ? true : false;
    }
	# determine if family is a single mother family
 	function isSingleMother() {
    	return (isEmptyString($this->getFatherID()) && !isEmptyString($this->getMotherID())) ? true : false;
    }
    # determine if family is valid or not
    function isValidFamily(){
    	$valid = false;
    	if($this->hasBothPartners()){
    		$valid = true;
    	}
    	if($this->hasChildren()){
    		$valid = true;
    	}
    	return $valid;
    }
    # determine if family is deletable. has no children
    function isDeletable() {
    	return !$this->isValidFamily() ? true : false;
    }
    # determine if family has atleast one parent
    function hasOneParent() {
    	$result = false;
    	if($this->hasFather() || $this->hasMother()){
    		$result = true;
    	}
    	return $result;
    }
    # determine list of children
  	function getListOfChildren() {
  		$list = "None";
  		$listarray = array();
  		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
  		if($this->hasChildren()){
  			$children = $this->getChildren();
  			foreach ($children as $child){
  				$listarray[$child->getID()] = '<a href="'.$baseUrl.'/person/view/id/'.encode($child->getID()).'" title="'.$child->getName().'">'.$child->getName().'</a>';
  			}
  			if(count($listarray) > 0){
  				$list = createHTMLListFromArray($listarray, 'custom_margin');	
  			}
  		}
  		return $list;
  	}
  	# determine families related to this family immediately
  	function getRelatedFamilies($treeid) {
  		$q = Doctrine_Query::create()->from('Family f')->where("(f.addedbyid = '".$treeid."' AND f.id <> '".$this->getID()."') ")->limit(3)->orderby('rand()');
		$result = $q->execute();
		return $result;
  	}
}
?>