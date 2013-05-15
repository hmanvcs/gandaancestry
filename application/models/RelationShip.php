<?php

class RelationShip extends BaseRecord {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('relationship');
		$this->hasColumn('ename', 'string', 255);
		$this->hasColumn('edescription', 'string', 255);
		$this->hasColumn('lname', 'string', 255);
		$this->hasColumn('ldescription', 'string', 255);
		$this->hasColumn('addenabled', 'string', 255);
	}
}
?>