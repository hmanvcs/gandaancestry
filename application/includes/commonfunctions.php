<?php

# functions to create and manage drop down lists
require_once 'dropdownlists.php';

define("ACTION_CREATE", "create");
define("ACTION_INDEX", "index"); // maps to the default controller for Zend Framework, same as the create action in the ACL 
define("ACTION_EDIT", "edit");
define("ACTION_APPROVE", "approve");
define("ACTION_DELETE", "delete");
define("ACTION_EXPORT", "export");
define("ACTION_VIEW", "view");
define("ACTION_LIST", "list");

# redirect, success and error urls during the processing of an action 
define("URL_REDIRECT", "redirecturl"); // url forwarded to when a user has to login 
define("URL_SUCCESS", "successurl"); // override the url when an action suceeds
define("URL_FAILURE", "failureurl"); // override the url when an action fails

# the separator between a table name and a column name for filtering since the . cannot be used as
# a separator for HTML field names
define("HTML_TABLE_COLUMN_SEPARATOR", "__");

# the session variable holding the values from the REQUEST when an error occurs
define("FORM_VALUES", "formvalues");
# the session variable holding the error message when processing a form 
define("ERROR_MESSAGE", "errors"); 
# the session variable holding the success message when processing a form 
define("SUCCESS_MESSAGE", "successmessage"); 
# the session variable holding the error message for the error page which is not cleared from page to page 
define("APPLICATION_ERROR_PAGE_MESSAGE", "error_page_erros"); 
# the session variable for the access control lists 
define("SESSION_ACL", "acl"); 

# calendar view options
define("CALENDAR_VIEW_MONTH", "month_view"); 
define("CALENDAR_VIEW_WEEK", "week_view"); 

# constant for showing views in a popup
define("PAGE_CONTENTS_ONLY", "pgc"); 
define("EXPORT_TO_EXCEL", "excel"); 

# constant for the select chain value
define("SELECT_CHAIN_TYPE", "select_chain_type"); 

# excel generation constants
# a comma delimited list of column indexes with numbers
define("EXPORT_NUMBER_COLUMN_LIST", "numbercolumnlist");
# the number of columns to ignore at the beginning of the query 
define("EXPORT_IGNORE_COLUMN_COUNT", "columncheck");  
# the query string with all the results
define("ALL_RESULTS_QUERY", "arq");
# the query string with the searches and filters applied
define("CURRENT_RESULTS_QUERY", "crq");
# the page title for current list
define("PAGE_TITLE", "ttl");

// relationship types
define('RELATIONSHIP_ME', 0);
define('RELATIONSHIP_FATHER', 1);
define('RELATIONSHIP_MOTHER', 2);
define('RELATIONSHIP_BROTHER', 3);
define('RELATIONSHIP_SISTER', 4);
define('RELATIONSHIP_SON', 5);
define('RELATIONSHIP_DAUGHTER', 6);
define('RELATIONSHIP_PARTNER', 7); 

define('RELATIONSHIP_ME_LABEL', 'Myself');
define('RELATIONSHIP_FATHER_LABEL', 'Father');
define('RELATIONSHIP_MOTHER_LABEL', 'Mother');
define('RELATIONSHIP_BROTHER_LABEL', 'Brother');
define('RELATIONSHIP_SISTER_LABEL', 'Sister');
define('RELATIONSHIP_SON_LABEL', 'Son');
define('RELATIONSHIP_DAUGHTER_LABEL', 'Daughter');
define('RELATIONSHIP_PARTNER_LABEL', 'Spouse'); 

define('PRIVACY_1', 1);
define('PRIVACY_2', 2);
define('PRIVACY_3', 3);
define('PRIVACY_4', 4);
define('PRIVACY_5', 5);

define('PRIVACY_1_SYMBOL', 'P');
define('PRIVACY_2_SYMBOL', 'S');
define('PRIVACY_3_SYMBOL', 'R');
define('PRIVACY_4_SYMBOL', 'F');
define('PRIVACY_5_SYMBOL', 'M');

define('DEMO_FLAG', 1);

// open a connection to the database
function openDatabaseConnection() {
	// open connection to MySQL database
	$link = mysql_connect(MYSQL_HOST, MYSQL_USER,MYSQL_PASSWORD)
		  or die("Could not connect to the SQL database server");
	mysql_select_db(MYSQL_DATABASE) or die("Could not connect to the SQL database server");
}
/**
 * Change a date from MySQL database Format (yyyy-mm-dd) to the format displayed on pages(mm/dd/yyyy)
 * 
 * If the date from the database is NULL, it is transformed to an empty string for display on the pages 
 *
 * @param String $mysqldate The date in MySQL format 
 * @return String the date in short date format, or an empty string if no date is provided 
 */
