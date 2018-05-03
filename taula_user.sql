USE PwBox;

DROP TABLE IF EXISTS User CASCADE;

CREATE TABLE `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) NOT NULL,
  `surname` varchar(40) NOT NULL,
  `username` varchar(40) NOT NULL,
  `birth_date` date NOT NULL,
  `email` varchar(200) NOT NULL,
  `pswUser` varchar(12) NOT NULL,
  `image` varchar (50),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

SELECT * FROM User;