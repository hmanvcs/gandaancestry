-- Reset all email addresses to development addresses and the password to the word password
UPDATE appconfig set optionvalue = 'notifications@gandaancestry.com' WHERE optionname = 'emailmessagesender';
UPDATE appconfig set optionvalue = 'support@gandaancestry.com' WHERE optionname = 'supportemailaddress';