function changeMySQLDateToPageFormat($mysqldate) {
	$aconfig = Zend_Registry::get("config"); 
	if (isEmptyString($mysqldate)) {
		return $mysqldate;
	} else {
		return date($aconfig->dateandtime->mediumformat, strtotime($mysqldate));
	}
}
/**
 * Transform a date from the format displayed on pages(mm/dd/yyyy) to the MySQL database date format (yyyy-mm-dd). 
 * If the date from the database is an empty string or the string NULL, it is transformed to a NULL value.
 *
 * @param String $pagedate The string representing the date
 * @return String The MYSQL datetime format or NULL if the provided date is an empty string or the string NULL 
 */
function changeDateFromPageToMySQLFormat($pagedate) {
	if ($pagedate == "NULL") {
		return NULL;
	}
	if (isEmptyString($pagedate)) {
		return NULL;
	} else {
		return date("Y-m-d H:i:s", strtotime($pagedate));
	}
}


/**
 * Check whether or not the string is empty. The string is emptied
 *
 * @param String $str The string to be checked
 * 
 * @return Boolean Whether or not the string is empty
 */
function isEmptyString($str) {
	if ($str == "") {
		return true; 
	}
	if ((trim($str)) == "") {
		return true;
	} else {
		return false;
	}
}

/**
 * Check whether or not the value of the key in the specified array is empty
 *
 * @param String $key The key whose value is to be checked
 * @param Array $arr The array to check  
 * 
 * @return Boolean Whether or not the array key is empty
 */
function isArrayKeyAnEmptyString($key, $arr) {
	if (!array_key_exists($key, $arr)) {
		return true; 
	}
	if (is_string($arr[$key])) {
		return isEmptyString($arr[$key]);
	}
	return false; 
}
/**
 * Check whether or not the string is empty. The string is emptied
 *
 * @param String $str The string to be checked
 * 
 * @return boolean Whether or not the string is empty
 */
function isNotAnEmptyString($str) {
	return ! isEmptyString($str);
}

/**
 * Return a debug message with a break tag before and two break tags after
 *
 * @param Object $obj The object to be printed
 */
function debugMessage($obj) {
	echo "<br />";
	print_r($obj);
	echo "<br /><br />";
}

/**
 * Return the value of the checked HTML attribute for a checkbox or radio button
 *
 * @param Boolean $bool whether or not the HTML control is checked
 * @return String the checked attribute
 */
function getCheckedAttribute($bool) {
	if ($bool) {
		return ' checked="checked"';
	}
	return "";
}
/**
 *  Merge the arrays passed to the function and keep the keys intact.
 *  If two keys overlap then it is the last added key that takes precedence.
 * 
 * @return Array the merged array
 */
function array_merge_maintain_keys() {
	$args = func_get_args();
	$result = array();
	foreach ( $args as &$array ) {
		foreach ( $array as $key => &$value ) {
			$result[$key] = $value;
		}
	}
	return $result;
}

# function that trims every value of an array
function trim_value(&$value) {
	$value = trim($value);
}

/**
 * Recursively Remove empty values from an array. If any of the keys contains an 
 * array, the values are also removed.
 *
 * @param Array $input The array
 * @return Array with the specified values removed or the filtered values
 */
function array_remove_empty($arr) {
	$narr = array();
	while ( list ($key, $val) = each($arr) ) {
		if (is_array($val)) {
			$val = array_remove_empty($val);
			// does the result array contain anything?
			if (count($val) != 0) {
				// yes :-)
				$narr[$key] = $val;
			}
		} else {
			if (! isEmptyString($val)) {
				$narr[$key] = $val;
			}
		}
	}
	unset($arr);
	return $narr;

}

/**
 * Send test email
 *
 * @param String $subject The subject of the email 
 * @param String $message The contents of the email 
 */
function sendTestMessage($subject = "", $message = "") {
	$mailer = getMailInstance(); 
	
	# get an instance of the PHP Mailer
	$from_email = $mailer->getDefaultFrom(); 
	$mailer->AddTo($from_email['email']);
	
	$mailer->setSubject($subject);
	$mailer->setBodyHTML($message);
	try {
		$result = $mailer->send();
		//debugMessage("The email sending result is ".$result);
		if (!$result) {
			# Log the error
			echo "an error occured while sending the message " . $mailer->ErrorInfo;
		}
	} catch ( Exception $e ) {
		debugMessage("Error sending email ".$e);
	}
}
/**
 * Wrapper function for the encoding of the urls using base64_encode 
 *
 * @param String $str The string to be encoded
 * @return String The encoded string 
 */
