USE PwBox;

DROP TABLE IF EXISTS User CASCADE;

CREATE TABLE `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) NOT NULL,
  `surname` varchar(40) NOT NULL,
  `username` varchar(40) NOT NULL,
  `birth_date` date NOT NULL,
  `email` varchar(200) NOT NULL,
  `pswUser` varchar(100) NOT NULL,
  `id_motherfolder` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8


CREATE TABLE `Item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) NOT NULL,
  `parent` varchar(100) NOT NULL,
  `type` bool NOT NULL,
  PRIMARY KEY (`id`))
  ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

SELECT * FROM User;
SELECT * FROM Item;