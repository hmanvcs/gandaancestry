<?php
class ClanController extends SecureController   {
	
	public function getResourceForACL(){
        return "Person Profile"; 
    }
 	public function getActionforACL() {
        $action = strtolower($this->getRequest()->getActionName()); 
		if($action == "add" || $action == "add" || $action == "addname" || $action == "processaddname" || 
			$action == "processcreate" || $action == "picture" || $action == "processpicture"  || $action == "ssiga"  || 
			$action == "ssigasearch" || $action == "delete"
		) {
			return ACTION_CREATE; 
		}
 		if($action == "croppicture" || $action == "addnamesuccess" || $action == "addsuccess" || $action == "test") {
			return ACTION_VIEW; 
		}
		return parent::getActionforACL(); 
    }
	public function addnameAction() {
		$session = SessionWrapper::getInstance(); 
    	
    }
	public function processaddnameAction() {
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$session = SessionWrapper::getInstance(); 
    	$formvalues = $this->_getAllParams();
    	// debugMessage($formvalues);
    	
    	$this->_setParam('createdby', $session->getVar('userid'));
    	$this->_setParam("action", ACTION_CREATE); 
    	if(!isEmptyString($formvalues['id'])){
    		$this->_setParam("action", ACTION_EDIT); 
    	}
    	
    	parent::createAction();
    }
	public function addnamesuccessAction(){
		$session = SessionWrapper::getInstance(); 
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$session->setVar(SUCCESS_MESSAGE, $this->_translate->translate("clan_addname_success"));
    	// echo '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>'.$this->_translate->translate("person_invite_success").'</div>';
    }
	public function addAction() {
		$session = SessionWrapper::getInstance(); 
    	
    }
	public function processcreateAction() {
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$session = SessionWrapper::getInstance(); 
    	$formvalues = $this->_getAllParams();
    	// debugMessage($formvalues);
    	
    	$this->_setParam('createdby', $session->getVar('userid'));
    	$this->_setParam("action", ACTION_CREATE); 
    	if(!isEmptyString($formvalues['id'])){
    		$this->_setParam("action", ACTION_EDIT); 
    	}
    	
    	parent::createAction();
    }
	public function addsuccessAction(){
		$session = SessionWrapper::getInstance(); 
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$session->setVar(SUCCESS_MESSAGE, $this->_translate->translate("person_update_success"));
    	// echo '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>'.$this->_translate->translate("person_invite_success").'</div>';
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
		// debugMessage($formvalues);
		$clan = new Clan();
		$clan->populate(decode($this->_getParam('id')));
		// debugMessage($clan->toArray());
		$updatefield = "";
		$istotem = false;
		$isflag = false;
		$iscover = false;
		switch ($formvalues['type']){
			case 1:
				$iscover = true;
				break;
			case 2:
				$istotem = true;
				break;
			case 3:
				$isflag = true;
				break;
		}
		
		// only upload a file if the attachment field is specified		
		$upload = new Zend_File_Transfer();
		// set the file size in bytes
		$upload->setOptions(array('useByteString' => false));
		
		// Limit the extensions to the specified file extensions
		$upload->addValidator('Extension', false, $config->profilephoto->allowedformats);
	 	$upload->addValidator('Size', false, $config->profilephoto->maximumfilesize);
		
		// base path for profile pictures
		$destination_path = APPLICATION_PATH.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.PUBLICFOLDER.DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."clans".DIRECTORY_SEPARATOR."clan_";
				
		// determine if user has destination avatar folder. Else user is editing there picture
		if(!is_dir($destination_path.decode($this->_getParam('id')))){
			// no folder exits. Create the folder
			mkdir($destination_path.decode($this->_getParam('id')), 0755);
		} 
		
		// set the destination path for the image
		$profilefolder = decode($this->_getParam('id'));
		$destination_path = $destination_path.$profilefolder;
		if(!is_dir($destination_path)){
			mkdir($destination_path, 0755);
		}
		// create archive folder for each user
		$archivefolder = $destination_path.DIRECTORY_SEPARATOR."archive";
		if(!is_dir($archivefolder)){
			mkdir($archivefolder, 0755);
		}
		$oldfilename = "";
		$prefixtxt = "";
		if($iscover){
			$oldfilename = $clan->getCoverPhotoUpload();
			$prefixtxt = "cover";
		}
		if($istotem){
			$oldfilename = $clan->getTotemUpload();
			$prefixtxt = "logo";
		}
		if($isflag){
			$oldfilename = $clan->getFlagUpload();
			$prefixtxt = "flag";
		}
		
		// debugMessage($destination_path); 
		$upload->setDestination($destination_path);
		
		// the profile image info before upload
		$file = $upload->getFileInfo('profileimage');
		$currenttime = mktime();
		$currenttime_file = $currenttime.'.jpg';
		// debugMessage($file);
		$thefilename = $destination_path.DIRECTORY_SEPARATOR.'base_'.$prefixtxt.'_'.$currenttime_file; 
		// debugMessage($thefilename); 
		// rename the base image file 
		$upload->addFilter('Rename',  array('target' => $thefilename, 'overwrite' => true));		
		
		// process the file upload
		if($upload->receive()){
			$file = $upload->getFileInfo('profileimage');
			// debugMessage($file);
			$basefile = $thefilename;
			
			
			// generate and save thumbnails for sizes 250, 125 and 50 pixels
			if(!$iscover){
				$newlargefilename = "large_".$prefixtxt."_".$currenttime_file;
				$newmediumfilename = "medium_".$prefixtxt."_".$currenttime_file;
				$newthumbnailfilename = "thumbnail_".$prefixtxt."_".$currenttime_file;
				
				//debugMessage($basefile); 
				$image = WideImage::load($basefile);
				$resized_1 = $image->resize(400, 400, 'inside','any');
				$resized_1->saveToFile($destination_path.DIRECTORY_SEPARATOR.$newlargefilename);
		
			} else {
				$newlargefilename = "large_cover_".$currenttime_file;
				// resizeImage($basefile, $destination_path.DIRECTORY_SEPARATOR.$newlargefilename, 815);
				
				$image = WideImage::load($basefile);
				$resized_1 = $image->resize(500, 400, 'outside','any');
				$resized_1->saveToFile($destination_path.DIRECTORY_SEPARATOR.$newlargefilename);
			}
			// exit();
			// update the useraccount with the new profile images
			try {
				if($iscover){
					$clan->setCoverPhotoUpload($currenttime_file);
				}
				if($istotem){
					$clan->setTotemUpload($currenttime_file);
				}
				if($isflag){
					$clan->setFlagUpload($currenttime_file);
				}
				$clan->save();
				
				// check if user already has profile picture and archive it
				$old_path = $destination_path.DIRECTORY_SEPARATOR."base_".$oldfilename;
				if(file_exists($old_path) && !isEmptyString($oldfilename)){
					// determine folder for last profile picture and create folder in archive
					$folder_to_create = current(explode('.', $oldfilename));
					$archivefolder = $archivefolder.DIRECTORY_SEPARATOR.$folder_to_create;
					if(!is_dir($archivefolder)){
						mkdir($archivefolder, 0755);
					}
					// loop through all files at root and in current timestamp archive folder 
					$files = glob($destination_path.DIRECTORY_SEPARATOR.'*'.$folder_to_create.'*.jpg');
					foreach ($files as $afile){
						$afile_filename = basename($afile);
						rename($afile, $archivefolder.DIRECTORY_SEPARATOR.$afile_filename);
					}
				}
				// exit();
				$session->setVar(SUCCESS_MESSAGE, $this->_translate->translate("person_update_success"));
				$this->_helper->redirector->gotoUrl($this->view->baseUrl("clan/picture/clanid/".encode($clan->getID()).'/type/'.$formvalues['type'].'/crop/1'));
			} catch (Exception $e) {
				$session->setVar(ERROR_MESSAGE, $e->getMessage());
				$session->setVar(FORM_VALUES, $this->_getAllParams());
				$this->_helper->redirector->gotoUrl($this->view->baseUrl('clan/picture/id/'.encode($clan->getID())));
			}
		} else {
			// debugMessage($upload->getMessages()); debugMessage('bye'); exit();
			$uploaderrors = $upload->getMessages();
			$customerrors = array();
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
			$this->_helper->redirector->gotoUrl(decode($formvalues[URL_SUCCESS]));
		}
	}
	