function encode($str) {
	return base64_encode($str); 
}
/**
 * Wrapper function for the decoding of the urls using base64_decode 
 *
 * @param String $str The string to be decoded
 * @return String The encoded string 
 */
function decode($str) {
	return base64_decode($str); 
}

/**
 * Function to generate a JSON string from an array of data, using the keys and values
 *
 * @param $data The data to be converted into a string
 * @param $default_option_value Value of the default option
 * @param $default_option_text Test for the default 
 * 
 * @return the JSON string containing the select options
 */
function generateJSONStringForSelectChain($data, $default_option_value = "", $default_option_text = "<Select One>") {
	$values = array(); 
	//debugMessage($data);
	if (!isEmptyString($default_option_value)) {
		# use the text and option from the data
		if(!isArrayKeyAnEmptyString($default_option_value, $data)){
			$values[] = '{"id":"' . $default_option_value . '", "label":"' . $data[$default_option_value] . '"}';
			// remove the default option from the available options
			unset($data[$default_option_value]);
		}
	}
	# add a default option
	$values[] = '{"id":"", "label":"' . $default_option_text . '"}';
	foreach ( $data as $key => $value ) {
		$values[] = '{"id":"'.$key.'", "label":"' . $value . '"}';
		//debugMessage($strstring);
	}
	# remove the first comma at the end
	return '[' . implode("," , $values). "]";
}
/**
 * Format a number to two decimal places and a comma separator between thousands. Empty Strings are considered to be numeric
 *
 * @param Number $number The number to be formatted
 * @return Number The formatted version of the number
 */
function formatNumber($number) {
	if (isEmptyString($number) || !is_numeric($number)) {
		return $number;
	}
	$aconfig = Zend_Registry::get("config"); 
	return number_format($number, $aconfig->currency->decimalplaces);
}
/**
 * Generate an HTML list from an array of values
 *
 * @param Array $array
 * @return String 
 */
function createHTMLListFromArray($array, $classname="") {
	$str = ""; 
	// return empty string if no array is passed
	if (!is_array($array)) {
		return $str; 
	}
	// return an empty string if the array is empty
	if (!$array) {
		return $str; 
	}
	$class = "";
	if(!isEmptyString($classname)){
		$class = " class='".$classname."'";
	}
	// opening list tag and the first li element
	$str  = "<ul ".$class."><li>";
	// explode the array and generate the inner list items
	$str .= implode($array, "</li><li>");
	// close the last list item, and the ul
	$str .= "</li></ul>"; 
	
	return $str; 
}
/**
  * Load the application configuration from the database
  * 
  */
function loadConfig() {
	$cache = Zend_Registry::get('cache');
	// load the configuration from the cache
	$config = $cache->load('config'); 
	if (!$config) {
		// do nothing 
	} else {
		// add the config object to the registry 
		Zend_Registry::set('config', $config);
		return; 
	}
	
	// load the active application configuration from the database
	$sql = "SELECT section, optionname, optionvalue FROM appconfig WHERE active = 'Y'";

	$conn = Doctrine_Manager::connection(); 
	$result = $conn->fetchAll($sql); 
	
	// generate a config array from the data
	if (!$result) {
		// do nothing no data returned
	} else {
		$config_array = array(); 
		foreach ($result as $line) {
			if (isEmptyString($line['section'])) {
				// no section name provided so add the option to the root
				$config_array[$line['optionname']] = $line['optionvalue']; 
			} else {
				// add the option to the section 
				$config_array[$line['section']][$line['optionname']]= $line['optionvalue'];
			}  
		}
		# Add the config object to the registry
		$config = new Zend_Config($config_array); 
		Zend_Registry::set('config', $config);
		# cache the config object
		$cache->save($config, 'config');
	}
}
/**
 * Create a Zend_Mail instance from the registry, clear all recipients and the existing subject
 * 
 * @return Zend_Mail 
 */
function getMailInstance() {
	// create mail object
	$mail = Zend_Registry::get("mail");
	//TODO: Temporary workaround for subject set twice error message
	// clear the subject to prevent an error when sending multiple emails in the same session
	$mail->clearSubject(); 
	// clear the addresses too
	$mail->clearRecipients();
	
	return $mail; 
}
/**
 * Return an instance of the access control list 
 *
 * @return ACL 
 */
