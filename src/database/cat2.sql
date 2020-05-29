# FILE: cat2
# DESCRIPTION: 
#    Defines the structure of the database
# 							
#							website<https://www.kuarantine.co.ke>
#							email<felixmuthui32@gmail>

# Initialise the database
# FOREIGN KEY CONSTRAINTS MUST BE UNIQUE IN THE ENTIRE DATABASE!!
CREATE DATABASE `kuarantine`;
USE `kuarantine`;

# Create the user table structure
#
CREATE TABLE IF NOT EXISTS `users` ( 
`id`          INT(11) auto_increment ,
`url`         VARCHAR(255) NULL ,
`username`    VARCHAR(60) NULL , 
`email`       VARCHAR(60) NOT NULL , 
`firstName`   VARCHAR(60) NULL ,
`lastName`    VARCHAR(60) NULL ,
`fullName`    VARCHAR(60) NULL,
`isAdmin`     BOOLEAN NULL ,
`bio`         varchar(255), 
`password`    VARCHAR(65) NOT NULL ,
`created`     DATETIME NOT NULL DEFAULT NOW() ,
`lastUpdated` TIMESTAMP NOT NULL DEFAULT NOW() ,
`expiryDate`  DATETIME NULL, 
 PRIMARY KEY (`id`)
 ) ENGINE = InnoDB;
CREATE UNIQUE INDEX users_username_uindex ON users (username);
CREATE UNIQUE INDEX users_email_uindex ON users (email);
 
 # Create the articles table
 # Set::  ON update set null cascade ON delete cascade set null
 # so that when the users are deleted or updated the articles are not affected
 # 
CREATE TABLE IF NOT EXISTS `articles` (
`id`          INT auto_increment primary key,
`url`         varchar(255) null,
`headline`    varchar(70) NOT null,
`content`     varchar(200) null,
`userID`      int NOT NULL,
 constraint `fk_author` FOREIGN key (`userID`) REFERENCES users(`id`) ON update cascade ON delete cascade,
 INDEX(`userID`),
`created`     DATETIME NOT NULL DEFAULT NOW() ,
`lastUpdated` TIMESTAMP NOT NULL DEFAULT NOW() 
)ENGINE = InnoDB;

# Create the comments table
# Set::  ON update set null cascade ON delete cascade set null
# so that when the users are deleted or updated the articles are not affected
# 

CREATE TABLE IF NOT EXISTS `comments` (
`id`          INT auto_increment primary key,
`userID`          int NOT NULL,
FOREIGN key (`userID`) REFERENCES users(`id`) ON update cascade ON delete cascade,
`articleID`          int NOT NULL,
constraint `fk_articleID` FOREIGN key (`articleID`) REFERENCES articles(`id`) ON update cascade ON delete cascade,
INDEX (`userID`, `articleID`),
`isRead`      bool NULL,
`content`     varchar(255) null,
`created`     DATETIME NOT NULL DEFAULT NOW() ,
`lastUpdated` TIMESTAMP NOT NULL DEFAULT NOW() 
)ENGINE = InnoDB;

# Create the app table structure
#
CREATE TABLE IF NOT EXISTS `app` (
`id`              INT auto_increment primary key,
`isTermsAndConditionsAccepted` bool null,
`applicationID`   varchar(90) null,
`versionName`     varchar(50) NULL,
`versionCode`     varchar(50) NULL,
`userID`          int NOT NULL,
`display`        varchar(70) NOT NULL ,
`created` 		 DATETIME NOT NULL DEFAULT NOW() ,
`lastUpdated` 	 TIMESTAMP NOT NULL DEFAULT NOW(),
 INDEX(`userID`),
 constraint `fk_installed_user` FOREIGN key (`userID`) REFERENCES users(`id`) ON update cascade ON delete restrict
)ENGINE = InnoDB;

# The categories table strucuture
#

CREATE TABLE IF NOT EXISTS `categories` (
`id`            INT auto_increment primary key,
`categoryName`  varchar(250) not null
)ENGINE = InnoDB;

# Create table products
#

CREATE TABLE IF NOT EXISTS `products` (
`id`          INT auto_increment primary key,
`url`         varchar(250) null,
`title`       varchar(70) NOT null,
`detail`      varchar(200) null,
`categoryId`  int NOT NULL,
`price`       decimal not null,
 constraint `fk_category` FOREIGN key (`categoryId`) REFERENCES categories(`id`) ON update cascade ON delete cascade,
`created`     DATETIME NOT NULL DEFAULT NOW() ,
`lastUpdated` TIMESTAMP NOT NULL DEFAULT NOW() 
)ENGINE = InnoDB;

# The table for orders made by users
#

