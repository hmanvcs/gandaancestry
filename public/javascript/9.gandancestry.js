// Make columns heights equal
function equalHeight(group) {
    tallest = 0;
    group.each(function() {
        thisHeight = $(this).height();
        if(thisHeight > tallest) {
            tallest = thisHeight;
        }
    });
    group.height(tallest);
}
// function to open a pop-up window with specific width
function openPopUpWindow(URL) {
	aWindow=window.open(URL,"newwindow","toolbar=no,scrollbars=no,status=no,location=no,resizable=no,menubar=no,width=650,height=500,left=400,top=100");
}

// function to open a pop-up window with specific width
function openPopUpWindowWithOptions(URL, width, height) {
	aWindow=window.open(URL,"newwindow","toolbar=no,scrollbars=no,status=no,location=no,resizable=no,menubar=no,width="+width+",height="+height+",left=400,top=100");
}
// function to obsfuscate email addresses from spammers
function contactUnobsfuscate() {
	
	// find all links in HTML
	var link = document.getElementsByTagName && document.getElementsByTagName("a");
	var contact, e;
	
	// examine all links
	for (e = 0; link && e < link.length; e++) {
	
		// does the link have use a class named "contact"
		if ((" "+link[e].className+" ").indexOf(" contact ") >= 0) {
		
			// get the obfuscated contact address
			contact = link[e].firstChild.nodeValue.toLowerCase() || "";
			
			// transform into real contact address
			contact = contact.replace(/dot/ig, ".");
			contact = contact.replace(/\(at\)/ig, "@");
			contact = contact.replace(/\s/g, "");
			
			// is contact valid?
			if (/^[^@]+@[a-z0-9]+([_\.\-]{0,1}[a-z0-9]+)*([\.]{1}[a-z0-9]+)+$/.test(contact)) {
			
				// change into a real mailto link
				link[e].href = "mailto:" + contact;
				link[e].firstChild.nodeValue = contact;
		
			}
		}
	}
}
window.onload = contactUnobsfuscate;

//close popup window and reload parent
function closeWindowAndReloadParent(){
	$(window).unload(function () { 
		//alert("Bye now!"); 
		window.opener.location.reload();			
	});
}

//if the default search term is present, clear it
function clearValue(fieldid) {
	if($("#" + fieldid).val() == $("#" + fieldid).attr('title')) {
		$("#" + fieldid).val('');
	} 
}
// if the default search term is empty, set it
function setValue(fieldid) {
	if ($("#" + fieldid).val() == "") {
		$("#" + fieldid).val($("#" + fieldid).attr('title'));
	} 
}

/*
 * Disable the fields specified and add the class disabledfield
 * 
 * @param fieldid - The ID of the field to be disabled
 */
/*
 * Disable the fields specified and add the class disabledfield
 * 
 * @param fieldid - The ID of the field to be disabled
 */
function disableField(fieldid) {
	// disable the respective field and add the class disabled field		
	$("#" + fieldid).attr("disabled", "disabled").addClass('disabledfield');
}
/*
 * Disable the fields specified and add the class disabledfield
 * 
 * @param fieldids - The IDs of the fields to be disabled
 */
function multipleDisableField(fieldids) {	
	var fieldsarray = fieldids.replace(/ /g,'').split(',');
	// alert(fieldsarray);
	$.each(fieldsarray, function(index, value) {
		// alert(index + ': ' + value);
		disableField(value)
	});
}
/*
 * Enable the fields specified and remove the class disabledfield
 * 
 * @param fieldid - The ID of the field to be enabled
 */
function enableField(fieldid) {
	// // hide the respective field and remove the class disabled field
	$("#" + fieldid).attr("disabled", false).removeClass('disabledfield');	
} 
/*
 * Enable the multiple fields specified and remove the class disabledfield
 * 
 * @param fieldids - The IDs of the fields to be enabled
 */ 
function multipleEnableField(fieldids) {	
	var fieldsarray = fieldids.replace(/ /g,'').split(',');
	// alert(fieldsarray);
	$.each(fieldsarray, function(index, value) {
		// alert(index + ': ' + value);
		enableField(value)
	});
}
/*
 * Hide the container with the specified id and disable all input fields in it
 * 
 * @param fieldid - The ID of the container to be hidden
 */
function disableContainerByID(fieldid) {
	// hide the respective tbody and disable any HTML controls
	$("#" + fieldid).hide();
	$("#" + fieldid + " input, #" + fieldid + " select, #" + fieldid + " textarea").attr("disabled", true);
}
/*
 * Hide the containers with the specified id and disable all input fields in it
 * 
 * @param fieldids - The IDs of the containers to be hidden
 */
function multipleDisableContainerByID(fieldids) {	
	var fieldsarray = fieldids.replace(/ /g,'').split(',');
	// alert(fieldsarray);
	$.each(fieldsarray, function(index, value) {
		// alert(index + ': ' + value);
		disableContainerByID(value)
	});
}
/*
 * Show the container with the specified id and enable all input fields in it
 * 
 * @param fieldid - The ID of the container to be shown
 */
function enableContainerByID(fieldid) {
	// hide the respective tbody and disable any HTML controls
	$("#" + fieldid).show();
	$("#" + fieldid + " input, #" + fieldid + " select, #" + fieldid + " textarea").attr("disabled", false);
}
/*
 * Show the container with the specified id and enable all input fields in it
 * 
 * @param fieldids - The IDs of the containers to be shown
 */
function multipleEnableContainerByID(fieldids) {	
	var fieldsarray = fieldids.replace(/ /g,'').split(',');
	// alert(fieldsarray);
	$.each(fieldsarray, function(index, value) {
		// alert(index + ': ' + value);
		enableContainerByID(value)
	});
}
 /*
 * Hide the container with the specified class and disable all input fields in it
 * 
 * @param fieldid - The class of the container to be hidden
 */
function disableContainerByClass(classid) {
	// hide the respective tbody and disable any HTML controls
	$("." + classid).hide();
	$("." + classid + " input, ." + classid + " select, ." + classid + " textarea").attr("disabled", true);
}
/*
 * Hide the container with the specified class and disable all input fields in it
 * 
 * @param fieldids - The class of the containers to be hidden
 */
function multipleDisableContainerByClass(classids){
	var fieldsarray = classids.replace(/ /g,'').split(',');
	// alert(fieldsarray);
	$.each(fieldsarray, function(index, value) {
		// alert(index + ': ' + value);
		disableContainerByClass(value)
	});
}
/*
 * Show the container with the specified class and enable all input fields in it
 * 
 * @param fieldid - The class of the container to be shown
 */
function enableContainerByClass(classid) {
	// hide the respective tbody and disable any HTML controls
	$("." + classid).show();
	$("." + classid + " input, ." + classid + " select, ." + classid + " textarea").attr("disabled", false);
}
/*
 * Show the container with the specified class and enable all input fields in it
 * 
 * @param fieldids - The class of the containers to be shown
 */
function multipleEnableContainerByClass(classids){
	var fieldsarray = classids.replace(/ /g,'').split(',');
	// alert(fieldsarray);
	$.each(fieldsarray, function(index, value) {
		// alert(index + ': ' + value);
		enableContainerByClass(value)
	});
}
/**
 * Resize the contents of the body to fit due to removal or addition of elements
 * which affect the height of the page
 */
