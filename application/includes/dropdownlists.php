<?php

	# This class require_onces functions to access and use the different drop down lists within
	# this application

	/**
	 * function to return the results of an options query as array. This function assumes that
	 * the query returns two columns optionvalue and optiontext which correspond to the corresponding key
	 * and values respectively. 
	 * 
	 * The selection of the names is to avoid name collisions with database reserved words
	 * 
	 * @param String $query The database query
	 * 
	 * @return Array of values for the query 
	 */
	function getOptionValuesFromDatabaseQuery($query) {
		$conn = Doctrine_Manager::connection(); 
		$result = $conn->fetchAll($query);
		$valuesarray = array();
		foreach ($result as $value) {
			$valuesarray[$value['optionvalue']]	= htmlentities(preg_replace('/[^a-z\d ]/i', '', $value['optiontext']));
		}
		return decodeHtmlEntitiesInArray($valuesarray);
	}
        # function to generate months
	function getAllMonths() {
		$months = array(
		"January" => "January",		
		"February" => "February",
		"March" => "March",
		"April" => "April",
		"May" => "May",		
		"June" => "June",
		"July" => "July",
		"August" => "August",
		"September" => "September",		
		"October" => "October",
		"November" => "November",
		"December" => "December"	
		);
		return $months;
	}
	
	# function to generate months
	function getAllMonthsAsNumbers() {
		$months = array(
		"01" => "January",		
		"02" => "February",
		"03" => "March",
		"04" => "April",
		"05" => "May",		
		"06" => "June",
		"07" => "July",
		"08" => "August",
		"09" => "September",		
		"10" => "October",
		"11" => "November",
		"12" => "December"	
		);
		return $months;
	}
	# split a date into day month and year
	function splitDate($date) {
		if(isEmptyString($date)){
			return array();
		}
		$date = date('Y-n-j',strtotime($date));
		$date_parts = explode('-', $date);
		// debugMessage($date_parts);
		return $date_parts;	
	}
	# function to generate months
	function getMonthsAsNumbers() {
		$months = array(
		"01" => "01",		
		"02" => "02",
		"03" => "03",
		"04" => "04",
		"05" => "05",		
		"06" => "06",
		"07" => "07",
		"08" => "08",
		"09" => "09",		
		"10" => "10",
		"11" => "11",
		"12" => "12"	
		);
		return $months;
	}
	# function to generate months short names
	function getAllMonthsAsShortNames() {
		$months = array(
		"1" => "Jan",		
		"2" => "Feb",
		"3" => "Mar",
		"4" => "Apr",
		"5" => "May",		
		"6" => "Jun",
		"7" => "Jul",
		"8" => "Aug",
		"9" => "Sept",		
		"10" => "Oct",
		"11" => "Nov",
		"12" => "Dec"	
		);
		return $months;
	}
	# function to generate months short names
	function getAllMonthsAsShortNamesAndAbsoluteValues() {
		$months = array(
		"01" => "Jan",		
		"02" => "Feb",
		"03" => "Mar",
		"04" => "Apr",
		"05" => "May",		
		"06" => "Jun",
		"07" => "Jul",
		"08" => "Aug",
		"09" => "Sept",		
		"10" => "Oct",
		"11" => "Nov",
		"12" => "Dec"	
		);
		return $months;
	}

	function getMonthName($number) {
		$months = getAllMonthsAsNumbers();
		return $months[$number];
	}
	
	# function to generate years
	function getAllYears() {				
		$aconfig = Zend_Registry::get("config"); 
		$years = array(); 
		$start_year = date("Y") - $aconfig->dateandtime->mindate;
		
		$end_year = date("Y") + $aconfig->dateandtime->maxdate;
		for($i = $start_year; $i <= $end_year; $i++) {
			$years[$i] = $i; 
		}		
		//return the years in descending order from the last year to the first and add true to maintain the array keys
		return array_reverse($years, true);
	}
	
	# function to generate future years
	function getFutureYears() {				
		$aconfig = Zend_Registry::get("config"); 
		$years = array(); 
		$start_year = date("Y");
		
		$end_year = date("Y") + $aconfig->dateandtime->mindate;
		for($i = $start_year; $i <= $end_year; $i++) {
			$years[$i] = $i; 
		}		
		//return the years in descending order from the last year to the first and add true to maintain the array keys
		return $years;
	}
        # function to generate years
	function getMultipleYears() {				
		$aconfig = Zend_Registry::get("config"); 
		$years = array(); 
		$start_year = date("Y") - $aconfig->dateandtime->mindateofbirth;
		
		$end_year = date("Y");
		for($i = $start_year; $i <= $end_year; $i++) {
			$years[$i] = $i; 
		}		
		//return the years in descending order from the last year to the first and add true to maintain the array keys
		return array_reverse($years, true);
	}
	 # function to generate years
	function getSubscribeBirthYears() {				
		$aconfig = Zend_Registry::get("config"); 
		$years = array(); 
		$start_year = (date("Y")) - 113;
		
		$end_year = (date("Y") - 15);
		for($i = $start_year; $i <= $end_year; $i++) {
			$years[$i] = $i; 
		}		
		//return the years in descending order from the last year to the first and add true to maintain the array keys
		return array_reverse($years, true);
	}
	# function to generate years
	function getMonthDays() {
		$days = array(); 
		$start_day = 1;
	
		$end_day = 31;
		for($i = $start_day; $i <= $end_day; $i++) {
			$days[$i] = $i; 
		}		
		//return the years in descending order from 2009 down to the start year and true to maintain the array keys
		return $days;
	}
	# get the first day of a month
	function getFirstDayOfMonth($month,$year) {
		return date("Y-m-d", mktime(0,0,0, $month,1,$year));
	}
	# get the last day of a month
	function getLastDayOfMonth($month,$year) {
		return date("Y-m-d", mktime(0,0,0, $month+1,0,$year));
	}
	# get the first day of current month
	function getFirstDayOfNextMonth($month,$year) {
		return date("Y-m-d", mktime(0,0,0, $month,2,$year));
	}
	# get the last day of the next month
	function getLastDayOfNextMonth($month,$year) {
		return date("Y-m-d", mktime(0,0,0, $month+2,0,$year));
	}
	# get the first day of last month
	function getFirstDayOfLastMonth($month,$year) {
		return date("Y-m-d", mktime(0,0,0, $month,-1,$year));
	}
	# get the last day of the last month
	function getLastDayOfLastMonth($month,$year) {
		return date("Y-m-d", mktime(0,0,0, $month-1,0,$year));
	}
	
	/**
	 * Return an array containing the 2 digit US state codes and names of the states
	 *
	 * @return Array Containing 2 digit state codes as the key, and the name of a US state as the value
	 */
	function getStates() {
		$state_list = array('AL'=>"Alabama",  
			'AK'=>"Alaska",  
			'AZ'=>"Arizona",  
			'AR'=>"Arkansas",  
			'CA'=>"California",  
			'CO'=>"Colorado",  
			'CT'=>"Connecticut",  
			'DE'=>"Delaware",  
			'DC'=>"District Of Columbia",  
			'FL'=>"Florida",  
			'GA'=>"Georgia",  
			'HI'=>"Hawaii",  
			'ID'=>"Idaho",  
			'IL'=>"Illinois",  
			'IN'=>"Indiana",  
			'IA'=>"Iowa",  
			'KS'=>"Kansas",  
			'KY'=>"Kentucky",  
			'LA'=>"Louisiana",  
			'ME'=>"Maine",  
			'MD'=>"Maryland",  
			'MA'=>"Massachusetts",  
			'MI'=>"Michigan",  
			'MN'=>"Minnesota",  
			'MS'=>"Mississippi",  
			'MO'=>"Missouri",  
			'MT'=>"Montana",
			'NE'=>"Nebraska",
			'NV'=>"Nevada",
			'NH'=>"New Hampshire",
			'NJ'=>"New Jersey",
			'NM'=>"New Mexico",
			'NY'=>"New York",
			'NC'=>"North Carolina",
			'ND'=>"North Dakota",
			'OH'=>"Ohio",  
			'OK'=>"Oklahoma",  
			'OR'=>"Oregon",  
			'PA'=>"Pennsylvania",  
			'RI'=>"Rhode Island",  
			'SC'=>"South Carolina",  
			'SD'=>"South Dakota",
			'TN'=>"Tennessee",  
			'TX'=>"Texas",  
			'UT'=>"Utah",  
			'VT'=>"Vermont",  
			'VA'=>"Virginia",  
			'WA'=>"Washington",  
			'WV'=>"West Virginia",  
			'WI'=>"Wisconsin",  
			'WY'=>"Wyoming");
		
		return $state_list; 
	}
	/**
	 * Return full name of a US state
	 *
	 * @return String Name of state
	 */
	function getFullStateName($state) {
		$statesarray = getStates();
		return $statesarray[$state];
	}
	/**
	 * Return an array containing the countries in the world
	 *
	 * @return Array Containing 2 digit country codes as the key, and the name of a couuntry as the value
	 */
	function getCountries(){
		$country_list = array(
			"GB" => "United Kingdom",
			"US" => "United States",
			"AF" => "Afghanistan",
			"AL" => "Albania",
			"DZ" => "Algeria",
			"AS" => "American Samoa",
			"AD" => "Andorra",
			"AO" => "Angola",
			"AI" => "Anguilla",
			"AQ" => "Antarctica",
			"AG" => "Antigua And Barbuda",
			"AR" => "Argentina",
			"AM" => "Armenia",
			"AW" => "Aruba",
			"AU" => "Australia",
			"AT" => "Austria",
			"AZ" => "Azerbaijan",
			"BS" => "Bahamas",
			"BH" => "Bahrain",
			"BD" => "Bangladesh",
			"BB" => "Barbados",
			"BY" => "Belarus",
			"BE" => "Belgium",
			"BZ" => "Belize",
			"BJ" => "Benin",
			"BM" => "Bermuda",
			"BT" => "Bhutan",
			"BO" => "Bolivia",
			"BA" => "Bosnia And Herzegowina",
			"BW" => "Botswana",
			"BV" => "Bouvet Island",
			"BR" => "Brazil",
			"IO" => "British Indian Ocean Territory",
			"BN" => "Brunei Darussalam",
			"BG" => "Bulgaria",
			"BF" => "Burkina Faso",
			"BI" => "Burundi",
			"KH" => "Cambodia",
			"CM" => "Cameroon",
			"CA" => "Canada",
			"CV" => "Cape Verde",
			"KY" => "Cayman Islands",
			"CF" => "Central African Republic",
			"TD" => "Chad",
			"CL" => "Chile",
			"CN" => "China",
			"CX" => "Christmas Island",
			"CC" => "Cocos (Keeling) Islands",
			"CO" => "Colombia",
			"KM" => "Comoros",
			"CG" => "Congo",
			"CD" => "Congo, The Democratic Republic Of The",
			"CK" => "Cook Islands",
			"CR" => "Costa Rica",
			"CI" => "Cote D'Ivoire",
			"HR" => "Croatia (Local Name: Hrvatska)",
			"CU" => "Cuba",
			"CY" => "Cyprus",
			"CZ" => "Czech Republic",
			"DK" => "Denmark",
			"DJ" => "Djibouti",
			"DM" => "Dominica",
			"DO" => "Dominican Republic",
			"TP" => "East Timor",
			"EC" => "Ecuador",
			"EG" => "Egypt",
			"SV" => "El Salvador",
			"GQ" => "Equatorial Guinea",
			"ER" => "Eritrea",
			"EE" => "Estonia",
			"ET" => "Ethiopia",
			"FK" => "Falkland Islands (Malvinas)",
			"FO" => "Faroe Islands",
			"FJ" => "Fiji",
			"FI" => "Finland",
			"FR" => "France",
			"FX" => "France, Metropolitan",
			"GF" => "French Guiana",
			"PF" => "French Polynesia",
			"TF" => "French Southern Territories",
			"GA" => "Gabon",
			"GM" => "Gambia",
			"GE" => "Georgia",
			"DE" => "Germany",
			"GH" => "Ghana",
			"GI" => "Gibraltar",
			"GR" => "Greece",
			"GL" => "Greenland",
			"GD" => "Grenada",
			"GP" => "Guadeloupe",
			"GU" => "Guam",
			"GT" => "Guatemala",
			"GN" => "Guinea",
			"GW" => "Guinea-Bissau",
			"GY" => "Guyana",
			"HT" => "Haiti",
			"HM" => "Heard And Mc Donald Islands",
			"VA" => "Holy See (Vatican City State)",
			"HN" => "Honduras",
			"HK" => "Hong Kong",
			"HU" => "Hungary",
			"IS" => "Iceland",
			"IN" => "India",
			"ID" => "Indonesia",
			"IR" => "Iran (Islamic Republic Of)",
			"IQ" => "Iraq",
			"IE" => "Ireland",
			"IL" => "Israel",
			"IT" => "Italy",
			"JM" => "Jamaica",
			"JP" => "Japan",
			"JO" => "Jordan",
			"KZ" => "Kazakhstan",
			"KE" => "Kenya",
			"KI" => "Kiribati",
			"KP" => "Korea, Democratic People's Republic Of",
			"KR" => "Korea, Republic Of",
			"KW" => "Kuwait",
			"KG" => "Kyrgyzstan",
			"LA" => "Lao People's Democratic Republic",
			"LV" => "Latvia",
			"LB" => "Lebanon",
			"LS" => "Lesotho",
			"LR" => "Liberia",
			"LY" => "Libyan Arab Jamahiriya",
			"LI" => "Liechtenstein",
			"LT" => "Lithuania",
			"LU" => "Luxembourg",
			"MO" => "Macau",
			"MK" => "Macedonia, Former Yugoslav Republic Of",
			"MG" => "Madagascar",
			"MW" => "Malawi",
			"MY" => "Malaysia",
			"MV" => "Maldives",
			"ML" => "Mali",
			"MT" => "Malta",
			"MH" => "Marshall Islands",
			"MQ" => "Martinique",
			"MR" => "Mauritania",
			"MU" => "Mauritius",
			"YT" => "Mayotte",
			"MX" => "Mexico",
			"FM" => "Micronesia, Federated States Of",
			"MD" => "Moldova, Republic Of",
			"MC" => "Monaco",
			"MN" => "Mongolia",
			"MS" => "Montserrat",
			"MA" => "Morocco",
			"MZ" => "Mozambique",
			"MM" => "Myanmar",
			"NA" => "Namibia",
			"NR" => "Nauru",
			"NP" => "Nepal",
			"NL" => "Netherlands",
			"AN" => "Netherlands Antilles",
			"NC" => "New Caledonia",
			"NZ" => "New Zealand",
			"NI" => "Nicaragua",
			"NE" => "Niger",
			"NG" => "Nigeria",
			"NU" => "Niue",
			"NF" => "Norfolk Island",
			"MP" => "Northern Mariana Islands",
			"NO" => "Norway",
			"OM" => "Oman",
			"PK" => "Pakistan",
			"PW" => "Palau",
			"PA" => "Panama",
			"PG" => "Papua New Guinea",
			"PY" => "Paraguay",
			"PE" => "Peru",
			"PH" => "Philippines",
			"PN" => "Pitcairn",
			"PL" => "Poland",
			"PT" => "Portugal",
			"PR" => "Puerto Rico",
			"QA" => "Qatar",
			"RE" => "Reunion",
			"RO" => "Romania",
			"RU" => "Russian Federation",
			"RW" => "Rwanda",
			"KN" => "Saint Kitts And Nevis",
			"LC" => "Saint Lucia",
			"VC" => "Saint Vincent And The Grenadines",
			"WS" => "Samoa",
			"SM" => "San Marino",
			"ST" => "Sao Tome And Principe",
			"SA" => "Saudi Arabia",
			"SN" => "Senegal",
			"SC" => "Seychelles",
			"SL" => "Sierra Leone",
			"SG" => "Singapore",
			"SK" => "Slovakia (Slovak Republic)",
			"SI" => "Slovenia",
			"SB" => "Solomon Islands",
			"SO" => "Somalia",
			"ZA" => "South Africa",
			"GS" => "South Georgia, South Sandwich Islands",
			"ES" => "Spain",
			"LK" => "Sri Lanka",
			"SH" => "St. Helena",
			"PM" => "St. Pierre And Miquelon",
			"SD" => "Sudan",
			"SR" => "Suriname",
			"SJ" => "Svalbard And Jan Mayen Islands",
			"SZ" => "Swaziland",
			"SE" => "Sweden",
			"CH" => "Switzerland",
			"SY" => "Syrian Arab Republic",
			"TW" => "Taiwan",
			"TJ" => "Tajikistan",
			"TZ" => "Tanzania, United Republic Of",
			"TH" => "Thailand",
			"TG" => "Togo",
			"TK" => "Tokelau",
			"TO" => "Tonga",
			"TT" => "Trinidad And Tobago",
			"TN" => "Tunisia",
			"TR" => "Turkey",
			"TM" => "Turkmenistan",
			"TC" => "Turks And Caicos Islands",
			"TV" => "Tuvalu",
			"UG" => "Uganda",
			"UA" => "Ukraine",
			"AE" => "United Arab Emirates",
			"UM" => "United States Minor Outlying Islands",
			"UY" => "Uruguay",
			"UZ" => "Uzbekistan",
			"VU" => "Vanuatu",
			"VE" => "Venezuela",
			"VN" => "Viet Nam",
			"VG" => "Virgin Islands (British)",
			"VI" => "Virgin Islands (U.S.)",
			"WF" => "Wallis And Futuna Islands",
			"EH" => "Western Sahara",
			"YE" => "Yemen",
			"YU" => "Yugoslavia",
			"ZM" => "Zambia",
			"ZW" => "Zimbabwe"
		);
		return $country_list;
	}
	/**
	 * Return full name of a country
	 *
	 * @return String Name of country
	 */
	function getFullCountryName($countrycode) {
		$countriesarray = getCountries();
		return $countriesarray[$countrycode];
	}
	# function to generate ethinicity dropdown list 
	function getEthinicities(){
		$ethn_list = array(
			"1" => "Afghan",
			"2" => "Albanian",
			"3" => "Algerian",
			"4" => "American",
			"5" => "Andorran",
			"6" => "Angolan",
			"7" => "Antiguans",
			"8" => "Argentinean",
			"9" => "Armenian",
			"10" => "Australian"
		);
		return $ethn_list;
	}
	/**
	 * Return an array containing the 2 digit US state codes and names of the states
	 *
	 * @return Array Containing 2 digit state codes as the key, and the name of a US state as the value
	 */
	function getLanguages() {
		$list = array(
			"1" => "English",
			"2" => "Luganda",
			"3" => "Lusoga",
			"4" => "Mandarin Chinese",
			"6" => "Spanish",
			"7" => "Hindi",
			"8" => "Portuguese",
			"9" => "Russian",
			"10" => "French",
			"11" => "Bengali",
			"12" => "Malay",
			"13" => "German",
			"14" => "Japanese",
			"15" => "Italian",
			"16" => "Persian",
			"17" => "Panjabi",
			"18" => "Urdu",
			"19" => "Marathi",
			"20" => "Turkish",
			"21" => "Telugu",
			"22" => "Egyptian Arabic",
			"23" => "Javanese",
			"24" => "Wu Chinese",
			"25" => "Korean",
			"26" => "Thai",
			"27" => "Vietnamese",
			"28" => "Yue Chinese",
			"29" => "Tamil",
			"30" => "Maghrebi Arabic",
			"31" => "Min Nan Chinese",
			"32" => "Polish",
			"33" => "Gugarati",
			"34" => "Jin Yu Chinese",
			"35" => "Ukrainian",
			"36" => "Hausa",
			"37" => "Kannada",
			"38" => "Pashto",
			"39" => "Xiang Chinese",
			"40" => "Levantine Arabic",
			"41" => "Malayalam",
			"42" => "Hakka Chinese",
			"43" => "Berber",
			"44" => "Amharic",
			"45" => "Oromo",
			"46" => "Burmese",
			"47" => "Oriya",
			"48" => "Nepali",
			"49" => "Sundanese",
			"50" => "Bhojpuri",
			"51" => "Tagalog",
			"52" => "Romanian",
			"53" => "Kurdish",
			"54" => "Haryanvi",
			"55" => "Dutch",
			"56" => "Azerbaijani",
			"57" => "Yoruba",
			"58" => "Serbo-croatian",
			"59" => "Uzbek",
			"60" => "Gan Chinese",
			"61" => "Assamese",
			"62" => "Sindhi",
			"63" => "Malagasy",
			"64" => "Khme",
			"65" => "Igbo",
			"66" => "Sa'ide Arabic",
			"67" => "Sudanese Arabic",
			"68" => "Greek",
			"69" => "Saraiki",
			"70" => "Somali",
			"71" => "Cebuano",
			"72" => "Hungarian",
			"73" => "Chitagonian",
			"74" => "Mesopotamian Arabic",
			"75" => "Zhuang Chinese",
			"76" => "Madurese",
			"77" => "Fula",
			"78" => "Shinhala",
			"79" => "Kazakh",
			"80" => "Swedish",
			"81" => "Marwari",
			"82" => "Czech",
			"83" => "Hiligaynon",
			"84" => "Magadhi",
			"85" => "Haitian Creole",
			"86" => "Quechua",
			"87" => "Chhattisgarh",
			"88" => "Uyghur",
			"89" => "Dekhni",
			"90" => "Min Bei Chinese",
			"91" => "Uyghur",
			"92" => "Belarusian",
			"93" => "kinyarwanda",
			"94" => "Ilokano",
			"95" => "Hebrew",
			"96" => "Bulgarian",
			"97" => "Najdi Arabic",
			"98" => "Zulu",
			"99" => "Akan",
			"100" => "Gulf Arabic",
			"101" => "Shona",
			"102" => "Tatar",
			"103" => "Fulfulde",
			"104" => "Acholi",
			"105" => "Adhola",
			"106" => "Alur",
			"107" => "Amba",
			"108" => "Aringa",
			"109" => "Bari",
			"110" => "Chiga",
			"111" => "Gujarati",
			"112" => "Gungu",
			"113" => "Gwere",
			"114" => "Hindi",
			"115" => "Ik",
			"116" => "Kakwa",
			"117" => "Karamojong",
			"118" => "Kenyi",
			"119" => "Konzo",
			"120" => "Kumam",
			"121" => "Kupsabiny",
			"122" => "Lango",
			"123" => "Lendu",
			"124" => "Lugbara",
			"125" => "Ma'di",
			"126" => "Masaaba",
			"127" => "Ndo",
			"128" => "Nubi",
			"129" => "Nyang'i",
			"130" => "Nyankore",
			"131" => "Nyole",
			"132" => "Nyoro",
			"133" => "Pökoot",
			"134" => "Rundi",
			"135" => "Ruuli",
			"136" => "Rwanda",
			"137" => "Saamia",
			"138" => "Singa",
			"139" => "Soga",
			"140" => "Soo",
			"141" => "Swahili",
			"142" => "Talinga-Bwisi",
			"143" => "Teso",
			"144" => "Tooro"
		);
		
		return $list; 
	}
	# function to generate eye color dropdown list
	function getHairColors() {
		$haircolor_list = array(
			"1" => "Brown",
			"2" => "Black",
			"3" => "White",
			"4" => "Sandy",
			"5" => "Gray or Partially Gray",
			"6" => "Red/Auburn",
			"7" => "Blond/Strawberry",
			"8" => "Blue",
			"9" => "Green",
			"10" => "Orange",
			"11" => "Pink",
			"12" => "Purple",
			"13" => "Partly or Completely Bald",
			"14" => "Other"
		);
		return $haircolor_list;
	}
	# function to generate eye color dropdown list
	function getEyeColors() {
		$eyecolor_list = array(
			"1" => "Black",
			"2" => "Blue",
			"3" => "Brown",
			"4" => "Gray",
			"5" => "Green",
			"6" => "Hazel",
			"7" => "Maroon",
			"8" => "Pink",
			"9" => "Multicolored",
			"10" => "Other"
		);
		return $eyecolor_list;
	}
	# function to generate religion dropdown list
	function getReligions() {
		$values = array(
				"1" => "African Traditional & Diasporic",
				"2" => "Agnostic",
				"3" => "Atheist",
				"4" => "Bahai",
				"5" => "Buddhism",
				"6" => "Cao Dai",
				"7" => "Chinese traditional religion",
				"8" => "Christianity",
				"9" => "Hinduism",
				"10" => "Islam",
				"11" => "Jainism",
				"12" => "Juche",
				"13" => "Judaism",
				"14" => "Neo-Paganism",
				"15" => "Nonreligious",
				"16" => "Rastafarianism",
				"17" => "Secular",
				"18" => "Shinto",
				"19" => "Sikhism",
				"20" => "Spiritism",
				"21" => "Tenrikyo",
				"22" => "Unitarian-Universalism",
				"23" => "Zoroastrianism",
				"24" => "Primal-Indigenous"
			);
			
			asort($values);
			return $values;
		}
		# function to generate height list in feet
		function getHeightRangeInFeet() {				
			$rangearray = array(); 
			$start = 1;
			$end = 8;
			for($i = $start; $i <= $end; $i++) {
				$rangearray[$i] = $i; 
			}		
			//return the years in descending order from the last year to the first and add true to maintain the array keys
			return array_reverse($rangearray, true);
		}
		# function to generate height list in inches
		function getHeightRangeInInches() {				
			$rangearray = array(); 
			$start = 0;
			$end = 11;
			for($i = $start; $i <= $end; $i++) {
				$rangearray[$i] = $i; 
			}		
			//return the years in descending order from the last year to the first and add true to maintain the array keys
			return array_reverse($rangearray, true);
		}		
		/**
		 * Generate an array containing a list of all partners/wives for a person
		 * 
		 * @param Integer $personid
		 */
		function getAllFemalePartnersForPerson($personid) {
			$valuesquery = "SELECT f.id as optionvalue, concat(p.firstname,' ',p.lastname) as optiontext FROM family f inner join person p on (f.motherid = p.id) WHERE f.fatherid  = '".$personid."' ";
			//debugMessage($valuesquery);
			return getOptionValuesFromDatabaseQuery($valuesquery);
		}
		/**
		 * Generate an array containing a list of all partners for a person
		 * 
		 * @param Integer $personid
		 */
		function getAllMalePartnersForPerson($personid) {
			$valuesquery = "SELECT f.id as optionvalue, concat(p.firstname,' ',p.lastname) as optiontext FROM family f inner join person p on (f.fatherid = p.id) WHERE f.motherid  = '".$personid."' ";
			// debugMessage($valuesquery);
			return getOptionValuesFromDatabaseQuery($valuesquery);
		}
		/**
		 * Generate an array containing a list of other male partners for person other than father of focus
		 * 
		 * @param Integer $personid
		 */
		function getOtherMalePartnersForPerson($personid, $excludedfamilyid){
			$excludequery = "";
			if (!isEmptyString($excludedfamilyid)) {
				$excludequery = " AND f.id <> ".$excludedfamilyid;
			}
			$valuesquery = "SELECT f.id as optionvalue, concat(p.firstname,' ',p.lastname) as optiontext FROM family f inner join person p on (f.fatherid = p.id) WHERE f.motherid  = '".$personid."' ".$excludequery;
			// debugMessage($valuesquery);
			return getOptionValuesFromDatabaseQuery($valuesquery);
		}
		/**
		 * Generate an array containing a list of other female partners for person other than father of focus
		 * 
		 * @param Integer $personid
		 */
		function getOtherFemalePartnersForPerson($personid, $excludedfamilyid){
			$excludequery = "";
			if (!isEmptyString($excludedfamilyid)) {
				$excludequery = " AND f.id <> '".$excludedfamilyid."' ";
			}
			$valuesquery = "SELECT f.id as optionvalue, concat(p.firstname,' ',p.lastname) as optiontext FROM family f inner join person p on (f.motherid = p.id) WHERE f.fatherid  = '".$personid."' ".$excludequery;
			// debugMessage($valuesquery);
			return getOptionValuesFromDatabaseQuery($valuesquery);
		}
		/**
		 * Generate an array containing a list of male partner families for person
		 * 
		 * @param Integer $personid
		 */
		function getAllMalePartnerFamilies($motherid){
			$valuesquery = "SELECT f.id as optionvalue, concat(p.firstname,' ',p.lastname) as optiontext FROM family f left join person p on (f.fatherid = p.id) WHERE f.motherid = '".$motherid."' ";
			// debugMessage($valuesquery);
			return getOptionValuesFromDatabaseQuery($valuesquery);
		}
		/**
		 * Generate an array containing a list of female partner families for person
		 * 
		 * @param Integer $personid
		 */
		function getAllFemalePartnerFamilies($fatherid){
			$valuesquery = "SELECT f.id as optionvalue, concat(p.firstname,' ',p.lastname) as optiontext FROM family f left join person p on (f.motherid = p.id) WHERE f.fatherid = '".$fatherid."' ";
		// debugMessage($valuesquery);
		return getOptionValuesFromDatabaseQuery($valuesquery);
	}
	/**
	 * Get the ssigas in the specified clan 
	 * 
	 * @param Integer $clanid The id of the clan 
	 * 
	 * @return Array  
	 */
	function getSsigasInClan($clanid) {
		if (isEmptyString($clanid)) {
			return array(); 
		}
		$query = "SELECT id as optionvalue, fullname as optiontext FROM organisation WHERE (clanid = '".$clanid."' AND type = 2) OR (fullname = 'Other') OR (fullname = 'No Not Know') ORDER BY optionvalue"; 
		return getOptionValuesFromDatabaseQuery($query);
	}
	/**
	 * Get the places in the specified ssaza 
	 * 
	 * @param Integer $ssazaid The id of the ssaza 
	 * 
	 * @return Array  
	 */
	function getPlacesInSsaza($ssazaid) {
		if (isEmptyString($ssazaid)) {
			return array(); 
		}
		$query = "SELECT id as optionvalue, trim(name) as optiontext FROM place where (ssazaid = '".$ssazaid."' AND type = 2) ORDER BY optiontext "; 
		return getOptionValuesFromDatabaseQuery($query);
	}
	/**
	 * Generate an array containing a list of relationships
	 * 
	 * @param Integer $personid
	 */
	function getRelationShips(){
		$valuesquery = "SELECT r.id as optionvalue, concat(r.ename, ' (', r.lname, ')') as optiontext FROM relationship r where r.ename <> 'Focus' ORDER BY r.ename ASC ";
		// debugMessage($valuesquery);
		return getOptionValuesFromDatabaseQuery($valuesquery);
	}
	# determine signup contact categories
	function getContactUsCategories(){
		$array = array('Feedback'=>'Feedback', 'Ask a Question'=>'Ask a Question', 'Submit a Bug'=>'Submit a Bug', 'Sign up Problems'=>'Sign up Problems', 'Account compromised'=>'Account compromised', 'Failed to Login'=>'Failed to Login');
		ksort($array);
		$array['Other'] = 'Other';
		return $array;
	}
	function getViolationTypes(){
		$array = array('1'=>'Attempt to hack into my account or another account.', '2'=>'A person pretending to be me.', '3'=>'A person pretending to be a Muganda when both his or her father and mother are not Baganda','7'=>'A person using a fake or pseudo account.', '4'=>'An underage child who uses GandaAncestry without parental supervision.', '5'=>'Unauthorized copying and using content from GandaAncestry for personal commercial gain.', '6'=>'Other violation.');
		
		return $array;
	}
?>