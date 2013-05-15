<?php

class UploadController extends SecureController   {
	
	function getResourceForACL() {
		return "Event"; 
	}
	function getActionforACL() {
		$action = strtolower($this->getRequest()->getActionName());
		if ($action == "test") {
			return ACTION_VIEW; 
		}
		 
		return parent::getActionforACL(); 
	}
	function createAction(){
		//$this->_helper->layout->disableLayout();
		//$this->_helper->viewRenderer->setNoRender(TRUE);
		$session = SessionWrapper::getInstance();
		$config = Zend_Registry::get("config"); 
		$formvalues = $this->_getAllParams();
		//debugMessage($formvalues);
		
		// only upload a file if the attachment field is specified		
		$upload = new Zend_File_Transfer();
		$upload->setOptions(array('useByteString' => false));
		
		// File validators
		$upload->addValidator('Extension', false, $config->document->allowedformats);
	 	$upload->addValidator('Size', false, $config->document->maximumfilesize);
		
		// base path for uploaded
	 	$destination_path = APPLICATION_PATH.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.PUBLICFOLDER.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR;
		// add user folder if missing and add it to destination path
		$userfolder = "user_".$session->getVar('userid');
		if(!is_dir($destination_path.$userfolder)){
			mkdir($destination_path.$userfolder, 0755);
		}
		$folderpath = $userfolder."/";
		$destination_path = $destination_path.$userfolder.DIRECTORY_SEPARATOR;
		
		$eventfolder = "event";
		if(!is_dir($destination_path.$eventfolder)){
			mkdir($destination_path.$eventfolder, 0755);
		}
		$folderpath .= $eventfolder;
		$destination_path = $destination_path.$eventfolder.DIRECTORY_SEPARATOR;
		
		// set the destination for the uploaded files. 
		$upload->setDestination($destination_path);
		// debugMessage($upload);
		
		// loop through and upload files
		foreach($upload->getFileInfo() as $file => $info) {
			// debugMessage($info);
			// Process attachment if it exists in post data 
			if($file == 'attachment' && !isEmptyString($info['name'])){
				if($upload->receive($info['name'])){
					$ext = strtolower(findExtension($info['name']));
					$this->_setParam('filename', $info['name']);
					$this->_setParam('mimetype', $info['type']); // TODO: write custom function to generate mime types from a file 
					$this->_setParam('filesize', $info['size']);
					$this->_setParam('extension', $ext);
					$this->_setParam('filepath', $folderpath);
					
					// save thumbnails if upload is an image
					if(isImage($ext)){
						resizeImage($destination_path.$info['name'], $destination_path."medium_".$info['name'], 125);
						resizeImage($destination_path.$info['name'], $destination_path."thumbnail_".$info['name'], 50);
						// if width of image is greater than 450, resize original image
						list($width, $height, $type, $attr) = getimagesize($destination_path.$info['name']);
						// debugMessage($width." - ".$height." - ".$type." - ".$attr);
						if($width > 450 || $height > 600){
							resizeImage($destination_path.$info['name'], $destination_path.$info['name'], 450);
						}
					}
					
				} else {
					// custom error handling
					$uploaderrors = $upload->getMessages();
					$customerrors = array();
					// debugMessage($info); exit();
					if(!isArrayKeyAnEmptyString('fileExtensionFalse', $uploaderrors)){
						$custom_exterr = sprintf(translateKey('upload_invalid_ext_error'), $info['name'], $config->document->allowedformats);
						$customerrors['fileExtensionFalse'] = $custom_exterr;
					}
					if(!isArrayKeyAnEmptyString('fileUploadErrorIniSize', $uploaderrors)){
						$custom_exterr = sprintf(translateKey('upload_invalid_size_error'), $info['name'], formatBytes($config->document->maximumfilesize,0));
						$customerrors['fileUploadErrorIniSize'] = $custom_exterr;
					}
					if(!isArrayKeyAnEmptyString('fileSizeTooBig', $uploaderrors)){
						$custom_exterr = sprintf(translateKey('upload_invalid_size_error'), $info['name'], formatBytes($config->document->maximumfilesize,0));
						$customerrors['fileSizeTooBig'] = $custom_exterr;
					}
					// debugMessage($customerrors);
					$this->_setParam('id', $formvalues['id']);
					$session->setVar(ERROR_MESSAGE, 'The following errors occured <ul><li>'.implode('</li><li>', $customerrors).'</li></ul>');
					$session->setVar(FORM_VALUES, $this->_getAllParams()); debugMessage($session->getVar(ERROR_MESSAGE));
					// return to index page
					$this->_redirect(decode($this->_getParam(URL_FAILURE)));
				}
			}
		}
		// debugMessage($this->_getAllParams());
		// exit();
		parent::createAction();
	}
	function testAction(){
		$this->_helper->layout->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(TRUE);  
	    
	    $error = "";
		$msg = "";
		$fileElementName = 'attachment';
		
		if(!empty($_FILES[$fileElementName]['error'])){
			switch($_FILES[$fileElementName]['error']){
				case '1':
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$error = 'No file was uploaded.';
					break;
				case '6':
					$error = 'Missing a temporary folder';
					break;
				case '7':
					$error = 'Failed to write file to disk';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = 'No error code avaiable';
			}
		} elseif(empty($_FILES['attachment']['tmp_name']) || $_FILES['attachment']['tmp_name'] == 'none'){
			$error = 'No file was uploaded..';
			} else {
					// $msg .= " " . $_FILES['attachment']['name'] . " ";
					$msg .= $_FILES['attachment']['name'];
					// $msg .= " (" . @filesize($_FILES['attachment']['tmp_name'])." B)";
					//for security reason, we force to remove all uploaded file
					// @unlink($_FILES['attachment']);

					// debugMessage($formvalues);
					$config = Zend_Registry::get("config"); 
					$this->_translate = Zend_Registry::get("translate"); 
					$session = SessionWrapper::getInstance();
					
					// only upload a file if the attachment field is specified		
					$upload = new Zend_File_Transfer();
					$upload->setOptions(array('useByteString' => false));
					
					// File validators
					$upload->addValidator('Extension', false, $config->document->allowedformats);
				 	$upload->addValidator('Size', false, $config->document->maximumfilesize);
				 	
					// the document file object before upload
					$file = $upload->getFileInfo('attachment');
					$filename = $file['attachment']['name'];
					// debugMessage($oldfilename);
					
					// base path for uploaded
				 	$destination_path = APPLICATION_PATH.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.PUBLICFOLDER.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR;
					
					// the destination for the uploaded file. Add the matterid to the path
					$upload->setDestination($destination_path);
					debugMessage($destination_path);
					// process the file upload
					/*if($upload->receive($filename)){
						// debugMessage($upload);
						// file has been uploaded. Set filename 
						// $this->_setParam('resume', $newresumefilename);
						
					} else {
					}*/
			}		
			echo "{";
			echo "error: '" . $error . "',\n";
			echo "msg: '" . $msg . "'\n";
			echo "}";
			
	}
	function viewAction(){
		// disable rendering of the view and layout so that we can just echo the AJAX output 
		// $this->_helper->layout->disableLayout();
		//$this->_helper->viewRenderer->setNoRender(TRUE);
		if($this->_getParam('returntoperson') == 1){
			$session = SessionWrapper::getInstance();
			$session->setVar(SUCCESS_MESSAGE, translateKey("upload_save_success"));
			
			$doc = loadEntity('Document', decode($this->_getParam('id')));
			$this->_helper->redirector->gotoUrl($this->view->baseUrl('person/view/id/'.decode($doc->getPersonID())."/tab/events"));
		}
	}
}