function getACLInstance() {
	$cache = Zend_Registry::get('cache'); 
	$session = SessionWrapper::getInstance(); 
	// check if the acl is cached
	$aclkey = "acl".$session->getVar('userid'); 
	$acl = $cache->load($aclkey); 
	if (!$acl) {
		$acl = new ACL($session->getVar('userid')); 
	}
	
	return $acl; 
}
/**
 * Return the file extension from a file name
 * 
 * @param string $filename
 * @return The file extension 
 */
function findExtension($filename){  
	return substr(strrchr($filename,'.'),1);
}
/**
 * Decode all html entities of an array  
 * @param Array $elem the array to be decoded
 */
function decodeHtmlEntitiesInArray(&$elem){ 
	if (!is_array($elem)) { 
    	$elem=html_entity_decode($elem); 
	}  else  { 
		foreach ($elem as $key=>$value){
			$elem[$key]=decodeHtmlEntitiesInArray($value);
		} 
  	} 
	return $elem; 
}
 /**
 * Trims a given string with a length more than a specified length with a more link to view the details 
 *
 * @param string $text
 * @param int $length
 * @param string $tail
 * @return string the substring with a more link as the tail
 */
function snippet($text, $length, $tail) {
	$text = trim($text);
	$txtl = strlen($text);
	if ($txtl > $length) {
		for($i = 1; $text[$length - $i] != " "; $i ++) {
			if ($i == $length) {
				return substr($text, 0, $length) . $tail;
			}
		}
		for(; $text[$length - $i] == "," || $text[$length - $i] == "." || $text[$length - $i] == " "; $i ++) {
			;
		}
		$text = substr($text, 0, $length - $i + 1) . $tail;
	}
	return $text;
} 
function formatBytes($size, $precision = 2) { 
    $base = log($size) / log(1000);
    $suffixes = array('', 'kb', 'MB', 'GB', 'TB');   

    return round(pow(1000, $base - floor($base)), $precision) . $suffixes[floor($base)];
}
 /*
 * Generate a thumbnail from a source and a new width
 */
