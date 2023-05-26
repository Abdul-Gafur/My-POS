-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2023 at 02:12 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mypos`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(3) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile1` varchar(15) NOT NULL,
  `mobile2` varchar(15) NOT NULL,
  `password` char(60) NOT NULL,
  `role` char(5) NOT NULL,
  `created_on` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `last_seen` datetime NOT NULL,
  `last_edited` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `account_status` char(1) NOT NULL DEFAULT '1',
  `deleted` char(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `first_name`, `last_name`, `email`, `mobile1`, `mobile2`, `password`, `role`, `created_on`, `last_login`, `last_seen`, `last_edited`, `account_status`, `deleted`) VALUES
(1, 'Abdul-Gafur', 'Saeed', 'abdulgafurshaattir@gmail.com', '54 732 2367', '200957200', '$2y$10$7rLSvRVyTQORapkDOqmkhetjF6H9lJHngr4hJMSM2lHObJbW5EQh6', 'Super', '2022-01-04 22:19:16', '2023-05-26 10:29:40', '2023-05-26 10:28:26', '2023-05-26 10:29:40', '1', '0');

-- --------------------------------------------------------

--
-- Table structure for table `eventlog`
--

CREATE TABLE `eventlog` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event` varchar(200) NOT NULL,
  `eventRowIdOrRef` varchar(20) DEFAULT NULL,
  `eventDesc` text DEFAULT NULL,
  `eventTable` varchar(20) DEFAULT NULL,
  `staffInCharge` bigint(20) UNSIGNED NOT NULL,
  `eventTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `eventlog`
--