CREATE TABLE `productsOrder` (
`id` INT NOT NULL auto_increment,
`productCategoryId` INT NOT NULL,
`productId`       INT NOT NULL,
`userId`          INT NOT NULL,
primary key(`id`),
INDEX (`productCategoryId`, `productId`),
INDEX (`userId`),
foreign key (`productCategoryId`, `productId`)
	references products(`categoryId`, `id`) ON UPDATE CASCADE ON DELETE RESTRICT,
FOREIGN KEY (`userId`) REFERENCES users(`id`)
)ENGINE=INNODB;

# RESEARCH MORE ON SENDING LOGS TO TH SERVER
# history table structure
# action:: added a product
# tableId:: the product id
# TODO:: CREATING THIS TABLE LEADS TO AN ERROR
#
CREATE TABLE IF NOT EXISTS `activity` (
`id`            INT auto_increment primary key,
`action`          varchar(255) NOT NULL,
`tableId`        int NOT NULL ,
`userID`          int NOT NULL,
`created`       DATETIME NOT NULL DEFAULT NOW() ,
`lastUpdated`   TIMESTAMP NOT NULL DEFAULT NOW(),
INDEX(`userId`),
FOREIGN key (`userId`) REFERENCES users(`id`) ON update cascade ON delete cascade
)ENGINE = InnoDB;

# Create the notifications table
# entity id :: 
#	column relates to the related table
# if a user posts an article the article id is stored in the entity id table
# then you can get the details of the post by the entity id.
# STRUCTURE:: 
#  | id  | entityId  | userId  |  					content						          | 
#  |  1  |    23     | 1       | This notification is about creating article 23(entityId) |
#
CREATE TABLE IF NOT EXISTS `notifications` (
`id`              INT auto_increment primary key,
`userID`          int NOT NULL,
`entityId`        INT NOT NULL,
`content`         varchar(250) NULL,
`isRead`          bool NULL, 
`created`         DATETIME NOT NULL DEFAULT NOW() ,
`lastUpdated`     TIMESTAMP NOT NULL DEFAULT NOW(),
INDEX (`entityId`),
FOREIGN key (`userID`) REFERENCES users(`id`) ON update cascade ON delete cascade
)ENGINE = INNODB;


# Create the root user ME :)
 /**
 * Create the admin account
 * username: felix
 * Password: Igwanya32
 */
 INSERT INTO `users` ( `id`, `url`, `username`, `email`, `firstName`, `lastName`,`fullName`,  `isAdmin`, `bio`, `password`)
 VALUES
(
1,
'img/Thumbnail.png',
'felix',
'felixmuthui32@gmail.com',
'Felix', 
'Muthui',
'Felix Muthui',
true, 
'The root user the amazing all system admin for the site',
'$2y$10$N6FfGndrdk0UcNvzPrgiTubW7OXT4FWVCP8vdUf20hlyAET9y06Oi'
);

 # Create the initial article
 # TODO:: column url has no data !!!
 #
INSERT INTO `articles` (
`id`, `headline`, `content`, `userID`)
VALUES
(
1,
'Welcome to the quarantine app',
'Share you stories and get updated when information is sent out',
 1 ),
 (
 2,
 'Order goods and serivces',
 'Order groceries, gas from the quarantine app directly from your phone or website',
 1 );

# initialise the comments table data
#
INSERT INTO `comments` (`id`, `userId`, `articleID`, `isRead`, `content`) 	VALUES 
(1, 1, 1, true, 'The app is beautiful and functional');

# initial data for the app table data
# 
INSERT INTO `app`( `id`, `isTermsAndConditionsAccepted`, `applicationId`, `versionName`, `versionCode`, `userID`, `display`) VALUES 
(1, true, 'com.muthui.cat2',"SNAPSHOT", "1.0.0", 1, '1024x968');

# Initial data for the categories table
#
INSERT INTO categories(`id`, `categoryName`) VALUES 
('1','dress'), ('2', 'bottoms'), ('3', 'shoes');

# Data for the products table
#
INSERT INTO `products`(`id`, `url`, `title`, `detail`, `categoryID`, `price`) VALUES
 (1, '', 'An very nice dress', "Buy this awesome dress ", 1, 500),
 (2, '', 'An affordable bottom', "Buy this while it si in stock now ", 2, 1500),
 (3, '', 'A very nice shoe', "This shoe comes in all sizes", 3, 2000);


















# To wipe eveything SHIFT+DELETE
#
# DROP DATABASE `cat2`;

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




# Select all from categories
#
SELECT * FROM `categories`;



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




INSERT INTO `history`(action, id) VALUES ("Installed the Kuarantine app", 1);
SELECT * FROM  `history`;
DROP TABLE IF EXISTS `history`;

INSERT INTO `notifications`(title,content,priority, created) VALUES ("Welcome", "You've installed the app", "INFO", current_timestamp());

SELECT * FROM `notifications`;

DROP TABLE IF EXISTS `notifications`;