function resizeContentForm() {
	equalHeight($("#contentcolumn, #leftcolumn, #rightcolumn"));
	$("#contentcolumn").css({'height': $("#contentcolumn").height() + 150});		
} 
function resizePageForm() {		
	equalHeight($("form, #contentcolumn"));
	$("#contentcolumn").css({'height': $("form").height() + 50});	
} 
// function to do nothing
function doNothing(){	
}
// remove empty and null values
function cleanArray(actual){
  var newArray = new Array();
  for(var i = 0; i<actual.length; i++){
      if (actual[i]){
        newArray.push(actual[i]);
    }
  }
  return newArray;
}
// remove duplicate values from array
function removeDuplicates(inputArray) {
	var i;
	var len = inputArray.length;
	var outputArray = [];
	var temp = {};

	for (i = 0; i < len; i++) {
		temp[inputArray[i]] = 0;
	}
	for (i in temp) {
		outputArray.push(i);
	}
	return outputArray;
}
// count occurence of a value in an array
function countValuesInArray(myArray, val){
	var results = new Array();
	for (var j=0; j<myArray.length; j++) {
		var key = myArray[j].toString(); // make it an associative array
		if (!results[key]) {
			results[key] = 1
		} else {
			results[key] = results[key] + 1;
		}
	}
	return results[val];
}
// general purpose function to see if an input value has been
// entered at all
function isEmptyString(inputStr) {
	if (inputStr == null || inputStr == "") {
		return true;
	}
	return false;
}
// base 64 javascript encode
function base64_encode (data) {    
    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
        ac = 0,
        enc = "",
        tmp_arr = [];

    if (!data) {
        return data;
    }

    data = this.utf8_encode(data + '');
    do { // pack three octets into four hexets
        o1 = data.charCodeAt(i++);
        o2 = data.charCodeAt(i++);
        o3 = data.charCodeAt(i++);

        bits = o1 << 16 | o2 << 8 | o3;

        h1 = bits >> 18 & 0x3f;
        h2 = bits >> 12 & 0x3f;
        h3 = bits >> 6 & 0x3f;
        h4 = bits & 0x3f;

        // use hexets to index into b64, and append result to encoded string
        tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
    } while (i < data.length);

    enc = tmp_arr.join('');
    var r = data.length % 3;
    return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
}
// utf8 javascript encode
function utf8_encode (argString) {
    if (argString === null || typeof argString === "undefined") {
        return "";
    }

    var string = (argString + ''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");
    var utftext = "",
        start, end, stringl = 0;

    start = end = 0;
    stringl = string.length;
    for (var n = 0; n < stringl; n++) {
        var c1 = string.charCodeAt(n);
        var enc = null;

        if (c1 < 128) {
            end++;
        } else if (c1 > 127 && c1 < 2048) {
            enc = String.fromCharCode((c1 >> 6) | 192) + String.fromCharCode((c1 & 63) | 128);
        } else {
            enc = String.fromCharCode((c1 >> 12) | 224) + String.fromCharCode(((c1 >> 6) & 63) | 128) + String.fromCharCode((c1 & 63) | 128);
        }
        if (enc !== null) {
            if (end > start) {
                utftext += string.slice(start, end);
            }
            utftext += enc;
            start = end = n + 1;
        }
    }

    if (end > start) {
        utftext += string.slice(start, stringl);
    }

    return utftext;
}
// base 64 javascript decode
function base64_decode (data) {    
    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
        ac = 0,
        dec = "",
        tmp_arr = [];

    if (!data) {
        return data;
    }

    data += '';
    do { // unpack four hexets into three octets using index points in b64
        h1 = b64.indexOf(data.charAt(i++));
        h2 = b64.indexOf(data.charAt(i++));
        h3 = b64.indexOf(data.charAt(i++));
        h4 = b64.indexOf(data.charAt(i++));

        bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

        o1 = bits >> 16 & 0xff;
        o2 = bits >> 8 & 0xff;
        o3 = bits & 0xff;

        if (h3 == 64) {
            tmp_arr[ac++] = String.fromCharCode(o1);
        } else if (h4 == 64) {
            tmp_arr[ac++] = String.fromCharCode(o1, o2);
        } else {
            tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
        }
    } while (i < data.length);

    dec = tmp_arr.join('');
    dec = this.utf8_decode(dec);

    return dec;
}
// utf8 javascript decode
function utf8_decode (str_data) {
    var tmp_arr = [],
        i = 0,
        ac = 0,
        c1 = 0,
        c2 = 0,
        c3 = 0;

    str_data += '';

    while (i < str_data.length) {
        c1 = str_data.charCodeAt(i);
        if (c1 < 128) {
            tmp_arr[ac++] = String.fromCharCode(c1);
            i++;
        } else if (c1 > 191 && c1 < 224) {
            c2 = str_data.charCodeAt(i + 1);
            tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
            i += 2;
        } else {
            c2 = str_data.charCodeAt(i + 1);
            c3 = str_data.charCodeAt(i + 2);
            tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
            i += 3;
        }
    }

    return tmp_arr.join('');
} 
function passwordStrength(password)
{
	var desc = new Array();
	desc[0] = "Very Weak";
	desc[1] = "Weak";
	desc[2] = "Better";
	desc[3] = "Medium";
	desc[4] = "Strong";
	desc[5] = "Strongest";

	var score   = 0;

	//if password bigger than 6 give 1 point
	if (password.length > 6) score++;

	//if password has both lower and uppercase characters give 1 point	
	if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;

	//if password has at least one number give 1 point
	if (password.match(/\d+/)) score++;

	//if password has at least one special caracther give 1 point
	if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) )	score++;

	//if password bigger than 12 give another 1 point
	if (password.length > 12) score++;

	 document.getElementById("passwordDescription").innerHTML = desc[score];
	 document.getElementById("passwordStrength").className = "strength" + score;
}
function validateEmail(email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if( !emailReg.test( email ) ) {
        return false;
    } else {
        return true;
    }
}
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
}// check that price is a valid number
function IsValidAmount(value){
	var ValidChars = "0123456789";
	var valid=true;
	var Char;
	
	for (i = 0; i < value.length && valid == true; i++){ 
		Char = value.charAt(i); 
		if (ValidChars.indexOf(Char) == -1) {
			valid = false;
		}
	}
	return valid;
}
function addCommas(nStr) {
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
// thousand separtor for amount fields
function formatAmount(num,dec,thou,pnt,curr1,curr2,n1,n2) {
	var x = Math.round(num * Math.pow(10,dec));
	if (x >= 0) n1=n2='';
	var y = (''+Math.abs(x)).split('');
	var z = y.length - dec; 
	if (z<0) z--; 
	for(var i = z; i < 0; i++) 
	y.unshift('0'); 
	if (z<0) z = 1; 
	y.splice(z, 0, pnt); 
	if(y[0] == pnt) y.unshift('0'); 
	while (z > 3) {
		z-=3; 
		y.splice(z,0,thou);
	}
	var r = curr1+n1+y.join('')+n2+curr2;return r;
}
Date.prototype.monthNames = [
	"January", "February", "March",
	"April", "May", "June",
	"July", "August", "September",
	"October", "November", "December"
];
Date.prototype.getMonthName = function() {
	return this.monthNames[this.getMonth()];
};
Date.prototype.getShortMonthName = function () {
	return this.getMonthName().substr(0, 3);
};
function sha1(str) {
  // http://kevin.vanzonneveld.net
  // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
  // + namespaced by: Michael White (http://getsprink.com)
  // +      input by: Brett Zamir (http://brett-zamir.me)
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // -    depends on: utf8_encode
  // *     example 1: sha1('Kevin van Zonneveld');
  // *     returns 1: '54916d2e62f65b3afa6e192e6a601cdbe5cb5897'
  var rotate_left = function (n, s) {
    var t4 = (n << s) | (n >>> (32 - s));
    return t4;
  };

/*var lsb_hex = function (val) { // Not in use; needed?
    var str="";
    var i;
    var vh;
    var vl;

    for ( i=0; i<=6; i+=2 ) {
      vh = (val>>>(i*4+4))&0x0f;
      vl = (val>>>(i*4))&0x0f;
      str += vh.toString(16) + vl.toString(16);
    }
    return str;
  };*/

  var cvt_hex = function (val) {
    var str = "";
    var i;
    var v;

    for (i = 7; i >= 0; i--) {
      v = (val >>> (i * 4)) & 0x0f;
      str += v.toString(16);
    }
    return str;
  };

  var blockstart;
  var i, j;
  var W = new Array(80);
  var H0 = 0x67452301;
  var H1 = 0xEFCDAB89;
  var H2 = 0x98BADCFE;
  var H3 = 0x10325476;
  var H4 = 0xC3D2E1F0;
  var A, B, C, D, E;
  var temp;

  str = this.utf8_encode(str);
  var str_len = str.length;

  var word_array = [];
  for (i = 0; i < str_len - 3; i += 4) {
    j = str.charCodeAt(i) << 24 | str.charCodeAt(i + 1) << 16 | str.charCodeAt(i + 2) << 8 | str.charCodeAt(i + 3);
    word_array.push(j);
  }

  switch (str_len % 4) {
  case 0:
    i = 0x080000000;
    break;
  case 1:
    i = str.charCodeAt(str_len - 1) << 24 | 0x0800000;
    break;
  case 2:
    i = str.charCodeAt(str_len - 2) << 24 | str.charCodeAt(str_len - 1) << 16 | 0x08000;
    break;
  case 3:
    i = str.charCodeAt(str_len - 3) << 24 | str.charCodeAt(str_len - 2) << 16 | str.charCodeAt(str_len - 1) << 8 | 0x80;
    break;
  }

  word_array.push(i);

  while ((word_array.length % 16) != 14) {
    word_array.push(0);
  }

  word_array.push(str_len >>> 29);
  word_array.push((str_len << 3) & 0x0ffffffff);

  for (blockstart = 0; blockstart < word_array.length; blockstart += 16) {
    for (i = 0; i < 16; i++) {
      W[i] = word_array[blockstart + i];
    }
    for (i = 16; i <= 79; i++) {
      W[i] = rotate_left(W[i - 3] ^ W[i - 8] ^ W[i - 14] ^ W[i - 16], 1);
    }


    A = H0;
    B = H1;
    C = H2;
    D = H3;
    E = H4;

    for (i = 0; i <= 19; i++) {
      temp = (rotate_left(A, 5) + ((B & C) | (~B & D)) + E + W[i] + 0x5A827999) & 0x0ffffffff;
      E = D;
      D = C;
      C = rotate_left(B, 30);
      B = A;
      A = temp;
    }

    for (i = 20; i <= 39; i++) {
      temp = (rotate_left(A, 5) + (B ^ C ^ D) + E + W[i] + 0x6ED9EBA1) & 0x0ffffffff;
      E = D;
      D = C;
      C = rotate_left(B, 30);
      B = A;
      A = temp;
    }

    for (i = 40; i <= 59; i++) {
      temp = (rotate_left(A, 5) + ((B & C) | (B & D) | (C & D)) + E + W[i] + 0x8F1BBCDC) & 0x0ffffffff;
      E = D;
      D = C;
      C = rotate_left(B, 30);
      B = A;
      A = temp;
    }

    for (i = 60; i <= 79; i++) {
      temp = (rotate_left(A, 5) + (B ^ C ^ D) + E + W[i] + 0xCA62C1D6) & 0x0ffffffff;
      E = D;
      D = C;
      C = rotate_left(B, 30);
      B = A;
      A = temp;
    }

    H0 = (H0 + A) & 0x0ffffffff;
    H1 = (H1 + B) & 0x0ffffffff;
    H2 = (H2 + C) & 0x0ffffffff;
    H3 = (H3 + D) & 0x0ffffffff;
    H4 = (H4 + E) & 0x0ffffffff;
  }

  temp = cvt_hex(H0) + cvt_hex(H1) + cvt_hex(H2) + cvt_hex(H3) + cvt_hex(H4);
  return temp.toLowerCase();
}

//Start js for talking dictionary

// When loading content from an IFRAME into an HTML element, the 
// convention followed is that if the name of the element is XX,
// the IFRAME is IFRAME_PREFIX + XX. The constant below defines
// the value of the prefix
var IFRAME_PREFIX = "i";
// route separator
var ROUTE_SEPARATOR = " -> ";
var COMMA_SEPARATOR = ",";
var startingfrom = "";
var currentSelectedIndex = 0;
var currentNextPlace = "";
var endDirections = false;
var NEXTPLACEHTMLDISABLED = '<input name="nextgoto" type="text" value="" size="30" tabindex="5" disabled class="disabledfield">';
var NEXTPLACEHTMLENABLED = '<input name="nextgoto" type="text" value="" size="30" tabindex="5">';
// started adding directions
var startDirections = false;
var count = 0;
// array for keeping directions
var directions = new Array();

// Array to store fields on father information flap
var fatherInfoFields = new Array();
fatherInfoFields[0] = "ffirstname";
fatherInfoFields[1] = "fsurname";
fatherInfoFields[2] = "fclanname";
fatherInfoFields[3] = "fothernames";

// Array to store fields on mother information flap
var motherInfoFields = new Array();
motherInfoFields[0] = "methnicity";
motherInfoFields[1] = "mfirstname";
motherInfoFields[2] = "msurname";
motherInfoFields[3] = "mothernames";
motherInfoFields[4] = "mclanname";


// function to display ssiga data that matches a selected clan in the combo
function displaySsiga(clan, ssigaDiv) {
	// get the clan for which masiga are to be displayed
	var clanCombo = document.getElementById(clan);
	var clanName = clanCombo.options[clanCombo.selectedIndex].text;
	
	if (clanName == "Unknown") {
	} else {
		//alert("The masiga for the clan is " + document.getElementById(clanName).outerHTML);		
		// set the ssiga inner HTML
		document.getElementById(ssigaDiv).innerHTML = 
			document.getElementById(clanName).outerHTML;
		//document.getElementById("ssiga").name = controlName;
	}
}

//Remove leading and trailing spaces
function trimString(sInString) {
  sInString = sInString.replace( /^\s+/g, "" );// strip leading
  return sInString.replace( /\s+$/g, "" );// strip trailing
}

// Returns false if the field is empty, null, or has the string "null", and pops up
// the message passed to the function
function isNotNullOrEmptyString(fieldName, message) {	
	if (isNullOrEmpty(document.getElementById(fieldName).value)) {	
		alert(message);		
		return false;
	}
	return true;
}

// general purpose function to see if an input value has been
// entered at all or if the input value has a value "null"
function isNullOrEmpty(inputStr) {
	// trim; remove leading and trailing spaces
	var trimmedValue = trimString(inputStr);
	if (isEmpty(trimmedValue) || trimmedValue == "null") {
		return true;
	}
	return false;
}

// general purpose function to see if an input value has been
// entered at all
function isEmpty(inputStr) {
	if (inputStr == null || inputStr == "") {
		return true;
	}
	return false;
}


// general purpose function to see if a suspected numeric input
// is a positive integer and return the message if its not
function isPositiveInteger(fieldName, msg) {
	if (isPosInteger(fieldName)) {
		return true;
	} else {
		alert(msg);
		return false;
	}
}


// general purpose function to see if a suspected numeric input
// is a positive integer
function isPosInteger(fieldName) {
	inputVal = document.getElementById(fieldName).value;
	inputStr = inputVal.toString();
	for (var i = 0; i < inputStr.length; i++) {
		var oneChar = inputStr.charAt(i);
		if (oneChar < "0" || oneChar > "9") {
			return false;
		}
	}
	return true;
}

// general purpose function to ensure that a field does not contain zero as a value
function isNotZero(fieldName, msg) {
	if (document.getElementById(fieldName).value == "0") {
		alert(msg);
		return false;
	} else {
		return true;
	}
}

// function to set the starting from place for directions
function setStartingFrom() {
	// get currently selected index	
	var startingFromCombo = document.getElementById("startingfrom");	
	// if there's already a starting from place, prompt user before changing
	if (startDirections == true) {
		var message = "Are you sure you want to start again?\n Press OK to " + 
			" start again and Cancel to continue.";
		if (window.confirm(message)) {
			// set startdirections to false to start again
			startDirections = false;
			directions = new Array();
			// enable nextgoto and move button
			enableField('nextgoto');
			document.getElementById('move').disabled = false;
			endDirections = false;			
		} else {
			// set the previously selected word			
			startingFromCombo.options[currentSelectedIndex].selected = true;
		}
	}
	// always pickup startingfrom value when array is empty
	if (directions.length == 0) {
		startDirections = false;
	}	
	// start adding directions
	if (startDirections == false) {		
		// get starting from value
		startingfrom = document.getElementById('startingfrom').value;
		// set currently selected index
		currentSelectedIndex = startingFromCombo.selectedIndex;	
		startDirections = true;	
	}
	// startingfrom value
	directions[0] = startingfrom;
	showCurrentRoute();
	return true;
}
// function to move ahead in directions
function moveAhead() {
	// do not continue if user has reached destination
	if (endDirections) {
		return;
	}
	// always pickup startingfrom value when array is empty
	if (directions.length == 0) {
		startDirections = false;
		// if false do not continue
		if (!setStartingFrom()) {
			return;			
		}
	}		
	// get next value from nextgoto field
	var nextgoto = document.getElementById('nextgoto').value;
	// change nextgoto value to Titlecase
	nextgoto = toTitleCase(nextgoto);
	// TODO: next go to value should not be same as any previous value in array
	if (!checkWhetherValueExists(nextgoto)) {
		return;
	}
	// add next element to directions
	directions[directions.length] = nextgoto;
	// if next place value is same as Villade/City name disable next place text field
	var villageName = toTitleCase(document.getElementById('name').value);
	if (nextgoto == villageName) {
		// disable next place text field and 'move next' button		
		disableField('nextgoto');
		document.getElementById('move').disabled = true;
		endDirections = true;
	} else {
		// clear next place text field and give it focus
		document.getElementById('nextgoto').value = "";
		document.getElementById('nextgoto').focus();
		
	}
	// print current route
	showCurrentRoute();		
}

// function to enable text field
function enableField(fieldName) {
	document.getElementById(fieldName).disabled = false;
	document.getElementById(fieldName).className = "";
}

// function to disable text field
function disableField(fieldName) {
	document.getElementById(fieldName).disabled = true;
	document.getElementById(fieldName).className = "disabledfield";
	//Also, clear the control values
	//document.getElementById(fieldName).value = "";
}

// check whther value already exists
function checkWhetherValueExists(newValue) {
	for (i=0; i< directions.length; i++) {
		if (directions[i] == newValue) {
			alert("Place already used in the current route");
			return false
		}
	}
	return true;
}
// function to delete nth element in array
function deleteElement(array, n) {
  //delete the nth element of array
  var length = array.length;
  if (n >= length || n<0)
    return;
  for (var i=n; i<length-1; i++)
    array[i] = array[i+1];
  	array.length--;
}

// function to move backwards in directions
function moveBackwards() {
	// store current last element before deleting
	currentNextPlace = directions[directions.length - 1];
	// delete last element		
	deleteElement(directions, directions.length - 1);
	// reset next place text field if it was disabled
	if (endDirections) {		
		enableField('nextgoto');
		document.getElementById('move').disabled = false;
		endDirections = false;
	}	
	// set next place value to last element in array and only if the no of elements is > 1
	if (directions.length > 0) {
		document.getElementById('nextgoto').value = currentNextPlace;
	}
	// print current route
	showCurrentRoute();			
}


// function to print the current route
function showCurrentRoute() {
	var currentRoute = "";	
	 for (i=0; i < directions.length; i++) {
	 	currentRoute = currentRoute + directions[i] + ROUTE_SEPARATOR;		
	 }
	 // remove last separator if destination is reached
	 if (endDirections) {
	 	// remove last SEPARATOR
		var lastIndex = currentRoute.lastIndexOf(ROUTE_SEPARATOR);	
		currentRoute = currentRoute.substring(0,lastIndex);
	 } 	
	document.getElementById('currentroute').innerHTML = currentRoute;
}


// the name of the place must be included in the directions
function  isDestinationInDirections() {
   // make the current route and place lower case since the string comparison is case sensitive
	var currentRoute = document.getElementById("currentroute").innerHTML.toLowerCase();
	var place = document.getElementById("name").value.toLowerCase();
	if (currentRoute.indexOf(place) == -1) {
		// destination not in current route
		alert("Enter Village/City Name as the last place in the directions");
		return false;
	}
	return true;
}
// Transfer the HTML within the BODY tag from an IFRAME to the specified target.
// The convention followed is that content from an IFRAME called iXX is loaded
// into an element XX.
function transferHTML(target) {
   // The name of the IFRAME into which the external document is loaded
   var srcIFrame = document.getElementById(IFRAME_PREFIX + target);
   // The content in the IFrame contained within the body tag this code handles NN6, IE 5.5 and 6
   document.getElementById(target).innerHTML = (srcIFrame.contentDocument) ? srcIFrame.contentDocument.getElementsByTagName("BODY")[0].  innerHTML : (srcIFrame.contentWindow) ? srcIFrame.contentWindow.document.body.innerHTML : "";  
}

// function to show places of selected county (sets SRC attribute of Iframe)
//function showPlaces(newCounty) {
	//document.getElementById("iplace_div").src = "showplaces.php?county=" + newCounty;	
//}

// function to generate a request for famous Baganda whose names begin with the specified letter
function browseFamousBaganda(letter) {
	var url = "processsearch.php?letter="+letter;
	url += "&famous=Y&type=browsefamous";
	window.location.href = url;
}

// function to check that EITHER Surname OR Clan name has been entered on form
function checkRequiredFieldsForRelative(field1, field2, field3, field4, field5, field6) {	
	if (isNullOrEmpty(document.getElementById(field1).value) && isNullOrEmpty(document.getElementById(field2).value) 
		&& isNullOrEmpty(document.getElementById(field3).value) && isNullOrEmpty(document.getElementById(field4).value)
		&& isNullOrEmpty(document.getElementById(field5).value) && isNullOrEmpty(document.getElementById(field6).value)) {
		alert('You must enter either Surname or Clan Name of the person or one of the parents');
		return false;
	} 
	return true;
}

// function to check that EITHER Surname OR Clan name has been entered on form
function checkRequiredFieldsForFamousMuganda(field1, field2) {
	if (isNullOrEmpty(document.getElementById(field1).value) && isNullOrEmpty(document.getElementById(field2).value)) {
		alert('You must enter either Surname or Clan Name');
		return false;
	} 
	return true;
}

// check the required fields for the 
function checkRequiredFieldsForDescendantTree() {
	// first check whether the registration id has been entered
	if (isNullOrEmpty(document.getElementById("registrationid").value)) {
		// first name, surname and clan name are required
		return isNotNullOrEmptyString("firstname", "First Name is required") && 
			isNotNullOrEmptyString("surname", "Surname is required") && 
			isNotNullOrEmptyString("clanname", "Clan Name is required");
	}	
	return true;
}


function showPlaces(county) {
	frames['isearch_places'].location.href = "search_places_results.php?county=" + county;
}



// set direction hidden field
function setDirectionValue(separator, field) {	
	var directionString = "";
	for (i=0; i < directions.length; i++) {
		directionString = directionString + directions[i] + separator;
	}	
	// remove last SEPARATOR
	var lastCommaIndex = directionString.lastIndexOf(separator);	
	directionString = directionString.substring(0,lastCommaIndex);		
	// set hidden field value
	document.getElementById(field).value = directionString;
	return true;
}


// function to get clan details
function showClanDetails() {
	var clanCombo = document.getElementById('clan');	
	var clanName = clanCombo.options[clanCombo.selectedIndex].text;		
	window.frames['isearch_clans'].location = "search_clans_results.php?clan=" + clanName;	
}

// function to get clan details
function showRelativesDetails() {
	var surname = document.getElementById('surname').value;	
	window.frames['isearch_relatives'].location = "search_relatives3.php?surname=" + surname;	
}

// function to change string to TitleCase
function toTitleCase(newString) {
	var tmpString = ""
	// change string to lowercase
	tmpString = newString.toLowerCase();
	// change first letter to Uppercase
	var firstLetter = tmpString.substring(0,1);
	firstLetter = firstLetter.toUpperCase();
	// return string in title case
	tmpString = firstLetter + tmpString.substring(1,tmpString.length);
	return tmpString;		
}

// function to deal with selecting a default submit button
 function keyDownHandler(btn) { 
        // process only the Enter key
        if (event.keyCode == 13)  {
            // cancel the default submit
            event.returnValue=false;
            event.cancel = true;
            // submit the form by programmatically clicking the specified button
            document.getElementById(btn).click();
        }
 }
 
 function showTabIfVisible(mname, shown) {
	if (document.all[mname+'_layer'].style.visibility == (shown ? 'inherit' : 'hidden')) return;
	document.all[mname+'lt'].className = shown ? 'bgontabbottom' : 'bgofftabbottom';
	document.all[mname+'lnk'].className = shown ? 'bgontabbottommid' : 'bgofftabbottommid';
	document.all[mname+'txt'].className = shown ? 'smalltextnolink' : 'smallgraytextnolink';
	document.all[mname+'rt'].className = shown ? 'bgontabbottom' : 'bgofftabbottom';
	document.all[mname+'_layer'].style.zIndex = shown ? 0 : -1;
	document.all[mname+'_layer'].style.visibility = shown ? 'inherit' : 'hidden';
	document.images[mname+'lti'].src=document.images[mname+'lti'].src.replace(shown ? /_plain/ : /_stroked/,shown ? '_stroked' : '_plain');
	document.images[mname+'rti'].src=document.images[mname+'rti'].src.replace(shown ? /_plain/ : /_stroked/,shown ? '_stroked' : '_plain');
}

function showTab(lname) {
   showTabIfVisible('tab1', lname == 'tab1');
   showTabIfVisible('tab2', lname == 'tab2');
   showTabIfVisible('tab3', lname == 'tab3');
   //showTab('tab4', lname == 'tab4');
   //showTab('tab5', lname == 'tab5');
   //showTab('tab6', lname == 'tab6');
   //showTab('tab7', lname == 'tab7');   
}

// function to validate the data entered on a specific tab
function validateTab(newTab) {
	var currentLayer = document.getElementById("currentlayer").value;
	if (currentLayer == "tab1_layer") {
		// change the name of the current layer only if validation was sucessful
		if (validatePersonalInformation()) {
			document.getElementById("currentlayer").value = newTab + "_layer";
			return true;
		}
	} else if (currentLayer == "tab2_layer") {
		if (validateFatherInformation()) {
			document.getElementById("currentlayer").value = newTab + "_layer";
			return true;
		}
	} else if (currentLayer == "tab3_layer") {
		if (validateMotherInformation()) {
			document.getElementById("currentlayer").value = newTab + "_layer";
			return true;
		}		
	}
}

function validatePersonalInformation() {
	return isNotNullOrEmptyString('surname', 'Please enter Surname') &&
 isNotNullOrEmptyString('firstname', 'Please enter Firstname') && isNotNullOrEmptyString('clanname','Please enter Clan Name') && 
 isNotNullOrEmptyString('gender','Please enter Gender') && isNotNullOrEmptyString('clan','Please enter Clan');
}

// validate the father information if any has been entered
function validateFatherInformation() {
	if (document.getElementById("fathernotregistered").checked == false) {		
		// no data has been entered for the father
		return true;
	} else {
		// some data some data has been entered for the father therefore validate it
		validFatherData = isNotNullOrEmptyString('fsurname', 'Please enter Surname') &&
 					isNotNullOrEmptyString('ffirstname', 'Please enter Firstname') && 
					isNotNullOrEmptyString('fclanname','Please enter Clan Name'); 
		if (validFatherData) {
			// Set the parameter that allows the processing of father information
			document.getElementById("savefatherinformation").value = "true";
			return true;
		}		
	}
	return false;	
}

// validate the father information if any has been entered
function validateMotherInformation() {
	if (document.getElementById("mothernotregistered").checked == false) {
		// no data has been entered for the father
		return true;
	} else {
		// some data some data has been entered for the father therefore validate it
		validMotherData = isNotNullOrEmptyString('msurname', 'Please enter Surname') &&
 					isNotNullOrEmptyString('mfirstname', 'Please enter Firstname'); 
		if (validMotherData) {
			// Set the parameter that allows the processing of father information
			document.getElementById("savemotherinformation").value = "true";
			return true;
		}		
	}
	return false;	
}

function saveDate(year, month, day, dateField) {
	// delimiter for date fields
	var dateDelimiter = "-";
	// contruct the date from date fields
	var birthDate = document.getElementById(year).value + 
		dateDelimiter + document.getElementById(month).value + 
		dateDelimiter + document.getElementById(day).value;
	// set value of date to be saved to DB
	document.getElementById(dateField).value = birthDate;
	return true;	
}

// function to display buganda counties in combo list
function displayBugandaCounties(nameofCountryCombo, fieldName1, fieldName2) {
	// get name of selected option in country combo
	var countryCombo = document.getElementById(nameofCountryCombo);
	var countrySelected = countryCombo.options[countryCombo.selectedIndex].text;
	if (countrySelected == "Uganda (Buganda)") {
		// enable county combo and village textfield
		enableField(fieldName1);
		enableField(fieldName2);
	} else {		
		// disable county combo and village textfield
		disableField(fieldName1);
		disableField(fieldName2);
	}
}

// function to enable/disable mother information fields
function updateMotherFields(ethnicityCombo, fieldName1, fieldName2, fieldName3) {
	// get name of selected option in ethnicity combo
	var ethnicity = document.getElementById(ethnicityCombo);
	var ethnicitySelected = ethnicity.options[ethnicity.selectedIndex].text;
	if (ethnicitySelected == "Muganda") {
		// enable clan, ssiga combos and clan name textfield
		enableField(fieldName1);
		enableField(fieldName2);
		enableField(fieldName3);
	} else {		
		// disable clan, ssiga combos and clan name textfield
		disableFieldValue(fieldName1);
		disableFieldValue(fieldName2);
		disableFieldValue(fieldName3);
	}
}


// function to disable named controls on a screen
function disableControls(flag) {
	var arrayOfControls = new Array();
	if (flag == "f") {
		// father info flap
		arrayOfControls = fatherInfoFields;
	} else {
		// mother infoflap
		arrayOfControls = motherInfoFields;
	}
	for (i=0; i< arrayOfControls.length;i++) {
		disableFieldValue(arrayOfControls[i]);
	}
}

// function to enable named controls on a screen
function enableControls(flag) {
	if (flag == "f") {
		// father info flap
		arrayOfControls = fatherInfoFields;
	} else {
		// mother infoflap
		arrayOfControls = motherInfoFields;
	}
	for (i=0; i < arrayOfControls.length; i++) {
		enableField(arrayOfControls[i]);
	}
}

// show controls on flap
function showControls(flag) {
	if (flag == "f") {
		if (document.getElementById("fatherregistered").checked == true) {
			disableControls("f");
			// enable registration field id
			enableField("fatherid");
		} else {
			//show controls on flap since father is not registered
			enableControls("f");
			// disable registrationID field
			disableField("fatherid");
		}
	} else {
		if (document.getElementById("motherregistered").checked == true) {
			disableControls("m");
			// enable registrationID field
			enableField("motherid");
		} else {
			//show controls on flap since father is not registered
			enableControls("m");
			// disable registrationID field
			disableField("motherid");
		}
	}
}

// enables the father or mother id field if the id exists
function enableIdFields(){
	if(document.getElementById("fatherid").value != ""){
	   disableControls("f");
	   // enable registration field id
	   enableField("fatherid");
	   document.getElementById("fatherregistered").checked = true;
	}
	
	if(document.getElementById("motherid").value != ""){
	   disableControls("m");
	   // enable registration field id
	   enableField("motherid");
	   document.getElementById("motherregistered").checked = true;
	}
}

function showLayer(name) {
	var layers = document.getElementsByName(name);
	for (var i=0; i < layers.length; i++) {
		layers[i].style.visibility = "inherit";
	}
}

function hideLayer(name) {
	var layers = document.getElementsByName(name);
	for (var i=0; i < layers.length; i++) {
		layers[i].style.visibility = "hidden";
	}
}

// function to set parent registration status
function setParentRegistrationStatus() {
	if (document.getElementById("mothernotregistered").checked == true) {		
		document.getElementById("hidden_mothernotregistered").value = true;
	}
		if (document.getElementById("fathernotregistered").checked == true) {
		document.getElementById("hidden_fathernotregistered").value = true;
	}
	return true;
}

// Validates the email entered.
function validateEmail(fieldValue){
   // The invalid characters that should not be used in an email address
   var invalidChars = " /:,;"; 
   var emailAddress = fieldValue;
   
   var atPosition = emailAddress.indexOf("@",1);
   var periodPosition = emailAddress.indexOf(".",atPosition);
   
   if (isNullOrEmpty(emailAddress)){
      return false;
   }
   // Checks for the invalid characters listed above.
   for (var i=0; i<invalidChars.length; i++){
      badChar = invalidChars.charAt(i);
	  if (emailAddress.indexOf(badChar,0) > -1){
	     return false;		 
	  }
   }

   if (atPosition == -1){ // Checks for the @
      return false;
   }
   if (emailAddress.indexOf("@",atPosition + 1) > -1){ // Makes sure there is one @
      return false;
   }
   if (periodPosition == -1){ // Makes sure there is a period after the @ 
      return false;
   }
   // Makes sure there is at least 2 characters after the period
   if ((periodPosition + 3) > emailAddress.length){ 
      return false;
   }
   
   return true;
}

// function used to check email and display message
function isValidEmail(fieldname, msg) {
	if (!validateEmail(document.getElementById(fieldname).value)) {
		alert(msg);
		return false;
	}
	return true;
}

//focuses on the English field
function focusOnEnglish(){
   disableField('lugandago');
	document.getElementById('englishgo').disabled = false;
	document.getElementById('englishgo').className = "button";
	document.getElementById('englishword').focus();
}

//focuses on the Luganda field
function focusOnLuganda(){
   disableField('englishgo');
	document.getElementById('lugandago').disabled = false;
	document.getElementById('lugandago').className = "button";
	document.getElementById('lugandaword').focus();
}

// function to generate a request for famous Baganda whose names begin with the specified letter
function browseFamousBaganda(letter) {
	var url = "processsearch.php?letter="+letter;
	url += "&famous=Y&type=browsefamous";
	window.location.href = url;
}

// function to check that EITHER Surname OR Clan name has been entered on form
function checkRequiredFieldsForRelative(field1, field2, field3) {	
	if (isNullOrEmpty(document.getElementById(field1).value) && isNullOrEmpty(document.getElementById(field2).value) 
		&& isNullOrEmpty(document.getElementById(field3).value)) {
		alert('You must enter either Surname or Clan Name of the person or one of the parents');
		return false;
	} 
	return true;
}

// function to check that EITHER Surname OR Clan name has been entered on form
function checkRequiredFieldsForSearchRelative(field1) {	
	if (isNullOrEmpty(document.getElementById(field1).value)) {
		alert('You must enter either Surname or Clan Name of the person');
		return false;
	} 
	return true;
}
// function to check that EITHER Surname OR Clan name has been entered on form
function checkRequiredFieldsForFamousMuganda(field1, field2) {
	if (isNullOrEmpty(document.getElementById(field1).value) && isNullOrEmpty(document.getElementById(field2).value)) {
		alert('You must enter either Surname or Clan Name');
		return false;
	} 
	return true;
}

function checkRequiredFieldsForSearchFamousMuganda(field1) {
	if (isNullOrEmpty(document.getElementById(field1).value)) {
		alert('You must enter either Surname or Clan Name');
		return false;
	} 
	return true;
}

// check the required fields for the 
function checkRequiredFieldsForDescendantTree() {
	// first check whether the registration id has been entered
	if (isNullOrEmpty(document.getElementById("registrationid").value)) {
		// first name, surname and clan name are required
		return isNotNullOrEmptyString("firstname", "First Name is required") && 
			isNotNullOrEmptyString("surname", "Surname is required") && 
			isNotNullOrEmptyString("clanname", "Clan Name is required");
	}	
	return true;
}
// set the form action where data is to be submitted
function setFormAction(formAction) {
	document.forms[0].action = formAction;
}

// check value from drop down and forward to appropriate page
function checkAddInformation(fieldNameCombo) {
	var optionSelected = document.getElementById(fieldNameCombo).value;
	var url="";
	if(optionSelected == "Now"){
		url = "../add/addyourself.php?action=addnow";
	} else if (optionSelected == ""){
		alert("Specify whether you want to add your ancestry information now or later");
		return;
	} else {
		url = "../general/login.php";
	}
	window.location.href = url;
}

// function to check number of characters in textfield
function checkNumberofCharacters(textfieldName, counterFieldName) {
	var maxcharacters = 750;
	updateMessageCounter();
	var text = document.getElementById(textfieldName).value;
	var textLength = text.length;
	if (textLength > maxcharacters) {
		alert('You can only enter a maximum of ' + maxcharacters +' characters');
		// show only up to limit of charatcters
		var maxString = text.substring(0,maxcharacters);
		document.getElementById(textfieldName).value = maxString;
		// reset counter
		document.getElementById(counterFieldName).innerHTML = maxcharacters;
		return false;
	}
	return true;	
}

function updateMessageCounter() {
	var text = document.getElementById("text").value;
	var textLength = text.length;
	document.getElementById("counterdiv").innerHTML = textLength;
}

// function to join message board
function joinBoard(username, boardID) {
	var message = "Are you sure you want to join this board?\n Press OK to" + 
			" join and Cancel to cancel operation.";
	if (window.confirm(message)) {
		document.location.href="joinboard.php?boardid="+boardID+"&username="+username;		
	} else {
		// do nothing
	}
}
// function to join message board
function leaveBoard(username, boardID) {
	var message = "Are you sure you want to leave this board?\n The messages that you posted on this board shall not be deleted. \n Press OK to" + 
			" join and Cancel to cancel operation.";
	if (window.confirm(message)) {
		document.location.href="leaveboard.php?boardid="+boardID+"&username="+username;		
	}
}

// update a hidden field from a checkbox
function updateCheckBoxHiddenField(fieldName) {
	if (document.getElementById(fieldName + "_checkbox").checked) {
		// set the hidden field value to true
		document.getElementById(fieldName).value = "Y";
	} else {
		// set the hidden field value to true
		document.getElementById(fieldName).value = "N";
	}
	return true;
}

// update a hidden field from a checkbox
function updateCheckBoxHiddenFieldForAccount(fieldName) {
	if (document.getElementById(fieldName + "_checkbox").checked) {
		// set the hidden field value to true
		document.getElementById(fieldName).value = "N";
	} else {
		// set the hidden field value to true
		document.getElementById(fieldName).value = "Y";
	}
	return true;
}

// function to compare two passwords, and then clear them if they are not
// the same
function comparePasswords(firstPassword, secondPassword, message){
   //Check whether two fields have same value, alert message and return false when they don't
    if(document.getElementById(firstPassword).value != document.getElementById(secondPassword).value){
	   alert(message);
	   document.getElementById(firstPassword).value = "";
	   document.getElementById(secondPassword).value ="";
	   return false;
	}
	return true;

}

// function to check whether the password field is empty then compare the entered two passwords
//, and then clear them if they are not the same
function compareEditedPasswords(firstPassword, secondPassword, message){
	if(document.getElementById(firstPassword).value == ""){
		return true;
	}
   //Check whether two fields have same value, alert message and return false when they don't
    if(document.getElementById(firstPassword).value != document.getElementById(secondPassword).value){
	   alert(message);
	   document.getElementById(firstPassword).value = "";
	   document.getElementById(secondPassword).value ="";
	   return false;
	}
	return true;
}

// message displayed to a non-member, requesting them to login
function displaySubscribeMessage() {
	alert("You have to login to view the details for this person.");
}

// function to set membership amount for PAYPAL
function setPaypalParameters() {
	if (document.getElementById("membership10").checked) {
		document.getElementById("amount").value = document.getElementById("membership10").value;
		document.getElementById("item_name").value = "Member";
		return true;
	} 
	if (document.getElementById("membership50").checked) {
		document.getElementById("amount").value = document.getElementById("membership50").value;	
		document.getElementById("item_name").value = "Life Member";
		return true;
	} 
	alert("Please select the subscription type you wish to pay for.");
	return false;	
}

// function to set membership amount for PAYPAL
function setPaybyCheckParameters() {
	if (document.getElementById("membership10").checked) {
		document.getElementById("total").value = document.getElementById("membership10").value;
		document.getElementById("description").value = "Annual Membership";
		document.getElementById("price").value = 10;
		document.getElementById("quantity").value = 1;
		document.getElementById("charge").value = 10;
		document.getElementById("subtotal").value = 10;
		return true;
	} 
	if (document.getElementById("membership50").checked) {
		document.getElementById("total").value = document.getElementById("membership50").value;	
		document.getElementById("description").value = "Life Membership";
		document.getElementById("price").value = 50;
		document.getElementById("quantity").value = 1;
		document.getElementById("charge").value = 50;
		document.getElementById("subtotal").value = 50;
		return true;
	} 
	alert("Please select the subscription type you wish to pay for.");
	return false;	
}

// 
function displayInvoice(url) {
	var membertype = "";
	if (document.getElementById("membership10").checked) {
		membertype = "GA-MEMBER-001";
		url = url + membertype;
		openInvoiceWindow(url);
		return true;
	} 
	if (document.getElementById("membership50").checked) {
		membertype = "GA-MEMBER-002";
		url = url + membertype;
		openInvoiceWindow(url);
		return true;
	} 
	alert("Please select the subscription type you wish to pay for.");
	return false;	
}


// update a hidden field from a checkbox
function updateHiddenField(fieldName) {
	if (document.getElementById(fieldName + "_checkbox").checked) {
		// set the hidden field value to true
		document.getElementById(fieldName).value = "Yes";
	} else {
		// set the hidden field value to true
		document.getElementById(fieldName).value = "No";
	}
}

// update a hidden field from a checkbox
function updateHiddenFieldForFamousBaganda(fieldName) {
	if (document.getElementById(fieldName + "_checkbox").checked) {
		// set the hidden field value to true
		document.getElementById(fieldName).value = "Y";
	} else {
		// set the hidden field value to true
		document.getElementById(fieldName).value = "";
	}
}
// function to disable text field
function disableFieldValue(fieldName) {
	document.getElementById(fieldName).disabled = true;
	document.getElementById(fieldName).className = "disabledfield";
	//Also, clear the control values
	document.getElementById(fieldName).value = "";
}

// function to disable fields
function disableAncestryField(field1, comboName) {
	var combo = document.getElementById(comboName);
	var comboselection = combo.options[combo.selectedIndex].text;	
	if (comboselection == "Muganda") {
		// enable field
		enableField(field1);
	} else {
		// disable field
		disableField(field1);
	}	
}

// check if a given field value is equal to "No". Returns true if value is equal to "No"
function isValueNo(fieldName) {
	if (document.getElementById(fieldName).value == "No") {
		return true;
	}
	return false;
}
// if none of the required checkboxes is selected show message and don't submit form
function isRequiredCheckboxSelected(msg) {	
	if (isValueNo('traceancestry') && isValueNo('academicresearch') && isValueNo('interestedinuganda') && 
		isValueNo('bagandarelatives') && isValueNo('interestedinbuganda') && isValueNo('other')) {
		alert(msg);
		return false;
	}
	return true;
}

// function to open a pop-up
function openWindow(fileName) { 
  // To specify the window characteristics edit the "features" variable below:
  // width - width of the window
  // height - height of the window
  // scrollbar - "yes" for scrollbars, "no" for no scrollbars
  // left - number of pixels from left of screen
  // top - number of pixels from top of screen
  features = "width=750,height=280,left=100,top=130,resizable=0, scrollbars=0";
  listwindow = window.open(fileName,"newWin", features);
  listwindow.focus();   
}
// function to open a pop-up
function openInvoiceWindow(fileName) { 
  // To specify the window characteristics edit the "features" variable below:
  // width - width of the window
  // height - height of the window
  // scrollbar - "yes" for scrollbars, "no" for no scrollbars
  // left - number of pixels from left of screen
  // top - number of pixels from top of screen
  features = "width=565,height=680,left=100,top=0,resizable=0, scrollbars=1";
  listwindow = window.open(fileName,"newWin", features);
  listwindow.focus();   
}

// function to open a pop-up
function openRegistrationOverView(fileName) { 
  // To specify the window characteristics edit the "features" variable below:
  // width - width of the window
  // height - height of the window
  // scrollbar - "yes" for scrollbars, "no" for no scrollbars
  // left - number of pixels from left of screen
  // top - number of pixels from top of screen
  features = "width=350,height=180,resizable=0, scrollbars=0, left=600, top=300, alwaysRaised=yes";
  listwindow = window.open(fileName,"newWin", features);
  listwindow.focus();   
}
// function to display buganda counties in combo list
function displayBugandaCounties(fieldName1, fieldName2) {
	// get name of selected option in country combo
	var combo = document.getElementById("countryofbirth");
	var countrySelected = combo.options[combo.selectedIndex].text;
	if (countrySelected == "Uganda (Buganda)") {
		// enable county combo and village textfield
		enableField(fieldName1);
		enableField(fieldName2);
	} else {		
		// disable county combo and village textfield
		disableFieldValue(fieldName1);
		disableFieldValue(fieldName2);
	}
}

// function to display buganda counties in combo list
// this function is only used in add ancestry information functionality for admin users 
function showBugandaCounties(countryField, fieldName1, fieldName2) {
	// get name of selected option in country combo
	var combo = document.getElementById(countryField);
	var countrySelected = combo.options[combo.selectedIndex].text;
	if (countrySelected == "Uganda (Buganda)") {
		// enable county combo and village textfield
		enableField(fieldName1);
		enableField(fieldName2);
	} else {		
		// disable county combo and village textfield
		disableFieldValue(fieldName1);
		disableFieldValue(fieldName2);
	}
}

// show controls on flap
function changeOtherField(checkboxName, textfieldName) {
	if (document.getElementById(checkboxName).checked == true) {
			// enable other field id
			enableField(textfieldName);
	} else {
			// disable other field
			disableFieldValue(textfieldName);
	}
}

// function to ensure that at least one checkbox has been selected
function ischeckboxSelected(msg) {
   count = 0;
   for (var i=0; i < document.forms[0].elements.length; i++) {	
      if (document.forms[0].elements[i].checked) {
	     count++;
	  }
   }
   if (count == 0) {
      alert(msg);
	  return false;
   }
   return true;
}

// function to display initial membership amount for pay by check
function displayInitialCheckAmount(fieldName){
	 document.getElementById(fieldName).value = 10;
}

//function to update membership years depending on the value of the check
function updateMembershipYears(amountField, yearsField, radioField1, radioField2,  message){
	if(document.getElementById(amountField).value < 10){
		alert(message);
		return false;
	}else if(document.getElementById(amountField).value < 20){
		document.getElementById(yearsField).value = 1;
	}else if(document.getElementById(amountField).value < 30){
		document.getElementById(yearsField).value = 2;
	}else if(document.getElementById(amountField).value < 40){
		document.getElementById(yearsField).value = 3;
	}else if(document.getElementById(amountField).value < 50){
		document.getElementById(yearsField).value = 4;
	}else{
		document.getElementById(yearsField).value = "Life";
		document.getElementById(radioField2).checked = true;
		document.getElementById(radioField1).checked = false;	
	}
	return true;
}

//The functions below are for the premium dictionary
 function showNearMatchTranslation(index) {
	 document.getElementById('englishword').value = document.getElementById(index).value;
	 showWords('englishword', 'english');
 }
// function to open a pop-up
function openTestWordsList(fileName) { 
  // To specify the window characteristics edit the "features" variable below:
  // width - width of the window
  // height - height of the window
  // scrollbar - "yes" for scrollbars, "no" for no scrollbars
  // left - number of pixels from left of screen
  // top - number of pixels from top of screen
  features = "width=350,height=320,resizable=0, scrollbars=0, left=600, top=300, alwaysRaised=yes";
  listwindow = window.open(fileName,"newWindow", features);
  listwindow.focus();   
}

// function to check that EITHER Surname OR Clan name has been entered on form. The url is ignored
function requestMemberLogin(url) {	
	alert('You must subscribe see the details.');
}

//function to validate that CRM has entered a correct value for their deceased person.
function validatePersonAge(birthField, deathField){
	// if the user entered a birth date for their father or grandfather
	var birthdate = document.getElementById(birthField).value;
   	var deathdate = document.getElementById(deathField).value;
	if(birthdate != "" && deathdate != ""){
   			var ageRecord = deathdate - birthdate;
			if(ageRecord < 0 ){
				 alert("You have entered an invalid Date of Birth or Date of Death");
				 document.getElementById(deathField).value = "";
				return false;
			}
	}
	return true;
}

//function to validate that the user has entered a valid name.
function isValidName(fieldValue, message){
   var invalidChars = "/:,;?@%.!#*()^&"; 
   var theName = document.getElementById(fieldValue).value;
	 for (var i=0; i<invalidChars.length; i++){
      badChar = invalidChars.charAt(i);
	  if (theName.indexOf(badChar,0) > -1){
      	 alert(message);
		 return false;		 
	  }
   }
   return true;
}

// function to clear death values once the user selects status alive or unknown
function clearDeathFields(){
	document.getElementById("deathdate_y").value = "";
	document.getElementById("deathdate_m").value = "";
	document.getElementById("deathdate_d").value = "";
	document.getElementById("countryofburial").value = "";
	disableFieldValue('countyofburial');
	disableFieldValue('villageofburial');
}

// function to check number of characters in textfield
function checkNumberofCharactersForNewsText(textfieldName, counterFieldName) {
	updateMessageCounter();
	var maxcharacters = 150;
	var text = document.getElementById(textfieldName).value;
	var textLength = text.length;
	if (textLength > maxcharacters) {
		alert('You can only enter a maximum of ' + maxcharacters +' characters');
		// show only up to limit of charatcters
		var maxString = text.substring(0,maxcharacters);
		document.getElementById(textfieldName).value = maxString;
		// reset counter
		document.getElementById(counterFieldName).innerHTML = maxcharacters;
		return false;
	}
	return true;	
}

// prompts the user whether or not they would like to delete an entity
function deleteEntity(url) {
	message = "Are you sure you want to delete this news item? \n" + 
					"Press OK to delete the item \n" + 
					"Cancel to stay on the current page";
	if (window.confirm(message)) {
		window.location.href=url;
	}
}

// function to validate the URL entered when user post news items
function validateNewsArticleSource(articleSource, newsURL){
	var newspaper = document.getElementById(articleSource).value;
	var newsLink = document.getElementById(newsURL).value;
	if(newspaper == "Daily Monitor"){
		if(newsLink.indexOf("monitor.co.ug") == -1){
			alert("Daily Monitor articles should have a link to 'http://www.monitor.co.ug' domain.");
			return false;
		}
	}
	
	if(newspaper == "Sunday Monitor"){
		if(newsLink.indexOf("sundaymonitor.co.ug") == -1){
			alert("Sunday Monitor articles should have a link to 'http://www.sundaymonitor.co.ug' domain.");
			return false;
		}
	}
	
	if(newspaper == "The New Vision"){
		if(newsLink.indexOf("newvision.co.ug") == -1){
			alert("The New Vision articles should have a link to 'http://www.newvsion.co.ug' domain.");
			return false;
		}
	}
	
	if(newspaper == "Sunday Vision"){
		if(newsLink.indexOf("sundayvision.co.ug") == -1){
			alert("Sunday Vision articles should have a link to 'http://www.sundayvision.co.ug' domain.");
			return false;
		}
	}
	
	if(newspaper == "Bukedde"){
		if(newsLink.indexOf("bukedde.co.ug") == -1){
			alert("Bukedde articles should have a link to 'http://www.bukedde.co.ug' domain.");
			return false;
		}
	}
	
	if(newspaper == "Bukedde ku Ssande"){
		if(newsLink.indexOf("bukeddekussande.co.ug") == -1){
			alert("Bukedde ku Ssande articles should have a link to 'http://www.bukeddekussande.co.ug' domain.");
			return false;
		}
	}
	
	if(newspaper == "Simba Fm"){
		if(newsLink.indexOf("simba.fm") == -1){
			alert("Simba FM articles should have a link to 'http://www.simba.fm' domain.");
			return false;
		}
	}
	
	if(newspaper == "The Weekly Observer"){
		if(newsLink.indexOf("observer.ug") == -1){
			alert("The Weekly Observer articles should have a link to 'http://www.ugandaobserver.com' domain.");
			return false;
		}
	}
	
	if(newspaper == "Other"){
		if((newsLink.indexOf("ugandaobserver.com")!= -1) || (newsLink.indexOf("simba.fm")!= -1) || (newsLink.indexOf("bukeddekussande")!= -1) || (newsLink.indexOf("bukedde.co.ug")!= -1) || (newsLink.indexOf("sundayvision.co.ug")!= -1) || (newsLink.indexOf("newvision.co.ug")!= -1) || (newsLink.indexOf("sundaymonitor.co.ug")!= -1) || (newsLink.indexOf("monitor.co.ug")!= -1)){
			alert("The domain you specified has a newspaper listed in our sources. Please select the correct source.");
			document.getElementById(articleSource).focus();
			return false;
		}
	}
	return true;	
}
