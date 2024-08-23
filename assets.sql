-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 23, 2024 at 12:29 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `asset_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

DROP TABLE IF EXISTS `assets`;
CREATE TABLE IF NOT EXISTS `assets` (
  `asset_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year_of_purchase` int NOT NULL,
  `cost_of_asset` int NOT NULL,
  `end_of_life` int NOT NULL,
  `current_cost` int NOT NULL,
  `depreciation_percentage` decimal(10,2) NOT NULL,
  PRIMARY KEY (`asset_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`asset_id`, `name`, `category`, `description`, `year_of_purchase`, `cost_of_asset`, `end_of_life`, `current_cost`, `depreciation_percentage`) VALUES
(1, 'Toyota Camry', 'Cars', 'A reliable mid-sized sedan', 2021, 25000, 2025, 20000, 25.00),
(2, 'Honda Accord', 'Cars', 'A popular mid-sized sedan', 2020, 24000, 2024, 19200, 25.00),
(3, 'Ford Mustang', 'Cars', 'A classic American muscle car', 2019, 30000, 2023, 24000, 25.00),
(4, 'Chevrolet Malibu', 'Cars', 'A comfortable and efficient sedan', 2022, 23000, 2026, 2032, 25.00),
(5, 'Nissan Altima', 'Cars', 'A well-rounded sedan with great features', 2023, 26000, 2027, 2033, 25.00),
(6, 'Tesla Model 3', 'Cars', 'An electric sedan with cutting-edge technology', 2021, 35000, 2025, 2031, 25.00),
(7, 'BMW 3 Series', 'Cars', 'A luxury sedan with excellent performance', 2020, 40000, 2024, 2030, 25.00),
(8, 'Audi A4', 'Cars', 'A compact luxury sedan', 2018, 37000, 2022, 2028, 25.00),
(9, 'Mercedes-Benz C-Class', 'Cars', 'A luxury compact sedan', 2019, 42000, 2023, 2029, 25.00),
(10, 'Hyundai Elantra', 'Cars', 'A compact car with great fuel economy', 2022, 22000, 2026, 2032, 25.00),
(11, 'Samsung TV', 'Electronics', 'A 55-inch smart TV', 2021, 800, 2025, 640, 25.00),
(12, 'Apple iPhone', 'Electronics', 'A latest model smartphone', 2023, 1200, 2027, 960, 25.00),
(13, 'Dell Laptop', 'Electronics', 'A powerful business laptop', 2022, 1500, 2026, 1200, 25.00),
(14, 'Sony PlayStation 5', 'Electronics', 'A popular gaming console', 2021, 500, 2025, 400, 25.00),
(15, 'Bose Headphones', 'Electronics', 'Noise-cancelling over-ear headphones', 2020, 300, 2024, 240, 25.00),
(16, 'Microsoft Surface', 'Electronics', 'A versatile tablet-laptop hybrid', 2023, 2000, 2027, 2028, 25.00),
(17, 'Canon Camera', 'Electronics', 'A high-quality DSLR camera', 2019, 2500, 2023, 2000, 25.00),
(18, 'Apple Watch', 'Electronics', 'A smart wearable device', 2021, 400, 2025, 320, 25.00),
(19, 'Amazon Echo', 'Electronics', 'A smart speaker with voice control', 2022, 100, 2026, 80, 25.00),
(20, 'LG Refrigerator', 'Electronics', 'A large double-door refrigerator', 2020, 1500, 2024, 2025, 25.00),
(21, 'Sofa Set', 'Furniture', 'A comfortable 3-piece sofa set', 2019, 2000, 2023, 1600, 25.00),
(22, 'Dining Table', 'Furniture', 'A large wooden dining table', 2021, 1500, 2025, 1200, 25.00),
(23, 'Queen Bed', 'Furniture', 'A sturdy queen-sized bed frame', 2020, 1000, 2024, 800, 25.00),
(24, 'Office Desk', 'Furniture', 'A modern office desk with drawers', 2022, 500, 2026, 400, 25.00),
(25, 'Wardrobe', 'Furniture', 'A spacious wooden wardrobe', 2023, 1200, 2027, 960, 25.00),
(26, 'Bookshelf', 'Furniture', 'A tall wooden bookshelf', 2018, 800, 2022, 640, 25.00),
(27, 'TV Stand', 'Furniture', 'A sleek modern TV stand', 2021, 600, 2025, 480, 25.00),
(28, 'Coffee Table', 'Furniture', 'A glass-top coffee table', 2019, 300, 2023, 240, 25.00),
(29, 'Armchair', 'Furniture', 'A comfortable upholstered armchair', 2020, 400, 2024, 320, 25.00),
(30, 'Bedside Table', 'Furniture', 'A small wooden bedside table', 2022, 150, 2026, 120, 25.00);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
