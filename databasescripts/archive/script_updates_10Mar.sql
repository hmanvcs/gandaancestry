-- remove users
delete from useraccount where id not in(1, 4, 16);
alter table useraccount AUTO_INCREMENT = 1;

UPDATE useraccount set password = sha1('password'), email = 'csmukasa@gmail.com' where id = 4;
UPDATE useraccount set password = sha1('demo'), email = 'demo@gandaancestry.com' where id = 16;