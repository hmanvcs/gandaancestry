 -- Reset all email addresses to development addresses and the password to the word password

UPDATE useraccount set email =  INSERT(email,LOCATE('@', email)+1, 22,'devmail.infomacorp.com'), password = sha1('password');

-- UPDATE person set email =  concat(id, '@devmail.infomacorp.com');
UPDATE person set email =  'test@devmail.infomacorp.com';

 -- Overwrite admin email addresses to administrator
UPDATE useraccount set email = 'admin@devmail.infomacorp.com' WHERE id = 1;

UPDATE appconfig set optionvalue = 'administrator@devmail.infomacorp.com' WHERE optionname = 'emailmessagesender';
UPDATE appconfig set optionvalue = 'administrator@devmail.infomacorp.com' WHERE optionname = 'supportemailaddress';
