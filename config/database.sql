-- create user table
CREATE TABLE `user` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `password` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- add default admin user
INSERT INTO `user` (`username`, `password`) VALUES
('admin', '$2y$10$3aU9wnaBQSF3uoWulalnTOBvfUvgwe7uYQLfAr5K5zzvlbYlGOf.u');

-- create fach table
CREATE TABLE `fach` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `lehrer` varchar(50) NOT NULL,
  `gewichtung` float NOT NULL,
  `rundung` float NOT NULL,
  FOREIGN KEY (userID) REFERENCES user(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- create note table
CREATE TABLE `note` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `fachId` int(11) NOT NULL,
  `bezeichnung` varchar(30) NOT NULL,
  `note` float NOT NULL,
  `gewichtung` float NOT NULL,
  `datum` datetime DEFAULT NULL,
  FOREIGN KEY (fachID) REFERENCES fach(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;