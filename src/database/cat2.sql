# FILE: cat2
# DESCRIPTION: 
#    Defines the structure of the database
# 
#							email<felixmuthui32@gmail>


# Initialise the database
CREATE DATABASE `cat2`;
USE `cat2`;

# Create the user table
CREATE TABLE IF NOT EXISTS `users` ( 
`id` INT(11) auto_increment ,
`username` VARCHAR(60) NULL , 
`email` VARCHAR(60) NOT NULL , 
`firstName` VARCHAR(60) NULL ,
`lastName` VARCHAR(60) NULL ,
`isAdmin` BOOLEAN NULL ,
`passwordHash` VARCHAR(65) NOT NULL ,
`created` DATETIME NULL ,
`lastUpdated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`expiryDate` DATETIME NULL, 
 PRIMARY KEY (`id`)
 ) ENGINE = InnoDB;
CREATE UNIQUE INDEX users_username_uindex ON users (username);
CREATE UNIQUE INDEX users_email_uindex ON users (email);
 
 # Create the articles table
 # Set::  ON update set null cascade ON delete cascade set null
 # so that when the users are deleted or updated the articles are not affected
CREATE TABLE IF NOT EXISTS `articles` (
`articleID` INT auto_increment primary key,
`headline` varchar(70) NOT null,
`content` varchar(200) null,
`userID` int NOT NULL,
 constraint fk_author FOREIGN key (`userID`) REFERENCES users(`id`) ON update cascade ON delete cascade,
`created` DATETIME NULL ,
`lastUpdated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP 
)ENGINE = InnoDB;

# Create the root user ME :)
 /**
 * Create the admin account
 * Password: Igwanya32
 */
 INSERT INTO `users` (
`id`, `username`, `email`, `firstName`, `lastName`, `isAdmin`, `passwordHash`, `created`, `lastUpdated`)
 VALUES
('1',
'admin',
'felixmuthui32@gmail.com',
'Felix', 
'Muthui',
true, 
'$2y$10$N6FfGndrdk0UcNvzPrgiTubW7OXT4FWVCP8vdUf20hlyAET9y06Oi',
CURRENT_TIMESTAMP,
 CURRENT_TIMESTAMP);
 
 # Create the initial article
INSERT INTO `articles` (
`headline`, `content`, `userID`, `created`, `lastUpdated`)
VALUES
('Welcome to the quarantine app',
 'Share you stories and get updated when i receive information',
 1,
 CURRENT_TIMESTAMP,
 CURRENT_TIMESTAMP),
 ('Order goods from anywhere',
 'Order groceries, gas to quarantine masks directly from your phone',
 1,
 CURRENT_TIMESTAMP,
 CURRENT_TIMESTAMP);

# Create the app table
#
CREATE TABLE IF NOT EXISTS `app` (
`appID` INT auto_increment primary key,
`app_version` varchar(50) NULL,
`model`     varchar(50) NULL,
`user`      varchar(70) NULL,
`api_level` varchar(70) NULL,
`screen_resolution` varchar(70) NULL,
`screen_density` varchar(70) NULL
);  

select * from `app`;
DROP TABLE IF EXISTS `app`;


# Shift + delete everything
#
# DROP DATABASE `cat2`;
# DROP TABLE IF exists `users`;
# DROP TABLE IF EXISTS `articles`;

#  To drop the foregin key constaint
#
# ALTER TABLE table_name DROP foreign key constraint_name;

# Query everything from the tables
select * FROM `users`;
select * from `articles`;
SHOW CREATE TABLE `articles`;

# Test the on cascade stuff
#
# DELETE FROM `users` where id=1;