function resizeImage($in_filename, $out_filename, $width){
	$src_img = ImageCreateFromJpeg($in_filename);

    $old_x = ImageSX($src_img);
    $old_y = ImageSY($src_img);
    
    /* find the "desired height" of this thumbnail, relative to the desired width  */
  	$desired_height = floor($old_y *($width/$old_x));
  
    $dst_img = ImageCreateTrueColor($width, $desired_height);
    ImageCopyResampled($dst_img, $src_img, 0, 0, 0, 0, $width, $desired_height, $old_x, $old_y);

    ImageJpeg($dst_img, $out_filename, 100);

    ImageDestroy($dst_img);
    ImageDestroy($src_img);
}
# determine key for an array in a multimensional array
function array_search_key($all, $checkarray) {
   foreach ($all as $key => $value) {
		// debugMessage($subkey);
		if($checkarray['id'] == $value['id'] && count(array_diff($value, $checkarray)) == 0){
			return $key;
		}
   }
}
# determine key for id in multidimensional array
function array_search_key_by_id($themultiarray, $theid) {
   foreach ($themultiarray as $key => $value) {
		// debugMessage($subkey);
		if($theid == $value['id']){
			return $key;
		}
   }
}
# synchronise to multimensional arrays
function multidimensional_array_merge($oldarray, $newarray){
    $result = array();
    foreach($oldarray as $key => $value){
    	$result[$key] = $value;
        foreach ($newarray as $n_key => $n_value) {
        	if(strval($n_key) == strval($key)){
        		// debugMessage($value); debugMessage($newarray[$key]);
        		$merged = array_merge($oldarray[$key], $newarray[$n_key]);
        		unset($result[$key]);
        		$result[$key] = $merged;
        	} else {
        		$result[$n_key] = $n_value;
        	}
        }
        // debugMessage($oldarray[$n_key]);
    }
    return $result;
}
# convert text to url
function textToUrl($txtstring){
	if(isEmptyString($txtstring)){
		return '---';
	}
	return "<a href='".$txtstring."' target='_blank' title='Visit address'>".$txtstring."</a>";
}
# determine if person has profile image
function hasProfileImage($id, $photoname){
	$real_path = APPLICATION_PATH."/../public/uploads/user_";
 	if (APPLICATION_ENV == "production") {
 		$real_path = str_replace("public/", "", $real_path); 
 	}
	$real_path = $real_path.$id.DIRECTORY_SEPARATOR."avatar".DIRECTORY_SEPARATOR."base_".$photoname;
	if(file_exists($real_path) && !isEmptyString($photoname)){
		return true;
	}
	return false;
}
# determine path to small profile picture
function getSmallPicturePath($id, $gender, $photoname) {
	$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
	$path= "";
	if(isMale($gender)){
		$path = $baseUrl.'/uploads/user_0/avatar/default_small_male.jpg';
	}  
	if(isFemale($gender)){
		$path = $baseUrl.'/uploads/user_0/avatar/default_small_female.jpg'; 
	}
	if(hasProfileImage($id, $photoname)){
		$path = $baseUrl.'/uploads/user_'.$id.'/avatar/small_'.$photoname;
	}
	return $path;
}
# determine path to thumbnail profile picture
function getThumbnailPicturePath($id, $gender, $photoname) {
	$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
	$path= "";
	if(isMale($gender)){
		$path = $baseUrl.'/uploads/user_0/avatar/default_thumbnail_male.jpg';
	}  
	if(isFemale($gender)){
		$path = $baseUrl.'/uploads/user_0/avatar/default_thumbnail_female.jpg'; 
	}
	if(hasProfileImage($id, $photoname)){
		$path = $baseUrl.'/uploads/user_'.$id.'/avatar/thumbnail_'.$photoname;
	}
	return $path;
}
# determine path to medium profile picture
function getMediumPicturePath($id, $gender, $photoname) {
	$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
	$path= "";
	if(isMale($gender)){
		$path = $baseUrl.'/uploads/user_0/avatar/default_medium_male.jpg';
	}  
	if(isFemale($gender)){
		$path = $baseUrl.'/uploads/user_0/avatar/default_medium_female.jpg'; 
	}
	if(hasProfileImage($id, $photoname)){
		$path = $baseUrl.'/uploads/user_'.$id.'/avatar/medium_'.$photoname;
	}
	return $path;
}
# determine path to large profile picture
function getLargePicturePath($id, $gender, $photoname) {
	$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
	$path= "";
	if(isMale($gender)){
		$path = $baseUrl.'/uploads/user_0/avatar/default_large_male.jpg';
	}  
	if(isFemale($gender)){
		$path = $baseUrl.'/uploads/user_0/avatar/default_large_female.jpg'; 
	}
	if(hasProfileImage($id, $photoname)){
		$path = $baseUrl.'/uploads/user_'.$id.'/avatar/large_'.$photoname;
	}
	// debugMessage($path);
	return $path;
}
# determine if male
function isMale($gender){
	return $gender == '1' ? true : false; 
}
# determine if female
function isFemale($gender){
	return $gender == '2' ? true : false; 
}
# determine if loggedin user is admin
function isAdmin() {
	$session = SessionWrapper::getInstance(); 
	return $session->getVar('type') == '1' ? true : false;
}
# determine if loggedin user is subscriber
function isSubscriber() {
	$session = SessionWrapper::getInstance(); 
	return $session->getVar('type') == '2' ? true : false;
}
function png2jpg($originalFile, $outputFile, $quality) {
    $image = imagecreatefrompng($originalFile);
    imagejpeg($image, $outputFile, $quality);
    imagedestroy($image);
}
function ak_img_convert_to_jpg($target, $newcopy, $ext) {
    list($w_orig, $h_orig) = getimagesize($target);
    $ext = strtolower($ext);
    $img = "";
    if ($ext == "gif"){ 
        $img = imagecreatefromgif($target);
    } else if($ext =="png"){ 
        $img = imagecreatefrompng($target);
    }
    $tci = imagecreatetruecolor($w_orig, $h_orig);
    imagecopyresampled($tci, $img, 0, 0, 0, 0, $w_orig, $h_orig, $w_orig, $h_orig);
    imagejpeg($tci, $newcopy, 84);
}
/**
 * Determine the Unix timestamp of a given MySql date
 * @param string the MySql date whose timestamp is being determined
 * @return string the timestamp of the MySql date specified
 */
