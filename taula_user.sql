USE PwBox;

DROP TABLE IF EXISTS Notification CASCADE;
DROP TABLE IF EXISTS Share CASCADE;
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
  `total_bytes` int(11) NOT NULL DEFAULT '0',
  `validat` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;




CREATE TABLE `Item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `parent` varchar(100),
  `type` bool NOT NULL,
  `id_propietari` int(11) NOT NULL,
  FOREIGN KEY (`id_propietari`) REFERENCES User(`id`),
  PRIMARY KEY (`id`))
  ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

  DROP TABLE IF EXISTS Share CASCADE;
  CREATE TABLE `Share` (
  `id_user` int(11) NOT NULL ,
  `role` varchar(50) NOT NULL,
  `id_propietari` int(11) NOT NULL,
  `id_folder` int(11) NOT NULL,
	`parent` int(11) NOT NULL,
  FOREIGN KEY (`id_user`) REFERENCES User(`id`),
  FOREIGN KEY (`id_folder`) REFERENCES Item(`id`),
  FOREIGN KEY (`id_propietari`) REFERENCES Item(`id_propietari`),
  PRIMARY KEY (`id_user`,`id_folder`, `id_propietari`))
  ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
  SELECT * FROM Share;


  CREATE TABLE `Notification` (
  `id_user` int(11) NOT NULL ,
  `notification` varchar(500) NOT NULL,
  FOREIGN KEY (`id_user`) REFERENCES User(`id`))
  ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

SELECT * FROM User;
SELECT * FROM Item;
SELECT * FROM Share;



