<?php
class Donation extends BaseRecord {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		$this->setTableName('donation');
		$this->hasColumn('firstname', 'string', 255, array( 'notnull' => true, 'notblank' => true));
		$this->hasColumn('lastname', 'string', 255, array('notnull' => true, 'notblank' => true));
		$this->hasColumn('othernames', 'string', 255);	
		$this->hasColumn('addressline1', 'string', 255);
		$this->hasColumn('addressline2', 'string', 255);
		$this->hasColumn('city', 'string', 50);	
		$this->hasColumn('state', 'string', 50);
		$this->hasColumn('country', 'string', 2, array('default' => 'UG'));
		$this->hasColumn('email', 'string', 50, array('notnull' => true, 'notblank' => true));
		$this->hasColumn('donationamount', 'decimal', array( 'notnull' => true, 'notblank' => true));
		$this->hasColumn('donationid', 'string', 255);
		$this->hasColumn('donationname', 'string', 255);
		$this->hasColumn('phone', 'string', 255);
		$this->hasColumn('zipcode', 'string', 255);
	}
	/**
	 * Contructor method for custom initialization
	 */
	public function construct() {
		parent::construct();
		
	}
	/**
	 * Send an email with a link to activate the members' account
	 */
	function sendThankYouEmail() {
		$template = new EmailTemplate(); 
		$mail = getMailInstance(); 

		// assign values
		$template->assign('firstname', $this->getFirstName());
		$template->assign('amount', $this->getDonationAmount());
		// configure base stuff
		$mail->addTo($this->getEmail(), $this->geFirstName());
		// set the send of the email address
		$mail->setFrom($this->config->notification->emailmessagesender, $this->translate->_('useraccount_email_notificationsender'));
		
		$mail->setSubject($this->translate->_('donation_thank_you_email'));
		// render the view as the body of the email
		$mail->setBodyHtml($template->render('donationthankyouemail.phtml'));
		// debugMessage($template->render('accountdeactivationconfirmation.phtml')); // exit();
		$mail->send();
		
		return true;
   }
}
?>