	function croppictureAction(){
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		$clan = new Clan();
		$clan->populate(decode($formvalues['id']));
		$folder = $clan->getID();
		$type = $formvalues['type'];
		// debugMessage($formvalues);
		// debugMessage($clan->toArray());
		
		$istotem = false;
		$isflag = false;
		$iscover = false;
		switch ($formvalues['type']){
			case 1:
				$iscover = true;
				break;
			case 2:
				$istotem = true;
				break;
			case 3:
				$isflag = true;
				break;
		}
		
		$oldfilename = "";
		$prefixtxt = "";
		if($iscover){
			$oldfile = $clan->getCoverPhotoUpload();
			$prefixtxt = "cover";
		}
		if($istotem){
			$oldfile = $clan->getTotemUpload();
			$prefixtxt = "logo";
		}
		if($isflag){
			$oldfile = $clan->getFlagUpload();
			$prefixtxt = "flag";
		}
		
		$oldfile = "large_".$prefixtxt."_".$oldfile;
		$base = APPLICATION_PATH.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.PUBLICFOLDER.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'clans'.DIRECTORY_SEPARATOR.'clan_'.$folder.''.DIRECTORY_SEPARATOR;;
		$src = $base.$oldfile;
		// debugMessage($src); exit();
		
		//debugMessage($person->getLargePicturePath());
		$currenttime = mktime();
		$currenttime_file = $currenttime.'.jpg';
		
		if(!$iscover){
			$newlargefilename = $base."large_".$prefixtxt."_".$currenttime_file;
			$newmediumfilename = $base."medium_".$prefixtxt."_".$currenttime_file;
			$newthumbnailfilename = $base."thumbnail_".$prefixtxt."_".$currenttime_file;
			
			$image = WideImage::load($src);
			$cropped1 = $image->crop($formvalues['x1'], $formvalues['y1'], $formvalues['w'], $formvalues['h']);
			$resized_1 = $cropped1->resize(250, 260, 'fill');
			$resized_1->saveToFile($newlargefilename);
			
			//$image2 = WideImage::load($src);
			$cropped2 = $image->crop($formvalues['x1'], $formvalues['y1'], $formvalues['w'], $formvalues['h']);
			$resized_2 = $cropped2->resize(165, 170, 'fill');
			$resized_2->saveToFile($newmediumfilename);
			
			//$image3 = WideImage::load($src);
			$cropped3 = $image->crop($formvalues['x1'], $formvalues['y1'], $formvalues['w'], $formvalues['h']);
			$resized_3 = $cropped3->resize(110, 115, 'fill');
			$resized_3->saveToFile($newthumbnailfilename);
			
		} else {
			$newlargefilename = $base."cover_".$currenttime_file;
			
			$image = WideImage::load($src);
			$cropped1 = $image->crop($formvalues['x1'], $formvalues['y1'], $formvalues['w'], $formvalues['h']);
			$resized_1 = $cropped1->resize(815, 315, 'fill');
			$resized_1->saveToFile($newlargefilename);
			
			$clan->setCoverPhotoUpload($currenttime_file);
			debugMessage('set value is '.$clan->getCoverPhotoUpload());
		}	
		
		if($istotem){
			$clan->setTotemUpload($currenttime_file);
		}
		if($isflag){
			$clan->setFlagUpload($currenttime_file);
		}
		$clan->save(); 
		//debugMessage($clan->toArray());
		//exit();
		$this->_helper->redirector->gotoUrl($this->view->baseUrl('clan/view/id/'.encode($clan->getID())));
    }
    
