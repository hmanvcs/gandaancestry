-- Reset all email addresses to development addresses and the password to the word password
-- UPDATE useraccount set email =  INSERT(email,LOCATE('@', email)+1, 22,'veritracker.com'), password = sha1('password');

UPDATE appconfig set optionvalue = 'administrator@veritracker.com' WHERE optionname = 'emailmessagesender';
UPDATE appconfig set optionvalue = 'administrator@veritracker.com' WHERE optionname = 'supportemailaddress';

UPDATE appconfig set optionvalue = 'csmukasa@infoma.com' WHERE optionname = 'receiveremail';
UPDATE appconfig set optionvalue = 'https://www.paypal.com/cgi-bin/webscr' WHERE optionname = 'serverurl';
UPDATE appconfig set optionvalue = 'www.paypal.com' WHERE optionname = 'servername';

UPDATE appconfig set optionvalue = 'paypal@gandaancestry.com' WHERE optionname = 'receiveremail';
UPDATE appconfig set optionvalue = 'https://www.sandbox.paypal.com/cgi-bin/webscr' WHERE optionname = 'serverurl';
UPDATE appconfig set optionvalue = 'www.sandbox.paypal.com' WHERE optionname = 'servername';