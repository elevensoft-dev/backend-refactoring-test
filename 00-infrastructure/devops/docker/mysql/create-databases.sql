-- Garantir privilégios para o usuário eleven
GRANT SYSTEM_USER, CREATE ROUTINE, ALTER ROUTINE, EVENT ON *.* TO 'eleven'@'%';
FLUSH PRIVILEGES;

CREATE DATABASE IF NOT EXISTS eleven_testing;