	public function listAction() {
    	$listcount = new LookupType();
    	$listcount->setName("LIST_ITEM_COUNT_OPTIONS");
    	$values = $listcount->getOptionValues(); 
    	asort($values, SORT_NUMERIC); 
    	$session = SessionWrapper::getInstance();
    	
    	$dropdown = new Zend_Form_Element_Select('itemcountperpage',
							array(
								'multiOptions' => $values, 
								'view' => new Zend_View(),
								'decorators' => array('ViewHelper'),
							     'class' => array('span1')
							)
						);
		if (isEmptyString($this->_getParam('itemcountperpage'))) {
			$dropdown->setValue('ALL');
			if(!isEmptyString($session->getVar('itemcountperpage'))){
				$dropdown->setValue($session->getVar('itemcountperpage'));
			}
		} else {
			$session->setVar('itemcountperpage', $this->_getParam('itemcountperpage'));
			$dropdown->setValue($session->getVar('itemcountperpage'));
		}  
	    $this->view->listcountdropdown = '<span class="pull-right">'.$this->_translate->translate("global_list_itemcount_dropdown").$dropdown->render().'</span>';	
    }
    
	public function ssigaAction() {
		$listcount = new LookupType();
    	$listcount->setName("LIST_ITEM_COUNT_OPTIONS");
    	$values = $listcount->getOptionValues(); 
    	asort($values, SORT_NUMERIC); 
    	$session = SessionWrapper::getInstance();
    	
    	$dropdown = new Zend_Form_Element_Select('itemcountperpage',
							array(
								'multiOptions' => $values, 
								'view' => new Zend_View(),
								'decorators' => array('ViewHelper'),
							     'class' => array('span1')
							)
						);
		if (isEmptyString($this->_getParam('itemcountperpage'))) {
			$dropdown->setValue(50);
			if(!isEmptyString($session->getVar('itemcountperpage'))){
				$dropdown->setValue($session->getVar('itemcountperpage'));
			}
		} else {
			$session->setVar('itemcountperpage', $this->_getParam('itemcountperpage'));
			$dropdown->setValue($session->getVar('itemcountperpage'));
		}  
	    $this->view->listcountdropdown = '<span class="pull-right">'.$this->_translate->translate("global_list_itemcount_dropdown").$dropdown->render().'</span>';
	}
	
