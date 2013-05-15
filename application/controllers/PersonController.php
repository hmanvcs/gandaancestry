<?php
class PersonController extends SecureController   {

    public function addAction(){}
    
    public function getActionforACL() {
        $action = strtolower($this->getRequest()->getActionName()); 
		if($action == "add" || $action == "other" || $action == "processother") {
			return ACTION_CREATE; 
		}
    	if(
    	$action == "croppicture" || $action == "updaterelation" || $action == "search" || $action == "addsuccess"  || 
    	$action == "invite" || $action == "inviteone" || $action == "inviteoneconfirm" || $action == "invitemany" || 
    	$action == "invitemanyconfirm" || $action == "invitefriends" || $action == "invitefriendsconfirm" || 
    	$action == "clan" || $action == "tree" || $action == "picture" || $action == "processpicture" || 
    	$action == "autosearch" || $action == "list2"  || $action == "list2search" || $action == "relative" ||
    	$action == "delete" || $action == "deactivate" || $action == "privacy" || $action == "resetprivacy" || 
    	$action == "reset" || $action == "updatefamily" || $action == "changeemail" || $action == "demosuccess" 
    	|| $action == "demoupdate"
    	) {
			return ACTION_VIEW; 
		}
		return parent::getActionforACL(); 
    }
    
    public function getResourceForACL(){
        return "Person Profile"; 
    }
    
	public function createAction() {
		$session = SessionWrapper::getInstance(); 
		$personid = $session->getVar('personid');
		$formvalues = $this->_getAllParams();
		
		$lookup = new LookupTypeValue();
		$lookup->populate(92);
		if($lookup->getLookupTypeValue() == 1){
			$this->_helper->redirector->gotoUrl(decode($this->_getParam(URL_SUCCESS)));
		}
    	parent::createAction();
    }
    
    public function editAction() {
 		$session = SessionWrapper::getInstance();   	
    	$this->_setParam("action", ACTION_EDIT);
		$formvalues = $this->_getAllParams();
    	// debugMessage(DEMO_FLAG);
    	
		$lookup = new LookupTypeValue();
		$lookup->populate(92);
		if($lookup->getLookupTypeValue() == 1){
    		$session->setVar("demo_success", "This is a demonstration of a successfull update to the profile. Your changes have not actualy been saved as this is a demo account.");
    		$this->_helper->redirector->gotoUrl(decode($this->_getParam(URL_SUCCESS)));
		}
		//exit();
    	$this->createAction();
    }
    
	public function addsuccessAction(){
		$session = SessionWrapper::getInstance(); 
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$lookup = new LookupTypeValue();
		$lookup->populate(92);
		if($lookup->getLookupTypeValue() == 1){
			$session->setVar("demo_success", "Successfully added relative to your tree. However, your tree has not been actualy updated as this is only a demonstration account.");
		} else {
			$session->setVar(SUCCESS_MESSAGE, $this->_translate->translate("person_add_success"));
		}
		
    }
	
	public function demosuccessAction(){
		$session = SessionWrapper::getInstance(); 
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$session->setVar(SUCCESS_MESSAGE, "Successfully Added. However, this is demonstration account!");
    	// echo '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>'.$this->_translate->translate("person_invite_success").'</div>';
    }
    
	public function updatefamilyAction(){
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		$session = SessionWrapper::getInstance(); 
		$personid = $session->getVar('personid');
		
		$formvalues = $this->_getAllParams();
		// debugMessage($formvalues);
		
		$person = new Person();
    	$person->populate($formvalues['focusid']);
    	$oldfamily = $person->getFamilyID();
    	$rel = $this->_getParam('relationshiptype');
    	$person->setFamilyID($formvalues['childfamilyid']);
    	$person->save();
    	// debugMessage($person->toArray());
    	# cleanup previous family
    	if(!isEmptyString($oldfamily)){
    		$family = new Family();
    		$family->populate($oldfamily);
    		if($family->isDeletable()){
    			$family->delete();
    		}
    	}
    	
    	// check if mother of subscriber is being updated
    	if($personid == $person->getID()){
    		if($rel == 2){
    			$mother = $person->getFamily()->getMother();
    			$ftree = $mother->getTreeRelationship($person->getID());
    			$ftree->setRelationShipID($rel);
    			$ftree->save();
    			// debugMessage($ftree->toArray());
    		}
    	}
    	
    	// check if father of subscriber is being updated
    	// exit();
		$session->setVar(SUCCESS_MESSAGE, "Updated successfully");
    }
    
