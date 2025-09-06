-- Garantir privilégios para o usuário eleven
CREATE DATABASE IF NOT EXISTS eleven_testing;

GRANT ALL PRIVILEGES ON eleven_testing.* TO 'eleven'@'%';
GRANT SYSTEM_USER, CREATE ROUTINE, ALTER ROUTINE, EVENT ON *.* TO 'eleven'@'%';
FLUSH PRIVILEGES;
