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
`url` VARCHAR(250) NULL ,
`username` VARCHAR(60) NULL , 
`email` VARCHAR(60) NOT NULL , 
`firstName` VARCHAR(60) NULL ,
`lastName` VARCHAR(60) NULL ,
`fullName` VARCHAR(60) NULL,
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
 INSERT INTO `users` (`username`, `email`, `firstName`, `lastName`, `isAdmin`, `passwordHash`, `created`, `lastUpdated`)
 VALUES
(
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
`id` INT auto_increment primary key,
`versionName` varchar(50) NULL,
`versionCode`     varchar(50) NULL,
`userID`      int,
 constraint fk_userID FOREIGN key (`userID`) REFERENCES users(`id`) ON update cascade ON delete cascade,
`display` varchar(70) NULL ,
`created` DATETIME NULL ,
`lastUpdated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);  

INSERT INTO `app`(versionName, versionCode, userID, created) VALUES ("SNAPSHOT", "1.0.0", 1, current_timestamp());

select * from `app`;

# Shift + delete everything
#
# DROP DATABASE `cat2`;
# DROP TABLE IF exists `users`;
# DROP TABLE IF EXISTS `articles`;
DROP TABLE IF EXISTS `app`;

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

# Table category
#
CREATE TABLE IF NOT EXISTS `categories` (
`categoryID` INT auto_increment primary key,
`categoryName` varchar(250) null
)ENGINE = InnoDB;

# Create table products
#
CREATE TABLE IF NOT EXISTS `products` (
`productID` INT auto_increment primary key,
`url` varchar(250) null,
`title` varchar(70) NOT null,
`desription` varchar(200) null,
`categoryID` int NOT NULL,
`price` int null,
 constraint fk_category FOREIGN key (`categoryID`) REFERENCES categories(`categoryID`) ON update cascade ON delete cascade,
`created` DATETIME NULL ,
`lastUpdated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP 
)ENGINE = InnoDB;

# Test data for the categories table
#
INSERT INTO categories(categoryName) VALUES ('Smartphone'), ('SmartWatch');

# Select all from categories
#
SELECT * FROM `categories`;

# Data for the products table
#
INSERT INTO `products`(title, categoryID, created, lastUpdated) VALUES ('Ipad', 1, current_timestamp(), current_timestamp());

# Select * from products table
#
SELECT * FROM `products`;

# Delete a category
# Deleting a category deletes a product.
DELETE FROM `categories` WHERE `categoryID` = 1;

# Drop the whole table categories
#
DROP TABLE IF EXISTS `categories`;

# Drop the products table
#
DROP TABLE IF EXISTS `products`;

# Create the history table
#

CREATE TABLE IF NOT EXISTS `history` (
`historyID` INT auto_increment primary key,
`action` varchar(250) null,
`id` int NOT NULL,
 constraint fk_Id FOREIGN key (`id`) REFERENCES app(`id`) ON update cascade ON delete cascade,
`created` DATETIME NULL ,
`lastUpdated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP 
)ENGINE = InnoDB;

INSERT INTO `history`(action, id) VALUES ("Installed the Kuarantine app", 1);
SELECT * FROM  `history`;
DROP TABLE IF EXISTS `history`;


# Create the notifications table
#
CREATE TABLE IF NOT EXISTS `notifications` (
`id` INT auto_increment primary key,
`title` varchar(250) not null,
`content` varchar(250) NULL,
`priority` varchar(250) NULL,
`created` DATETIME NULL ,
`lastUpdated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP 
)ENGINE = InnoDB;

INSERT INTO `notifications`(title,content,priority, created) VALUES ("Welcome", "You've installed the app", "INFO", current_timestamp());

SELECT * FROM `notifications`;

DROP TABLE IF EXISTS `notifications`;


