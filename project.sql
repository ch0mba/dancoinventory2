CREATE DATABASE `dclinventorydb` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

CREATE TABLE `invmovdet` (
  `id` int(11) NOT NULL,
  `invMovId` int(11) NOT NULL,
  `stockcode` int(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `trnQty` varchar(255) NOT NULL,
  `notation` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FOREIGN` (`invMovId`) USING BTREE,
  CONSTRAINT `InvMovId` FOREIGN KEY (`invMovId`) REFERENCES `invmovements` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `invmovements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trnType` varchar(255) NOT NULL,
  `trnRef` varchar(255) NOT NULL,
  `warehouse` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `truckNo` varchar(7) NOT NULL,
  `officer` varchar(255) NOT NULL,
  `storeLocation` varchar(255) NOT NULL,
  `time` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `status` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `stockcodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stockcode` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `stocklocation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stockLocation` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `transactiontype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trnType` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(7) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `warehouse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