function convertMySqlDateToUnixTimestamp($mysqldate) {
	if ($mysqldate) {
		$parts = explode(' ', $mysqldate);
	}
	$datebits = explode('-', $parts[0]);
	if (3 != count($datebits)) {
		return -1;
	}
	if (isset($parts[1])) {
		$timebits = explode(':', $parts[1]);
		if (3 != count($timebits)) return -1;
		return mktime($timebits[0], $timebits[1], $timebits[2], $datebits[1], $datebits[2], $datebits[0]);
	}
	return mktime (0, 0, 0, $datebits[1], $datebits[2], $datebits[0]);
}
 	function sort_multi_array($array, $key, $order="DESC"){
		$keys = array();
		for ($i=1;$i<func_num_args();$i++) {
			$keys[$i-1] = func_get_arg($i);
		}
	
		// create a custom search function to pass to usort
		$func = function ($a, $b) use ($keys) {
			for ($i=0;$i<count($keys);$i++) {
				if ($a[$keys[$i]] != $b[$keys[$i]]) {
					return ($a[$keys[$i]] < $b[$keys[$i]]) ? -1 : 1;
				}
			}
	    	return 0;
	  	};
	  	usort($array, $func);
	  		if($order != "DESC"){
	  			$result = $array;
	 		} else {
	  		$result = array_reverse($array, true);
	  	}
  	 	return $result;
	}
# Paypal parameters
function getPayPalReturnUrl() {
	$view = new Zend_View();
	return $view->serverUrl($view->baseUrl('donation/paymentconfirmation'));
}
function getPayPalCancelUrl() {
	$view = new Zend_View();
	return $view->serverUrl($view->baseUrl('store/failure'));
}
function getPayPalNotifyUrl() {
	$view = new Zend_View();
	return $view->serverUrl($view->baseUrl('store/ipn'));
}
/**
 * 
 * Check whether the transaction ID has already been used, then it is a duplicate transaction
 * 
 * @param String $txn_idFromPaypal The Transaction ID from Paypal 
 * 
 * @return bool TRUE if the transaction ID does not exist in the database, and FALSE if the transaction ID already exists
 */
function isTransactionIDValid($txn_idFromPaypal) {
	$conn = Doctrine_Manager::connection(); 
	$query = "SELECT txn_id from payment WHERE txn_id = '".$txn_idFromPaypal."'";
	$result = $conn->fetchOne($query); 
	
	if(!isEmptyString($result)){ 
		// an error occured while processing the query
		sendTestMessage("Paypal Transaction ID ".$txn_idFromPaypal." already exists!!", "Query: ".$query.", <br>Error: ");
		return false;
	} else {
		// if there are no rows returned then txn_id has not been used before and is therefore valid.	
		return true;
	}
}
/**
 * 
 * Update the expiry date once an author has completed payment
 * @param unknown_type $custom
 * @param unknown_type $paymentid
 * @param unknown_type $item_number
 */
function executeCustomLogic($custom, $paymentid, $item_number) {
		//sendTestMessage("Params: ".$custom." - ".$paymentid." - ".$item_number);
		// TODO send an email confirming the payment
		$donation = new Donation();
		$donation->populate($custom);
		$donation->sendThankYouEmail();
		
		// $user->sendSubscriptionRenewalNotification();
		return true;
}
function detectIE() {
	if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) {
		return true;
	} else {
		return false;
	}
}
function valid_mysql_date($str){
    $date_parts = explode('-',$str);
    if (count($date_parts) != 3) return false;
    if ((strlen($date_parts[0]) != 4) || (!is_numeric($date_parts[0]))) return false;
    if ((strlen($date_parts[1]) != 2) || (!is_numeric($date_parts[1]))) return false;
    if ((strlen($date_parts[2]) != 2) || (!is_numeric($date_parts[2]))) return false;
    if (!checkdate( $date_parts[1], $date_parts[2] , $date_parts[0] )) return false;
    return true;
}
function deleteDir($dir) {
   // open the directory
   $dhandle = opendir($dir);

   if ($dhandle) {
      // loop through it
      while (false !== ($fname = readdir($dhandle))) {
         // if the element is a directory, and 
         // does not start with a '.' or '..'
         // we call deleteDir function recursively 
         // passing this element as a parameter
         if (is_dir( "{$dir}/{$fname}" )) {
            if (($fname != '.') && ($fname != '..')) {
               echo "<u>Deleting Files in the Directory</u>: {$dir}/{$fname} <br />";
               deleteDir("$dir/$fname");
            }
         // the element is a file, so we delete it
         } else {
            echo "Deleting File: {$dir}/{$fname} <br />";
            unlink("{$dir}/{$fname}");
         }
      }
      closedir($dhandle);
    }
   // now directory is empty, so we can use
   // the rmdir() function to delete it
   echo "<u>Deleting Directory</u>: {$dir} <br />";
   rmdir($dir);
}
?>