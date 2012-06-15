/*
SQLyog Community v9.50 
MySQL - 5.3.3-MariaDB : Database - webcms
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`webcms` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `webcms`;

/*Table structure for table `user_auths` */

DROP TABLE IF EXISTS `user_auths`;

CREATE TABLE `user_auths` (
  `auth_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `login` varchar(54) NOT NULL,
  `hash` char(40) NOT NULL,
  PRIMARY KEY (`auth_id`),
  UNIQUE KEY `login` (`login`),
  KEY `FK_user_auths_user_id` (`user_id`),
  CONSTRAINT `FK_user_auths_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `user_auths` */

insert  into `user_auths`(`auth_id`,`user_id`,`login`,`hash`) values (1,1,'someLogin','d763bba346510be03d985770878b157f8a382fcb'),(4,4,'someLogin1','343eafb0124d884a35cc8a8dd2df891a11558c86');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(54) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `username` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`user_id`,`email`,`date_created`,`username`) values (1,'example@example.com','2012-06-14 11:48:39','Ahaha!'),(4,'example@example.com','2012-06-15 16:37:13','User Userovich 2');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
