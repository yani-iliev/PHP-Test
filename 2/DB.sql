DROP TABLE IF EXISTS `members`;

CREATE TABLE `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `members` WRITE;

INSERT INTO `members` (`id`,`username`,`password`)
VALUES
	(1,'test','f5d1278e8109edd94e1e4197e04873b9');


UNLOCK TABLES;
