

CREATE TABLE IF NOT EXISTS `guari`.`Users` ( 
`id` INT(11) NOT NULL ,
 `username` VARCHAR(60) NULL , 
`email` VARCHAR(60) NOT NULL , 
`first_name` VARCHAR(60) NULL ,
 `last_name` VARCHAR(60) NULL ,
 `is_admin` BOOLEAN NULL ,
 `password_hash` VARCHAR(65) NOT NULL ,
 `created` DATETIME NULL ,
 `last_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 PRIMARY KEY (`id`)
 ) ENGINE = InnoDB;
CREATE UNIQUE INDEX users_username_uindex ON users (username);
CREATE UNIQUE INDEX users_email_uindex ON users (email);
ALTER TABLE users MODIFY id int(11) NOT NULL auto_increment;
 /**
 * Create the admin account
 */
 INSERT INTO `users` (
 `id`, `username`, `email`, `first_name`, `last_name`, `is_admin`, `password_hash`, `created`, `last_updated`)
 VALUES
 ('1',
 'admin',
 'felixmuthui32@gmail.com',
 'Felix', 
 'Muthui',
 '1', 
 '$2y$10$LqWFL22ui/PNf51aN.dFe.jaQiC4EqUmgVdLm7CtgVdwi.NvYh2hm',
 '2020-04-06 00:00:00',
 CURRENT_TIMESTAMP);
 
CREATE TABLE `posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;