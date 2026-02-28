-- Create a dedicated user for the web applications
CREATE USER IF NOT EXISTS 'all_db_user'@'%' IDENTIFIED BY 'password';

-- Grant access to all benchmark databases
GRANT ALL PRIVILEGES ON bWAPP.* TO 'all_db_user'@'%';
GRANT ALL PRIVILEGES ON dvwa.* TO 'all_db_user'@'%';
GRANT ALL PRIVILEGES ON simple_db.* TO 'all_db_user'@'%';
GRANT ALL PRIVILEGES ON wackopicko.* TO 'all_db_user'@'%';
GRANT ALL PRIVILEGES ON wordpress.* TO 'all_db_user'@'%';
GRANT ALL PRIVILEGES ON xvwa.* TO 'all_db_user'@'%';


FLUSH PRIVILEGES;
