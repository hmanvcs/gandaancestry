<?php
class BillingController extends SecureController   {
	
	/**
	 * Get the name of the resource being accessed 
	 *
	 * @return String 
	 */
	function getActionforACL() {
		return ACTION_VIEW;
	}
	function upgradeAction(){
	}
	function renewAction(){
	}
	function paymenthistoryAction(){
	}
	function confirmcancellationAction(){
	}
	function successAction(){
	}
	function failureAction(){
	}
	function cancelAction(){
		// disable rendering of the view and layout so that we can just echo the AJAX output
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$user = new UserAccount();
		$user->populate($this->_getParam('userid'));
		$user->setIsActive(2);
		try {
			// update the user's details in the DB
			$user->save();
			// send email to the user and the admin
			$template = new EmailTemplate();
			$mail = getMailInstance();
			$default_sender = $mail->getDefaultFrom(); 
			$mail->addTo($user->getEmail()); // send email to the account owner
			$mail->addCc($default_sender['email']); // send email to the admin as well   
			$mail->setSubject("SUBSCRIPTION CANCELLATION: ".$user->getName()); 
			$template->assign('firstname', $user->getFirstName());
			$template->assign('message', $this->_getParam("message"));
			$mail->setBodyHtml($template->render('accountdeactivationconfirmation.phtml'));
			// send the email
			try {
				$mail->send();
			} catch (Exception $fail){
				debugMessage($fail->getMessage());
			}
			// add failed login to audit trail
    		$audit_values['transactiontype'] = USER_DEACTIVATE;
    		$audit_values['success'] = "Y";
    		$audit_values['transactiondetails'] = $user->getName()." has deactivated their account.";
    		$audit_values['executedby'] = $user->getID();
			$this->notify(new sfEvent($this, USER_DEACTIVATE, $audit_values));
		} catch (Exception $e){
			// log the error and handle appropriately
		}
		// log them out of the App
		$this->_helper->redirector->gotoUrl($this->view->baseUrl('user/logout/redirecturl/'.encode($this->view->baseUrl("user/accountcancellelationconfirmation"))));
	}
	/**
	 * 
	 * action to process credit card payments
	 */
	function creditcardpaymentAction(){
		$session = SessionWrapper::getInstance(); 
		$config = Zend_Registry::get("config"); 
		
		//debugMessage($this->_getAllParams());
		
		$paymenttype = $this->_getParam('payment_type');
		$PayPalConfig = array('Sandbox' => "sandbox", 'APIUsername' => API_USERNAME, 'APIPassword' => API_PASSWORD, 'APISignature' => API_SIGNATURE);
		$paypalPro = new Paypal($PayPalConfig);
		
		// check what type of payment method the author has selected then do the necessary processing
		if ($paymenttype == "paypal"){
			// do some stuff
			//debugMessage("Payment method is PayPal");
			
		} elseif ($paymenttype == "credit_card"){
			// do some stuff
			//debugMessage("Payment method is Credit/Debit Card");
			
			$DPFields = array(
								'paymentaction' => 'Sale', 						// How you want to obtain payment.  Authorization indidicates the payment is a basic auth subject to settlement with Auth & Capture.  Sale indicates that this is a final sale for which you are requesting payment.  Default is Sale.
								'ipaddress' => $this->_getParam('ipaddress'), 	// Required.  IP address of the payer's browser.
								'returnfmfdetails' => '1' 						// Flag to determine whether you want the results returned by FMF.  1 or 0.  Default is 0.
							);
			
			$CCDetails = array(
								'creditcardtype' => $this->_getParam('credit_card_type'), 	// Required. Type of credit card.  Visa, MasterCard, Discover, Amex, Maestro, Solo.  If Maestro or Solo, the currency code must be GBP.  In addition, either start date or issue number must be specified.
								'acct' => $this->_getParam('credit_card_number'), 			// Required.  Credit card number.  No spaces or punctuation.  
								'expdate' => $this->_getParam('expire_date_month').$this->_getParam('expire_date_year'), 						// Required.  Credit card expiration date.  Format is MMYYYY
								'cvv2' => $this->_getParam('cvv2Number'), 					// Requirements determined by your PayPal account settings.  Security digits for credit card.
								'startdate' => '', 											// Month and year that Maestro or Solo card was issued.  MMYYYY
								'issuenumber' => ''											// Issue number of Maestro or Solo card.  Two numeric digits max.
							);
		
			$PayerInfo = array(
								'email' => '', 								// Email address of payer.
								'payerid' => '', 							// Unique PayPal customer ID for payer.
								'payerstatus' => '', 						// Status of payer.  Values are verified or unverified
								'business' => '' 							// Payer's business name.
							);
							
			$PayerName = array(
								'salutation' => '', 							// Payer's salutation.  20 char max.
								'firstname' => $this->_getParam('first_name'), 	// Payer's first name.  25 char max.
								'middlename' => '', 							// Payer's middle name.  25 char max.
								'lastname' => $this->_getParam('last_name'), 	// Payer's last name.  25 char max.
								'suffix' => ''									// Payer's suffix.  12 char max.
							);
			
			$BillingAddress = array(
									'street' => $this->_getParam('address1'), 		// Required.  First street address.
									'street2' => $this->_getParam('address2'), 		// Second street address.
									'city' => $this->_getParam('city'), 			// Required.  Name of City.
									'state' => $this->_getParam('state'), 			// Required. Name of State or Province.
									'countrycode' => $this->_getParam('country'), 	// Required.  Country code.
									'zip' => $this->_getParam('postal_code'), 		// Required.  Postal code of payer.
									'phonenum' => '' 								// Phone Number of payer.  20 char max.
								);
								
			$PaymentDetails = array(
									'amt' => $this->_getParam('amount'), 	// Required.  Total amount of order, including shipping, handling, and tax.  
									'currencycode' => 'USD', 				// Required.  Three-letter currency code.  Default is USD.
									'itemamt' => '', 						// Required if you include itemized cart details. (L_AMTn, etc.)  Subtotal of items not including S&H, or tax.
									'shippingamt' => '', 					// Total shipping costs for the order.  If you specify shippingamt, you must also specify itemamt.
									'handlingamt' => '', 					// Total handling costs for the order.  If you specify handlingamt, you must also specify itemamt.
									'taxamt' => '', 						// Required if you specify itemized cart tax details. Sum of tax for all items on the order.  Total sales tax. 
									'desc' => 'Timbus Books Membership', 	// Description of the order the customer is purchasing.  127 char max.
									'custom' => $this->_getParam('id'), 	// Free-form field for your own use.  256 char max.
									'invnum' => '', 						// Your own invoice or tracking number
									'buttonsource' => '', 					// An ID code for use by 3rd party apps to identify transactions.
									'notifyurl' => ''						// URL for receiving Instant Payment Notifications.  This overrides what your profile is set to use.
								);
								
			$OrderItems = array();		
			
			$Item = array(
									'l_name' => '', 						// Item Name.  127 char max.
									'l_desc' => '', 						// Item description.  127 char max.
									'l_amt' => '', 							// Cost of individual item.
									'l_number' => '', 						// Item Number.  127 char max.
									'l_qty' => '', 							// Item quantity.  Must be any positive integer.  
									'l_taxamt' => '', 						// Item's sales tax amount.
									'l_ebayitemnumber' => '', 				// eBay auction number of item.
									'l_ebayitemauctiontxnid' => '', 		// eBay transaction ID of purchased item.
									'l_ebayitemorderid' => '' 				// eBay order ID for the item.
							);
							
			array_push($OrderItems, $Item);

			$PayPalRequestData = array(
									   'DPFields' => $DPFields, 
									   'CCDetails' => $CCDetails, 
									   'PayerName' => $PayerName, 
									   'BillingAddress' => $BillingAddress, 
									   'PaymentDetails' => $PaymentDetails, 
									   'OrderItems' => $OrderItems
									);
			$PayPalResult = $paypalPro->DoDirectPayment($PayPalRequestData);
			
			//debugMessage($PayPalResult);   
			
			$ack = strtoupper($PayPalResult["ACK"]);
			//debugMessage($PayPalResult);
			//debugMessage($ack);
			if($ack == "SUCCESS") {
				$payment_status = 'Completed';
			} else {
				$payment_status = 'Failed';
			}
			
			$transactionIDValid = isTransactionIDValid($PayPalResult["CORRELATIONID"]);	
			
			$queryForPaypalInfo = "INSERT INTO `payment` (`item_name`,`business`,`receiver_email`,`receiver_id`,`item_number`,`quantity`,`invoice`,`custom`,`memo`,`num_cart_items`,`payment_status`,`payment_gross`,`payment_fee`,`mc_gross`,`mc_fee`,`settle_amount`,`mc_currency`,`settle_currency`,`exchange_rate`,`pending_reason`,`reason_code`,`payment_date`,`txn_id`,`parent_txn_id`,`payer_email`,`payer_id`,`payer_business_name`,`payer_status`,`txn_type`,`payment_type`,`first_name`,`last_name`,`address_name`,`address_city`,`address_street`,`address_state`,`address_zip`,`address_country`,`address_status`,`notify_version`,`verify_sign`,`datecreated`,`startdate`,`enddate`) VALUES 
														 ('Timbus Books Membership',NULL,'".$config->paypal->receiveremail."','".$PayPalResult["PROFILEID"]."','TMB-001',0,'0','".decode($this->_getParam('id'))."',NULL,0,'".$payment_status."','".$this->_getParam('amount')."','0','".$this->_getParam('amount')."','0',0,'USD','USD',NULL,'other','other','".date('Y-m-d h:i:s')."','".$PayPalResult["CORRELATIONID"]."','0','','".$PayPalResult["PROFILEID"]."',NULL,'verified','subscr_payment','instant','".$this->_getParam('first_name')."','".$this->_getParam('last_name')."','".$this->_getParam('address1')."','".$this->_getParam('city')."',NULL,'".$this->_getParam('state')."','".$this->_getParam('postal_code')."','".$this->_getParam('country_code')."','unconfirmed','','','".date('Y-m-d h:i:s')."','".date('Y-m-d')."','".date('Y-m-d', strtotime('+1 month'))."')";
			//debugMessage($queryForPaypalInfo);
			$conn = Doctrine_Manager::connection(); 
			$result = $conn->execute($queryForPaypalInfo); 
			$paymentid = $conn->lastInsertId();
			if (!$result) {
				# an error occured, log it and send a message
				$this->_logger->err("Error Inserting Paypal Transaction in Database. Query :".$queryForPaypalInfo." - error ".mysql_error()); 
			}
			//exit();
		} else {
			debugMessage("There is no payment method selected");
		}
		
		if($ack == "SUCCESS") {
			$this->_setParam(SUCCESS_MESSAGE, $this->_translate->translate("billing_success_message")."<br />Transaction ID: ".$PayPalResult["TRANSACTIONID"]);
			$session->setVar(SUCCESS_MESSAGE, $this->_translate->translate("billing_success_message")."<br />Transaction ID: ".$PayPalResult["TRANSACTIONID"]);
			// execute the custom login i.e. update the expiry date in the useraccount table and set the start and end dates in the payment table
			executeCustomLogic(decode($this->_getParam('id')), $paymentid, $config->paypal->itemnumber);
			// redirect to the billing page
			$this->_helper->redirector->gotoUrl($this->view->baseUrl('billing'));
		} else {
			$Errors = $paypalPro->GetErrors($PayPalResult);
			$this->_setParam(ERROR_MESSAGE, $this->_translate->translate("billing_failure_message")."<br />"."<br />".$PayPalResult['L_LONGMESSAGE0']);
			$session->setVar(ERROR_MESSAGE, $this->_translate->translate("billing_failure_message")."<br />"."<br />".$PayPalResult['L_LONGMESSAGE0'].$paypalPro->DisplayErrors($Errors));
			$session->setVar(FORM_VALUES, $this->_getAllParams()); 
			$this->_helper->redirector->gotoUrl($this->view->baseUrl('billing/renew'));
		}
	}
}

