-- create user table
CREATE TABLE `user` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `password` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- add default admin user
INSERT INTO `user` (`username`, `password`) VALUES
('admin', '$2y$10$3aU9wnaBQSF3uoWulalnTOBvfUvgwe7uYQLfAr5K5zzvlbYlGOf.u');

-- create subject table
CREATE TABLE `subject` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `teacher` varchar(50) NOT NULL,
  `weight` float NOT NULL,
  `rounding` float NOT NULL,
  FOREIGN KEY (userId) REFERENCES user(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- create note table
CREATE TABLE `mark` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `subjectId` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `value` float NOT NULL,
  `weight` float NOT NULL,
  `date` datetime DEFAULT NULL,
  FOREIGN KEY (subjectId) REFERENCES subject(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;