INSERT INTO `eventlog` (`id`, `event`, `eventRowIdOrRef`, `eventDesc`, `eventTable`, `staffInCharge`, `eventTime`) VALUES
(1, 'Creation of new item', '1', 'Addition of 69 quantities of a new item \'Demo Item\' with a unit price of &#8358;500.00 to stock', 'items', 1, '2021-04-19 17:47:59'),
(2, 'New Transaction', '765149033', '1 items totalling &#8358;490.00 with reference number 765149033 was purchased', 'transactions', 1, '2021-04-19 17:49:03'),
(3, 'Creation of new item', '2', 'Addition of 669 quantities of a new item \'Toy Cars\' with a unit price of $24.00 to stock', 'items', 1, '2021-06-01 13:57:58'),
(4, 'Creation of new item', '3', 'Addition of 712 quantities of a new item \'Oats Crunchy Honey Roasted Cereal\' with a unit price of $12.00 to stock', 'items', 1, '2021-06-01 13:59:54'),
(5, 'Creation of new item', '4', 'Addition of 752 quantities of a new item \'KIND Breakfast Cereal\' with a unit price of $21.55 to stock', 'items', 1, '2021-06-01 14:01:25'),
(6, 'New Transaction', '23649438', '1 items totalling $211.50 with reference number 23649438 was purchased', 'transactions', 1, '2021-06-01 14:03:10'),
(7, 'Creation of new item', '5', 'Addition of 1125 quantities of a new item \'Duck Brand Stretch Wrap\' with a unit price of $33.00 to stock', 'items', 1, '2021-06-01 14:12:11'),
(8, 'Creation of new item', '6', 'Addition of 1265 quantities of a new item \'Self Adhesive Clear Cookie Bags\' with a unit price of $8.00 to stock', 'items', 1, '2021-06-01 14:14:09'),
(9, 'New Transaction', '439972', '1 items totalling $2,166.78 with reference number 439972 was purchased', 'transactions', 1, '2021-06-01 14:15:41'),
(10, 'Creation of new item', '7', 'Addition of 552 quantities of a new item \'Lightweight Satin Soft Fabric\' with a unit price of $12.00 to stock', 'items', 1, '2021-06-01 14:19:42'),
(11, 'Creation of new item', '8', 'Addition of 852 quantities of a new item \'Anderson&#039;s Black Flame Retardant Gossamer Fabric\' with a unit price of $12.00 to stock', 'items', 1, '2021-06-01 14:21:08'),
(12, 'Creation of new item', '9', 'Addition of 811 quantities of a new item \'Precut Quilting Sewing Fabric\' with a unit price of $9.00 to stock', 'items', 1, '2021-06-01 14:25:01'),
(13, 'Creation of new item', '10', 'Addition of 563 quantities of a new item \'Orgnisulmte USDA Organic Dragon Fruit Powder\' with a unit price of $9.00 to stock', 'items', 1, '2021-06-01 14:30:45'),
(14, 'Creation of new item', '11', 'Addition of 469 quantities of a new item \'KIND Whole Fruit Bars\' with a unit price of $11.00 to stock', 'items', 1, '2021-06-01 14:31:45'),
(15, 'Creation of new item', '12', 'Addition of 482 quantities of a new item \'Del Monte Fruit and Oats Snack Cups\' with a unit price of $11.55 to stock', 'items', 1, '2021-06-01 14:33:14'),
(16, 'Creation of new item', '13', 'Addition of 888 quantities of a new item \'Chicken of the Sea Sardines\' with a unit price of $20.06 to stock', 'items', 1, '2021-06-01 14:34:28'),
(17, 'Creation of new item', '14', 'Addition of 702 quantities of a new item \'Wild Sardines\' with a unit price of $31.95 to stock', 'items', 1, '2021-06-01 14:35:38'),
(18, 'Creation of new item', '15', 'Addition of 995 quantities of a new item \'MW Polar Smoked Brisling Sardines in Olive Oil\' with a unit price of $35.38 to stock', 'items', 1, '2021-06-01 14:37:26'),
(19, 'Creation of new item', '16', 'Addition of 196 quantities of a new item \'Bluetooth Headphones Over-Ear\' with a unit price of $23.91 to stock', 'items', 1, '2021-06-01 14:38:54'),
(20, 'Creation of new item', '17', 'Addition of 201 quantities of a new item \'Panasonic Full-Sized Headphones\' with a unit price of $13.77 to stock', 'items', 1, '2021-06-01 14:39:55'),
(21, 'Creation of new item', '18', 'Addition of 236 quantities of a new item \'Toshiba Canvio 1TB Portable External Hard Drive\' with a unit price of $62.55 to stock', 'items', 1, '2021-06-01 16:25:16'),
(22, 'Creation of new item', '19', 'Addition of 312 quantities of a new item \'Seagate BarraCuda 2TB Internal Hard Drive\' with a unit price of $71.50 to stock', 'items', 1, '2021-06-01 16:26:55'),
(23, 'Creation of new item', '20', 'Addition of 589 quantities of a new item \'Samsung BAR Plus USB 3.1 Flash Drive 128GB\' with a unit price of $24.59 to stock', 'items', 1, '2021-06-01 16:28:30'),
(24, 'Creation of new item', '21', 'Addition of 6540 quantities of a new item \'Multipurpose Copy Printer Paper\' with a unit price of $19.10 to stock', 'items', 1, '2021-06-01 16:30:08'),
(25, 'New Transaction', '03941028', '1 items totalling $105.06 with reference number 03941028 was purchased', 'transactions', 1, '2021-06-01 16:31:05'),
(26, 'New Transaction', '872496', '1 items totalling $226.38 with reference number 872496 was purchased', 'transactions', 1, '2021-06-01 16:37:04'),
(27, 'Creation of new item', '22', 'Addition of 2550 quantities of a new item \'Metronic Large Shipping Bags Poly Mailers\' with a unit price of $4.99 to stock', 'items', 1, '2021-06-01 16:41:40'),
(28, 'New Transaction', '374217', '1 items totalling $429.14 with reference number 374217 was purchased', 'transactions', 1, '2021-06-01 16:43:26'),
(29, 'New Transaction', '18075809', '1 items totalling $946.64 with reference number 18075809 was purchased', 'transactions', 1, '2021-06-01 17:37:00'),
(30, 'New Transaction', '192549', '1 items totalling $254.52 with reference number 192549 was purchased', 'transactions', 1, '2021-06-01 18:29:37'),
(31, 'New Transaction', '641908', '1 items totalling $344.42 with reference number 641908 was purchased', 'transactions', 1, '2021-06-01 18:31:34'),
(32, 'New Transaction', '2346405', '1 items totalling $8,250.00 with reference number 2346405 was purchased', 'transactions', 1, '2021-06-01 18:33:38'),
(33, 'New Transaction', '983709261', '1 items totalling $1,164.00 with reference number 983709261 was purchased', 'transactions', 1, '2021-06-01 18:34:40'),
(34, 'Creation of new item', '23', 'Addition of 369 quantities of a new item \'Crunchies Freeze-Dried Fruits Variety Pack\' with a unit price of $17.00 to stock', 'items', 1, '2021-06-01 19:17:26'),
(35, 'New Transaction', '32825746', '1 items totalling $1,266.16 with reference number 32825746 was purchased', 'transactions', 1, '2021-06-01 19:19:33'),
(36, 'New Transaction', '895691278', '1 items totalling $280.91 with reference number 895691278 was purchased', 'transactions', 1, '2021-06-01 19:22:15'),
(37, 'New Transaction', '1230158', '1 items totalling $12,279.01 with reference number 1230158 was purchased', 'transactions', 1, '2021-06-01 19:24:03'),
(38, 'New Transaction', '069215370', '1 items totalling $734.40 with reference number 069215370 was purchased', 'transactions', 3, '2021-06-01 19:26:28');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `unitPrice` decimal(10,2) NOT NULL,
  `quantity` int(6) NOT NULL,
  `dateAdded` datetime NOT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `code`, `description`, `unitPrice`, `quantity`, `dateAdded`, `lastUpdated`) VALUES