	function ssigasearchAction(){
		$this->_helper->redirector->gotoSimple("ssiga", "clan", 
    											$this->getRequest()->getModuleName(),
    											array_remove_empty(array_merge_maintain_keys($this->_getAllParams(), $this->getRequest()->getQuery())));
	}
	
	function deleteAction() {
    	$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		
    	$clan = new Organisation();
    	$clan->populate($formvalues['id']);
    	$clanid = $clan->getClanID();
    	// debugMessage($person->toArray());
    	$clan->delete();
    	
    	$session->setVar(SUCCESS_MESSAGE, $this->_translate->translate("person_delete_success"));
    	if($clan->isClan()){
    		$this->_helper->redirector->gotoUrl($this->view->baseUrl('clan/list/range/2'));
    	} else {
    		$this->_helper->redirector->gotoUrl($this->view->baseUrl('clan/view/tab/ssiga/id/'.encode($clanid)));
    	}
    	
    	return false;
    }
    
    function testAction() {
    	$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		
		$testarray = array(
			"id" => "",
		    "action" => "create",
		    "createdby" => "1",
		    "clanid" => "7",
		    "fullname" => "asasas",
		    "leader" => "cacaba",
		    "places" => array(
		            "aba4b71492e96a9b35a6d8ad15728f8f" => array(
		                    "id" => "",
		                    "streetaddress" => "--"
		                )
		       ),
		    "places_type_aba4b71492e96a9b35a6d8ad15728f8f" => "5",
		    "places_country_aba4b71492e96a9b35a6d8ad15728f8f" => "XX",
		    "places_ssaza_aba4b71492e96a9b35a6d8ad15728f8f" => "2",
		    "places_village_aba4b71492e96a9b35a6d8ad15728f8f" => "728",
		    "type" => "2"
		);
		
		$ssiga = new Organisation();
		$ssiga->processPost($testarray);
		debugMessage('error is '.$ssiga->getErrorStackAsString());
		debugMessage($ssiga->toArray());
		
		$ssiga->save();
		$ssiga->afterSave();
		debugMessage('new is '.$ssiga->getErrorStackAsString());
		exit();
    }
}

