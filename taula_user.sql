USE PwBox;


DROP TABLE IF EXISTS Item CASCADE;
DROP TABLE IF EXISTS User CASCADE;


CREATE TABLE `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) NOT NULL,
  `surname` varchar(40) NOT NULL,
  `username` varchar(40) NOT NULL,
  `birth_date` date NOT NULL,
  `email` varchar(200) NOT NULL,
  `pswUser` varchar(100) NOT NULL,
  `id_motherfolder` int(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8



SELECT * FROM User;


CREATE TABLE `Item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) NOT NULL,
  `parent` varchar(100),
  `type` bool NOT NULL,
  `id_propietari` int(11) NOT NULL,
  FOREIGN KEY (`id_propietari`) REFERENCES User(`id`),
  PRIMARY KEY (`id`))
  ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

  SELECT * FROM Item;