(1, 'Demo Item', '101', 'Demo Test', 500.00, 68, '2021-04-19 23:32:59', '2021-04-19 17:49:03'),
(2, 'Toy Cars', '102', '5 in 1 colorful transporter truck Toys set, including a big transporter, a mini airplane, a small Taxi, a small bus and a middle bus. Colored toy cars with cute expressions, cartoon drawings better arouse kid&#039;s curiosity and give children a happy world. The ideal gifts for birthdays, parties, Christmas and any festival.', 24.00, 639, '2021-06-01 19:42:58', '2021-06-01 19:26:28'),
(3, 'Oats Crunchy Honey Roasted Cereal', '103', 'Honey Bunches of Oats cereal is the perfect combination of crispy flakes, crunchy oat clusters with a touch of honey.', 12.00, 712, '2021-06-01 19:44:54', '2021-06-01 13:59:54'),
(4, 'KIND Breakfast Cereal', '104', 'Flavor: Apple Cinnamon, 100% whole grains', 21.55, 742, '2021-06-01 19:46:25', '2021-06-01 14:03:09'),
(5, 'Duck Brand Stretch Wrap', '105', 'Heavy 70 gauge plastic wrap safely secures and bundles items while moving, in storage or in transit', 33.00, 808, '2021-06-01 19:57:11', '2021-06-01 18:33:38'),
(6, 'Self Adhesive Clear Cookie Bags', '106', 'Our self-adhesive cookie bags are made of food grade opp plastic material with a matte surface design, and a comfortable feel which is safe to use, odor-free, non-toxic, durable for your daily or commercial use.', 8.00, 1265, '2021-06-01 19:59:09', '2021-06-01 14:14:09'),
(7, 'Lightweight Satin Soft Fabric', '107', 'Color: Green, Maroon, Red, Navy Blue, Brown, Violet, Pattern: Solid, Weave Type: Satin', 12.00, 531, '2021-06-01 20:04:42', '2021-06-01 18:29:37'),
(8, 'Anderson&#039;s Black Flame Retardant Gossamer Fab', '108', 'Color: Black, Red, Brown, Purple, Green, 59 Inches x 100 Yard', 12.00, 755, '2021-06-01 20:06:08', '2021-06-01 18:34:40'),
(9, 'Precut Quilting Sewing Fabric', '109', 'Color: Green, Blue, Grey, White, Black, Purple, 18 x 22 inches', 9.00, 811, '2021-06-01 20:10:01', '2021-06-01 14:25:01'),
(10, 'Orgnisulmte USDA Organic Dragon Fruit Powder', '110', '100% Pure Freeze-Dried Pink Pitaya Powder, Natural Red Dragon Fruit Food Coloring Powder,Non GMO,Gluten Free 3.53oz(100g)', 9.00, 563, '2021-06-01 20:15:45', '2021-06-01 14:30:45'),
(11, 'KIND Whole Fruit Bars', '111', 'Strawberry Apple Chia, Chocolate Bananam Dark Chocolate Strawberry No Sugar Added, 1.2oz, 12 Count', 11.00, 448, '2021-06-01 20:16:45', '2021-06-01 16:37:04'),
(12, 'Del Monte Fruit and Oats Snack Cups', '112', '7 Ounce, Premium Quality, No Preservatives', 11.55, 482, '2021-06-01 20:18:13', '2021-06-01 14:33:13'),
(13, 'Chicken of the Sea Sardines', '113', 'Extra Virgin Olive Oil, 3.75 oz (Pack of 18)', 20.06, 888, '2021-06-01 20:19:28', '2021-06-01 14:34:28'),
(14, 'Wild Sardines', '114', 'Skinless &amp; Boneless, in Extra Virgin Olive Oil, Lowest Mercury Limit, Keto, Paleo, 12 count, 4.4oz', 31.95, 691, '2021-06-01 20:20:38', '2021-06-01 18:31:34'),
(15, 'MW Polar Smoked Brisling Sardines in Olive Oil', '115', '9.5oz. Jar (Pack of 6), Wild caught in the Baltic Sea, Naturally wood smoked, Packaged in Olive Oil', 35.38, 992, '2021-06-01 20:22:26', '2021-06-01 16:31:05'),
(16, 'Bluetooth Headphones Over-Ear', '116', 'Zihnic Foldable Wireless and Wired Stereo Headset Micro SD/TF, FM for Cell Phone,PC,Soft Earmuffs &amp;Light Weight for Prolonged Waring(Rose Gold)', 23.91, 156, '2021-06-01 20:23:54', '2021-06-01 17:37:00'),
(17, 'Panasonic Full-Sized Headphones', '117', 'Contains Mic, Lightweight Long-Cord Headphones – RP-HT161-K (Black)', 13.77, 181, '2021-06-01 20:24:55', '2021-06-01 19:22:15'),
(18, 'Toshiba Canvio 1TB Portable External Hard Drive', '118', 'Item Dimensions: LxWxH	4.3 x 3.1 x 0.55 inches, Drive USB 3.0, Black', 62.55, 236, '2021-06-01 22:10:16', '2021-06-01 16:25:16'),
(19, 'Seagate BarraCuda 2TB Internal Hard Drive', '119', '3.5 Inch SATA 6Gb/s 7200 RPM 256MB Cache 3.5-Inch – Frustration Free Packaging', 71.50, 312, '2021-06-01 22:11:55', '2021-06-01 16:26:55'),
(20, 'Samsung BAR Plus USB 3.1 Flash Drive 128GB', '120', 'Brand: Samsung, Silver Color, Redefine everyday file transfers with read speeds up to 400MB/s; USB 3.1 flash drive with backwards compatibility (USB 3.0, USB 2.0)', 24.59, 589, '2021-06-01 22:13:30', '2021-06-01 16:28:30'),
(21, 'Multipurpose Copy Printer Paper', '121', 'White, 8.5 x 11 Inches, 3 Ream Case', 19.10, 5884, '2021-06-01 22:15:08', '2021-06-01 19:24:03'),
(22, 'Metronic Large Shipping Bags Poly Mailers', '122', '19x24 Envelopes Mailers with Self Adhesive Waterproof and Tear-Proof Postal Bags in White, Black, Blue, Grey', 4.99, 2464, '2021-06-01 22:26:40', '2021-06-01 16:43:26'),
(23, 'Crunchies Freeze-Dried Fruits Variety Pack', '123', '100% All Natural Crispy Snacks, Gluten Free and Vegan, 5.8 Ounce-6 pack', 17.00, 293, '2021-06-02 01:02:26', '2021-06-01 19:19:33');