    function searchAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		$session = SessionWrapper::getInstance(); 
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		
		// debugMessage($formvalues); 
		$successpath = $baseUrl.'/person/list/range/1/';
		$area = $formvalues['searcharea'];
		$term = $formvalues['gsearchterm'];
		$session->setVar('gsearchterm', $term);
		switch($area) {
			case '1':
				$successpath = $baseUrl.'/baganda/index/searchterm/'.$term;
				$session->setVar('currentsearcharea', 1);
				break;
			case '2':
				$successpath = $baseUrl.'/family/list/range/1/searchterm/'.$term;
				$session->setVar('currentsearcharea', 2);
				break;
			case '3':
				$successpath = $baseUrl.'/clan/list/searchterm/'.$term;
				$session->setVar('currentsearcharea', 3);
				break;
			case '4':
				$successpath = $baseUrl.'/ssiga/list/searchterm/'.$term;
				$session->setVar('currentsearcharea', 4);
				break;
			default:
				$successpath = $baseUrl.'/baganda/index/searchterm/'.$term;
				$session->setVar('currentsearcharea', '');
				break;
		}
		// debugMessage($successpath);
		// exit();
		$this->_helper->redirector->gotoUrl($successpath); 
	}
	
    public function otherAction(){
		
    }
    
	public function processotherAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		$session = SessionWrapper::getInstance(); 	
		$formvalues = $this->_getAllParams();
		// debugMessage($formvalues);
		
		$this->_setParam('entityname', 'OrganisationContact');
		$postarray = array(
			"id"=>"",
			"organisationid"=>$formvalues['clanid'],
			"person" => array(
				"firstname"=>$formvalues['firstname'],
				"lastname"=>$formvalues['lastname'],
				"clanname"=>$formvalues['clanname'],
				"type"=>$formvalues['type'],
				"clanid"=>$formvalues['clanid'],
				"gender"=>$formvalues['gender'],
				"lifestatus"=>$formvalues['lifestatus'],
				"email"=>$formvalues['email']
			),
			"role"=>$formvalues['role'],
			"createdby"=>$session->getVar('userid')
		);
		
		//debugMessage($postarray);
		$orgcontact = new OrganisationContact();
		$orgcontact->processPost($postarray);
		
		if($orgcontact->save()){
			// success action
			$this->_redirect($this->view->baseUrl('person/addsuccess'));
		}
		return false;
    }
	public function pictureAction() {}
	
	public function processpictureAction() {
		// disable rendering of the view and layout so that we can just echo the AJAX output 
	    $this->_helper->layout->disableLayout();
		// $this->_helper->viewRenderer->setNoRender(TRUE);
		
	    $session = SessionWrapper::getInstance(); 	
	    $config = Zend_Registry::get("config");
	    $this->_translate = Zend_Registry::get("translate"); 
		
	    $formvalues = $this->_getAllParams();
	
		$lookup = new LookupTypeValue();
		$lookup->populate(92);
		if($lookup->getLookupTypeValue() == 1){
    		$session->setVar("demo_success", "This is a demonstration of a successfully update of the profile. Your changes have not actualy been committed as this is a demo account.");
    		$this->_helper->redirector->gotoUrl(decode($this->_getParam(URL_SUCCESS)));
		}
		
		// debugMessage($this->_getAllParams());
		$person = new Person();
		$person->populate(decode($this->_getParam('id')));
		
		// only upload a file if the attachment field is specified		
		$upload = new Zend_File_Transfer();
		// set the file size in bytes
		$upload->setOptions(array('useByteString' => false));
		
		// Limit the extensions to the specified file extensions
		$upload->addValidator('Extension', false, $config->profilephoto->allowedformats);
	 	$upload->addValidator('Size', false, $config->profilephoto->maximumfilesize);
		
		// base path for profile pictures
	 	$destination_path = APPLICATION_PATH.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."user_";
			
		// determine if user has destination avatar folder. Else user is editing there picture
		if(!is_dir($destination_path.decode($this->_getParam('id')))){
			// no folder exits. Create the folder
			mkdir($destination_path.decode($this->_getParam('id')), 0755);
		} 
		
		// set the destination path for the image
		$profilefolder = decode($this->_getParam('id'));
		$destination_path = $destination_path.$profilefolder.DIRECTORY_SEPARATOR."avatar";
		if(!is_dir($destination_path)){
			mkdir($destination_path, 0755);
		}
		// create archive folder for each user
		$archivefolder = $destination_path.DIRECTORY_SEPARATOR."archive";
		if(!is_dir($archivefolder)){
			mkdir($archivefolder, 0755);
		}
		$oldfilename = $person->getPhoto();
		//debugMessage($destination_path); 
		$upload->setDestination($destination_path);
		
		// the profile image info before upload
		$file = $upload->getFileInfo('profileimage');
		$uploadedext = findExtension($file['profileimage']['name']);
		$currenttime = mktime();
		$currenttime_file = $currenttime.'.'.$uploadedext;
		// debugMessage($file);
		
		$thefilename = $destination_path.DIRECTORY_SEPARATOR.'base_'.$currenttime_file;
		$updateablefile = $destination_path.DIRECTORY_SEPARATOR.'base_'.$currenttime;
		
		// rename the base image file 
		$upload->addFilter('Rename',  array('target' => $thefilename, 'overwrite' => true));		
		
		// process the file upload
		if($upload->receive()){
			//debugMessage('Completed');
			$file = $upload->getFileInfo('profileimage');
			// debugMessage($file);
			
			$basefile = $thefilename;
			// convert png to jpg
			if(in_array(strtolower($uploadedext), array('png','gif'))){
				ak_img_convert_to_jpg($thefilename, $updateablefile.'.jpg', $uploadedext);
				unlink($thefilename);
				$basefile = $updateablefile.'.jpg';
			}
			
			// new profilenames
			$newlargefilename = "large_".$currenttime_file;
			// generate and save thumbnails for sizes 250, 125 and 50 pixels
			resizeImage($basefile, $destination_path.DIRECTORY_SEPARATOR.$newlargefilename, 400);
			
			// update the useraccount with the new profile images
			try {
				$person->setPhoto($currenttime_file);
				$person->save();
				
				// check if user already has profile picture and archive it
				$ftimestamp = current(explode('.', $person->getPhoto()));
				$allfiles = glob($destination_path.DIRECTORY_SEPARATOR.'*.jpg');
				$currentfiles = glob($destination_path.DIRECTORY_SEPARATOR.'*'.$ftimestamp.'*.jpg');
				// debugMessage($currentfiles);
				$deletearray = array();
				foreach ($allfiles as $value) {
					if(!in_array($value, $currentfiles)){
						$deletearray[] = $value;
					}
				}
				// debugMessage($deletearray);
				if(count($deletearray) > 0){
					foreach ($deletearray as $afile){
						$afile_filename = basename($afile);
						rename($afile, $archivefolder.DIRECTORY_SEPARATOR.$afile_filename);
					}
				}
				
				// exit();
				$session->setVar(SUCCESS_MESSAGE, $this->_translate->translate("person_update_success"));
				$this->_helper->redirector->gotoUrl($this->view->baseUrl("person/picture/id/".encode($person->getID()).'/crop/1'));
			} catch (Exception $e) {
				$session->setVar(ERROR_MESSAGE, $e->getMessage());
				$session->setVar(FORM_VALUES, $this->_getAllParams());
				$this->_helper->redirector->gotoUrl($this->view->baseUrl('person/picture/id/'.encode($person->getID())));
			}
		} else {
			// debugMessage($upload->getMessages());
			$uploaderrors = $upload->getMessages();
			$customerrors = array();
			if(!isArrayKeyAnEmptyString('fileUploadErrorNoFile', $uploaderrors)){
				$customerrors['fileUploadErrorNoFile'] = "Please browse for image on computer";
			}
			if(!isArrayKeyAnEmptyString('fileExtensionFalse', $uploaderrors)){
				$custom_exterr = sprintf($this->_translate->translate('upload_invalid_ext_error'), $config->profilephoto->allowedformats);
				$customerrors['fileExtensionFalse'] = $custom_exterr;
			}
			if(!isArrayKeyAnEmptyString('fileUploadErrorIniSize', $uploaderrors)){
				$custom_exterr = sprintf($this->_translate->translate('upload_invalid_size_error'), formatBytes($config->profilephoto->maximumfilesize,0));
				$customerrors['fileUploadErrorIniSize'] = $custom_exterr;
			}
			if(!isArrayKeyAnEmptyString('fileSizeTooBig', $uploaderrors)){
				$custom_exterr = sprintf($this->_translate->translate('upload_invalid_size_error'), formatBytes($config->profilephoto->maximumfilesize,0));
				$customerrors['fileSizeTooBig'] = $custom_exterr;
			}
			$session->setVar(ERROR_MESSAGE, 'The following errors occured <ul><li>'.implode('</li><li>', $customerrors).'</li></ul>');
			$session->setVar(FORM_VALUES, $this->_getAllParams()); 
			// debugMessage($customerrors);
			
			$this->_helper->redirector->gotoUrl($this->view->baseUrl('person/picture/id/'.encode($person->getID())));
		}
		// exit();
	}
	
	function croppictureAction(){
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		$person = new Person();
		$person->populate(decode($formvalues['id']));
		$userfolder = $person->getID();
		//debugMessage($formvalues); exit();
		//debugMessage($person->toArray());
		
		$oldfile = "large_".$person->getPhoto();
		$base = APPLICATION_PATH.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.PUBLICFOLDER.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'user_'.$userfolder.''.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR; 
		$src = $base.$oldfile;
		//debugMessage($src);
		
		//debugMessage($person->getLargePicturePath());
		$currenttime = mktime();
		$currenttime_file = $currenttime.'.jpg';
		$newlargefilename = $base."large_".$currenttime_file;
		$newmediumfilename = $base."medium_".$currenttime_file;
		$newthumbnailfilename = $base."thumbnail_".$currenttime_file;
		$newsmallfilename = $base."small_".$currenttime_file;
		
		$image = WideImage::load($src);
		$cropped1 = $image->crop($formvalues['x1'], $formvalues['y1'], $formvalues['w'], $formvalues['h']);
		$resized_1 = $cropped1->resize(300, 300, 'fill');
		$resized_1->saveToFile($newlargefilename);
		
		//$image2 = WideImage::load($src);
		$cropped2 = $image->crop($formvalues['x1'], $formvalues['y1'], $formvalues['w'], $formvalues['h']);
		$resized_2 = $cropped2->resize(165, 165, 'fill');
		$resized_2->saveToFile($newmediumfilename);
		
		//$image3 = WideImage::load($src);
		$cropped3 = $image->crop($formvalues['x1'], $formvalues['y1'], $formvalues['w'], $formvalues['h']);
		$resized_3 = $cropped3->resize(65, 75, 'fill');
		$resized_3->saveToFile($newthumbnailfilename);
		
		//$image4 = WideImage::load($src);
		$cropped4 = $image->crop($formvalues['x1'], $formvalues['y1'], $formvalues['w'], $formvalues['h']);
		$resized_4 = $cropped4->resize(45, 45, 'fill');
		$resized_4->saveToFile($newsmallfilename);
		
		$person->setPhoto($currenttime_file);
		$person->save();
		
		// check if user already has profile picture and archive it
		$ftimestamp = current(explode('.', $person->getPhoto()));
		$allfiles = glob($base.DIRECTORY_SEPARATOR.'*.jpg');
		$currentfiles = glob($base.DIRECTORY_SEPARATOR.'*'.$ftimestamp.'*.jpg');
		// debugMessage($currentfiles);
		$deletearray = array();
		foreach ($allfiles as $value) {
			if(!in_array($value, $currentfiles)){
				$deletearray[] = $value;
			}
		}
		// debugMessage($deletearray);
		if(count($deletearray) > 0){
			foreach ($deletearray as $afile){
				$afile_filename = basename($afile);
				rename($afile, $base.DIRECTORY_SEPARATOR.'archive'.DIRECTORY_SEPARATOR.$afile_filename);
			}
		}
				
		$this->_helper->redirector->gotoUrl($this->view->baseUrl('person/view/id/'.encode($person->getID())));
		// exit();
    }
    
	public function inviteAction(){
    	
    }
    
    public function inviteoneAction(){
    	$session = SessionWrapper::getInstance(); 
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		// $id = "21";
		// $mail = "gsade@devmail.infomacorp.com";
		$id = $this->_getParam('id');
		$mail = $this->_getParam('email');
		$person = new Person();
		$person->populate($id);
		// set the new email
		$person->setEmail($mail);
		// debugMessage($person->toArray());
		
    	if($person->inviteOne($person->getOwner()->getPersonID())){
			// success action
			$this->_redirect($this->view->baseUrl('person/inviteoneconfirm/id/'.encode($person->getID())));
		}
		return false;
    }
    
	public function inviteoneconfirmAction(){
		$session = SessionWrapper::getInstance(); 
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

		/*$person = new Person();
		$person->populate(decode($this->_getParam('id')));*/
		
		// $session->setVar(SUCCESS_MESSAGE, $this->_translate->translate("person_invite_success"));
    	echo '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>'.$this->_translate->translate("person_invite_success").'</div>';
    }
    
	public function invitemanyAction(){
    	$session = SessionWrapper::getInstance(); 
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		/*$ids = decode($this->_getParam('ids'));
		$invitedby = $this->_getParam('personid');
		$mails = decode($this->_getParam('emails'));*/
		
		$ids = "52,54";
		$invitedby = "51";
		$mails = "test1@devmail.infomacorp.com,test2@devmail.infomacorp.com";
		$idsarray = explode(",", $ids);
		$emailsarray = explode(",", $mails);
		
		$data = array();
		foreach ($idsarray as $key => $value){
			$data[$key]['id'] = $value;
			$data[$key]['email'] = $emailsarray[$key];
		}
		// debugMessage($data);
		
		$inviter = new Person();
		$inviter->populate($invitedby);
		if($inviter->inviteMany($data)){
			// success action
			// $this->_redirect($this->view->baseUrl('person/invitemanyconfirm'));
		}
		return false;
    }
    
	public function invitemanyconfirmAction(){
		$session = SessionWrapper::getInstance(); 
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

		// $session->setVar(SUCCESS_MESSAGE, $this->_translate->translate("person_invite_success"));
    	echo '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>'.$this->_translate->translate("person_invite_success").'</div>';
    }
    
	public function invitefriendsAction(){
    	$session = SessionWrapper::getInstance(); 
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		/*$invitedby = $this->_getParam('id');
		$mails = decode($this->_getParam('emails'));*/
		
		$invitedby = "51";
		$mails = "test1@devmail.infomacorp.com,test2@devmail.infomacorp.com";
		$emailsarray = explode(",", $mails);
		
		$inviter = new Person();
		$inviter->populate($invitedby);
		if($inviter->sendFriendsInvitationNotification($emailsarray)){
			// success action
			// $this->_redirect($this->view->baseUrl('person/invitemanyconfirm'));
		}
		return false;
    }
    
	public function invitefriendsconfirmAction(){
		$session = SessionWrapper::getInstance(); 
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

		// $session->setVar(SUCCESS_MESSAGE, $this->_translate->translate("person_invite_success"));
    	echo '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>'.$this->_translate->translate("person_invite_success").'</div>';
    }
    
 	function clanAction(){
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		// debugMessage($this->_getAllParams());
		$family = new Family();
		$family->populate($this->_getParam('familyid'));
		$result = array();
		$result['clanid'] = $family->getFather()->getClanID();
		$result['gender'] = 1;
		$result['type'] = $family->getFather()->getType();
    	echo json_encode($result);
    }
	
	function deleteAction() {
    	$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		
    	$person = new Person();
    	$person->populate($formvalues['id']);
    	// debugMessage($person->toArray());
    	//if($person->isdeletable()) {
    		$person->deletePerson();
    		$session->setVar(SUCCESS_MESSAGE, $this->_translate->translate("person_delete_success"));
    		$this->_helper->redirector->gotoUrl($this->view->baseUrl('person/list/range/2'));
    	//}
    	
    	return false;
    }
    
	function privacyAction() {
    	$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		// debugMessage($formvalues);
		
    	$person = new Person();
    	$person->populate($formvalues['id']);
    	// debugMessage($person->toArray());
    	// debugMessage($person->getPrivacyLine()->toArray());
    	
    	switch($formvalues['section']) {
    		case 'namesection':
    			$person->getPrivacyLine()->setnamesection($formvalues['securitylevel']);
    			break;
    		case 'familysection':
    			$person->getPrivacyLine()->setfamilysection($formvalues['securitylevel']);
    			break;
    		case 'clansection':
    			$person->getPrivacyLine()->setclansection($formvalues['securitylevel']);
    			break;
    		case 'personalsection':
    			$person->getPrivacyLine()->setpersonalsection($formvalues['securitylevel']);
    			break;
    		case 'emailaddresssection':
    			$person->getPrivacyLine()->setemailaddresssection($formvalues['securitylevel']);
    			break;
    		case 'phonesection':
    			$person->getPrivacyLine()->setphonesection($formvalues['securitylevel']);
    			break;
    		case 'physicaladdresssection':
    			$person->getPrivacyLine()->setphysicaladdresssection($formvalues['securitylevel']);
    			break;
    		case 'webaddresssection':
    			$person->getPrivacyLine()->setwebaddresssection($formvalues['securitylevel']);
    			break;
    		case 'birthsection':
    			$person->getPrivacyLine()->setbirthsection($formvalues['securitylevel']);
    			break;
    		case 'profilepicture':
    			$person->getPrivacyLine()->setprofilepicture($formvalues['securitylevel']);
    			break;
    		default:
    			break;
    	}
    	// debugMessage($person->getPrivacyLine()->toArray());
    	try {
    		$person->getPrivacyLine()->save();
    		echo 'yes';
    	} catch (Exception $e) {
	        // debugMessage("Error is ".$e->getMessage());
	        echo 'no';
	    }
    }
    
	function resetprivacyAction() {
    	$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		//$formvalues['id'] = 228;
		//$formvalues['securitylevel'] = 4;
		// debugMessage($formvalues);
		
    	$person = new Person();
    	$person->populate($formvalues['id']);
    	// debugMessage($person->toArray());
    	// debugMessage($person->getPrivacyLine()->toArray());
    	
    	$person->getPrivacyLine()->setnamesection($formvalues['securitylevel']);
    	$person->getPrivacyLine()->setfamilysection($formvalues['securitylevel']);
    	$person->getPrivacyLine()->setclansection($formvalues['securitylevel']);
    	$person->getPrivacyLine()->setpersonalsection($formvalues['securitylevel']);
    	$person->getPrivacyLine()->setemailaddresssection($formvalues['securitylevel']);
    	$person->getPrivacyLine()->setphonesection($formvalues['securitylevel']);
    	$person->getPrivacyLine()->setphysicaladdresssection($formvalues['securitylevel']);
    	$person->getPrivacyLine()->setwebaddresssection($formvalues['securitylevel']);
    	$person->getPrivacyLine()->setbirthsection($formvalues['securitylevel']);
    	$person->getPrivacyLine()->setdefaultprivacy($formvalues['securitylevel']);
    	$person->getPrivacyLine()->setprofilepicture($formvalues['securitylevel']);
    	
    	// debugMessage($person->getPrivacyLine()->toArray());
    	try {
    		$person->getPrivacyLine()->save();
    		$session->setVar(SUCCESS_MESSAGE, $this->_translate->translate("person_settings_success"));
    		$this->_helper->redirector->gotoUrl($this->view->baseUrl('person/view/id/'.encode($formvalues['id']).'/tab/privacy'));
    	} catch (Exception $e) {
	        $session->setVar(ERROR_MESSAGE, "An error occured in changing your settings. ".$e->getMessage());
    		$this->_helper->redirector->gotoUrl($this->view->baseUrl('person/view/id/'.encode($formvalues['id']).'/tab/privacy'));
	    }
    }
    
	function resetAction(){
    	$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		// $formvalues['id'] = 449;
		
    	$person = new Person();
    	$person->populate($formvalues['id']);
    	// debugMessage($person->toArray());
    	$families = $person->getFamiliesAdded();
    	$people = $person->getRelativesAdded();
    	//debugMessage($families->toArray());
    	//debugMessage($people->toArray());
    	
    	// delete entries
    	$families->delete();
	    $people->delete();
	    $person->getUser()->autoReset();
    	$session->setVar(SUCCESS_MESSAGE, "Family Tree successfully reset");
	    
	    $this->_helper->redirector->gotoUrl($this->view->baseUrl('person/view/id/'.encode($formvalues['id'])));
    }
    
	function deactivateAction(){
    	$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		// $formvalues['id'] = 449;
		
    	$person = new Person();
    	$person->populate($formvalues['id']);
    	$user = $person->getUser();
    	$dperson = $person;
    	
    	$families = $person->getAllFamilies();
    	$families->delete();
    	$user->delete();
    	
    	// debugMessage($user->toArray());
    	$user->sendDeactivateNotification();
    	// debugMessage($dperson->toArray());
    	$this->clearSession();
        // redirect to the login page 
        $this->_helper->redirector->gotoUrl($this->view->baseUrl('user/login/delete/1'));
    }
    
    function updaterelationAction() {
    	$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		// debugMessage($formvalues);
		
    	$tree = new FamilyTree();
    	$tree->populate($formvalues['id']);
    	$tree->setRelationShipID($formvalues['relationship']);
    	// debugMessage($tree->toArray());
    	try {
    		$tree->save();
    		echo 'yes';
    	} catch (Exception $e) {
	        // debugMessage("Error is ".$e->getMessage());
	        echo 'no';
	    }
    }
    
    public function changeemailAction(){
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		$session = SessionWrapper::getInstance(); 
		$personid = $session->getVar('personid');
		
		$formvalues = $this->_getAllParams();
		//debugMessage($formvalues);
		
		$person = new Person();
    	$person->populate($formvalues['id']);
    	$person->getUser()->setActivationKey($person->getUser()->generateActivationKey());
    	$person->getUser()->save();
    	
    	$contact = new Contact();
    	$contact->populate($formvalues['contactid']);
    	$contact->setUnConfirmed(1);
    	$contact->save();
    	
    	$person->getUser()->sendNewEmailNotification($formvalues['toemail'], $formvalues['contactid']);
    	$person->getUser()->sendOldEmailNotification($formvalues['toemail'], $formvalues['contactid']);
    	//debugMessage($person->toArray());
    	// exit();
    	$session->setVar(SUCCESS_MESSAGE, "Activation instructions have been successfully sent to your new Email Address.");
   		$this->_helper->redirector->gotoUrl($this->view->baseUrl('person/view/id/'.encode($formvalues['id']).'/tab/contacts'));
    }
    
    function demoupdateAction() {
    	$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		$newflag = $formvalues['demoflag']; 
		// debugMessage($formvalues);
	    
		$lookup = new LookupTypeValue();
		$lookup->populate(92);
		// debugMessage($lookup->toArray());
		$lookup->setLookupTypeValue($newflag);
		$lookup->save();
		
		$newflag_text = $lookup->getLookupTypeValue() == 0 ? "enabled" : "disabled";
		$session->setVar(SUCCESS_MESSAGE, "Successfully ".$newflag_text." updates to the family tree.");
   		$this->_helper->redirector->gotoUrl($this->view->baseUrl('person/view/id/'.encode($formvalues['id']).'/tab/settings'));
   		
    }
}