-- --------------------------------------------------------

--
-- Table structure for table `lk_sess`
--

CREATE TABLE `lk_sess` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transId` bigint(20) UNSIGNED NOT NULL,
  `ref` varchar(10) NOT NULL,
  `itemName` varchar(50) NOT NULL,
  `itemCode` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(6) NOT NULL,
  `unitPrice` decimal(10,2) NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL,
  `totalMoneySpent` decimal(10,2) NOT NULL,
  `amountTendered` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `discount_percentage` decimal(10,2) NOT NULL,
  `vatPercentage` decimal(10,2) NOT NULL,
  `vatAmount` decimal(10,2) NOT NULL,
  `changeDue` decimal(10,2) NOT NULL,
  `modeOfPayment` varchar(20) NOT NULL,
  `cust_name` varchar(20) DEFAULT NULL,
  `cust_phone` varchar(15) DEFAULT NULL,
  `cust_email` varchar(50) DEFAULT NULL,
  `transType` char(1) NOT NULL,
  `staffId` bigint(20) UNSIGNED NOT NULL,
  `transDate` datetime NOT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cancelled` char(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transId`, `ref`, `itemName`, `itemCode`, `description`, `quantity`, `unitPrice`, `totalPrice`, `totalMoneySpent`, `amountTendered`, `discount_amount`, `discount_percentage`, `vatPercentage`, `vatAmount`, `changeDue`, `modeOfPayment`, `cust_name`, `cust_phone`, `cust_email`, `transType`, `staffId`, `transDate`, `lastUpdated`, `cancelled`) VALUES
(1, '765149033', 'Demo Item', '101', '', 1, 500.00, 500.00, 490.00, 500.00, 10.00, 2.00, 0.00, 0.00, 10.00, 'Cash', 'John', '3457896660', 'john@gmail.com', '1', 1, '2021-04-19 23:34:03', '2021-04-19 17:49:03', '0'),
(2, '23649438', 'KIND Breakfast Cereal', '104', '', 10, 21.55, 215.50, 211.50, 225.00, 4.00, 1.86, 0.00, 0.00, 13.50, 'Cash', 'Will Williams', '7410002145', 'williams@gmail.com', '1', 1, '2021-06-01 19:48:09', '2021-06-01 14:03:09', '0'),
(3, '439972', 'Duck Brand Stretch Wrap', '105', '', 67, 33.00, 2211.00, 2166.78, 2170.00, 44.22, 2.00, 0.00, 0.00, 3.22, 'Cash', 'John Watts', '7025802586', 'johnwt@gmail.com', '1', 1, '2021-06-01 20:00:41', '2021-06-01 14:15:41', '0'),
(4, '03941028', 'MW Polar Smoked Brisling Sardines in Olive Oil', '115', '', 3, 35.38, 106.14, 105.06, 110.00, 2.12, 2.00, 1.00, 1.04, 4.94, 'Cash', 'Johnny Greenwood', '7014547770', 'greenjoh@gmail.com', '1', 1, '2021-06-01 22:16:05', '2021-06-01 16:31:05', '0'),
(5, '872496', 'KIND Whole Fruit Bars', '111', '', 21, 11.00, 231.00, 226.38, 226.38, 4.62, 2.00, 0.00, 0.00, 0.00, 'POS', 'Colin Stuart', '7025896333', 'coleeen@gmail.com', '1', 1, '2021-06-01 22:22:04', '2021-06-01 16:37:04', '0'),
(6, '374217', 'Metronic Large Shipping Bags Poly Mailers', '122', '', 86, 4.99, 429.14, 429.14, 429.14, 0.00, 0.00, 0.00, 0.00, 0.00, 'Cash and POS', 'Philip Brown', '7347775477', 'philip@gmail.com', '1', 1, '2021-06-01 22:28:26', '2021-06-01 16:43:26', '0'),
(7, '18075809', 'Bluetooth Headphones Over-Ear', '116', '', 40, 23.91, 956.40, 946.64, 946.64, 19.13, 2.00, 1.00, 9.37, 0.00, 'Cash and POS', 'Eddie', '7001112540', 'eddie55@gmail.com', '1', 1, '2021-06-01 23:22:00', '2021-06-01 17:37:00', '0'),
(8, '192549', 'Lightweight Satin Soft Fabric', '107', '', 21, 12.00, 252.00, 254.52, 255.00, 0.00, 0.00, 1.00, 2.52, 0.48, 'Cash', 'Eugenio Brown', '7014747540', 'eugenio@gmail.com', '1', 1, '2021-06-02 00:14:37', '2021-06-01 18:29:37', '0'),
(9, '641908', 'Wild Sardines', '114', '', 11, 31.95, 351.45, 344.42, 345.00, 7.03, 2.00, 0.00, 0.00, 0.58, 'Cash', 'Peter Buchanan', '7321450014', 'peterb@gmail.com', '1', 1, '2021-06-02 00:16:34', '2021-06-01 18:31:34', '0'),
(10, '2346405', 'Duck Brand Stretch Wrap', '105', '', 250, 33.00, 8250.00, 8250.00, 8250.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'Cash', 'Arnold Flesher', '7940001240', 'arnold@gmail.com', '1', 1, '2021-06-02 00:18:38', '2021-06-01 18:33:38', '0'),
(11, '983709261', 'Anderson&#039;s Black Flame Retardant Gossamer Fab', '108', '', 97, 12.00, 1164.00, 1164.00, 1165.00, 0.00, 0.00, 0.00, 0.00, 1.00, 'Cash', 'John Bland', '7012220001', 'johnb@gmail.com', '1', 1, '2021-06-02 00:19:40', '2021-06-01 18:34:40', '0'),
(12, '32825746', 'Crunchies Freeze-Dried Fruits Variety Pack', '123', '', 76, 17.00, 1292.00, 1266.16, 1270.00, 25.84, 2.00, 0.00, 0.00, 3.84, 'Cash', 'Richard Lawrence', '7850001450', 'richardlw@gmail.com', '1', 1, '2021-06-02 01:04:33', '2021-06-01 19:19:33', '0'),
(13, '895691278', 'Panasonic Full-Sized Headphones', '117', '', 20, 13.77, 275.40, 280.91, 280.91, 0.00, 0.00, 2.00, 5.51, 0.00, 'POS', 'Liam Moore', '7010000025', 'moorel@gmail.com', '1', 1, '2021-06-02 01:07:15', '2021-06-01 19:22:15', '0'),
(14, '1230158', 'Multipurpose Copy Printer Paper', '121', '', 656, 19.10, 12529.60, 12279.01, 12279.01, 250.59, 2.00, 0.00, 0.00, 0.00, 'Cash and POS', 'Willam Payne', '7560002450', 'paynew@gmail.com', '1', 1, '2021-06-02 01:09:03', '2021-06-01 19:24:03', '0'),
(15, '069215370', 'Toy Cars', '102', '', 30, 24.00, 720.00, 734.40, 735.00, 0.00, 0.00, 2.00, 14.40, 0.60, 'Cash', 'John Doe', '7014445470', 'doejjj@gmail.com', '1', 3, '2021-06-02 01:11:28', '2021-06-01 19:26:28', '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `mobile1` (`mobile1`);

--
-- Indexes for table `eventlog`
--
ALTER TABLE `eventlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `eventlog`
--
ALTER TABLE `eventlog`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
