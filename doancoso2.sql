-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for shopbangiay
CREATE DATABASE IF NOT EXISTS `shopbangiay` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `shopbangiay`;

-- Dumping structure for table shopbangiay.address
CREATE TABLE IF NOT EXISTS `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT 0,
  `name` varchar(225) DEFAULT NULL,
  `country` varchar(50) NOT NULL DEFAULT '0',
  `city` varchar(50) NOT NULL DEFAULT '0',
  `address_line` varchar(50) NOT NULL DEFAULT '0',
  `phone` varchar(50) DEFAULT NULL,
  `is_default` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_address_user` (`user_id`),
  CONSTRAINT `FK_address_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopbangiay.address: ~2 rows (approximately)
INSERT INTO `address` (`id`, `user_id`, `name`, `country`, `city`, `address_line`, `phone`, `is_default`, `created_at`, `updated_at`) VALUES
	(1, 7, 'Trương Công Thành', 'Việt Nam', 'Đà Nẵng', '16/3 Nguyễn Hữu Thọ', '0898543702', 1, '2024-11-25 01:40:29', '2024-11-25 01:40:29'),
	(2, 8, 'Công Thành', 'Vietnam', 'Bình Phú, Thăng Bình, Quảng Nam', '1', '0776389977', 0, '2024-11-25 02:50:29', '2024-11-25 02:50:29');

-- Dumping structure for table shopbangiay.cart
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopbangiay.cart: ~3 rows (approximately)
INSERT INTO `cart` (`id`) VALUES
	(1),
	(2),
	(3);

-- Dumping structure for table shopbangiay.carthasproduct
CREATE TABLE IF NOT EXISTS `carthasproduct` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idCart` int(11) NOT NULL DEFAULT 0,
  `idProduct` bigint(20) unsigned NOT NULL DEFAULT 0,
  `color` varchar(50) NOT NULL DEFAULT '',
  `quantity` int(11) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `isPay` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_carthasproduct_cart` (`idCart`),
  KEY `FK_carthasproduct_products` (`idProduct`),
  CONSTRAINT `FK_carthasproduct_cart` FOREIGN KEY (`idCart`) REFERENCES `cart` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_carthasproduct_products` FOREIGN KEY (`idProduct`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopbangiay.carthasproduct: ~0 rows (approximately)

-- Dumping structure for table shopbangiay.comment
CREATE TABLE IF NOT EXISTS `comment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idUser` int(11) unsigned NOT NULL DEFAULT 0,
  `idProduct` bigint(20) unsigned NOT NULL DEFAULT 0,
  `content` bigint(20) NOT NULL,
  `rate` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_comment_user` (`idUser`),
  KEY `FK_comment_products` (`idProduct`),
  CONSTRAINT `FK_comment_products` FOREIGN KEY (`idProduct`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_comment_user` FOREIGN KEY (`idUser`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopbangiay.comment: ~0 rows (approximately)

-- Dumping structure for table shopbangiay.favourite
CREATE TABLE IF NOT EXISTS `favourite` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idproduct` int(11) NOT NULL DEFAULT 0,
  `idUser` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopbangiay.favourite: ~0 rows (approximately)

-- Dumping structure for table shopbangiay.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(255) NOT NULL,
  `total_payment` decimal(20,6) DEFAULT NULL,
  `order_date` timestamp NULL DEFAULT current_timestamp(),
  `delivery_date` timestamp NOT NULL DEFAULT (current_timestamp() + interval 15 day) ON UPDATE current_timestamp(),
  `address` varchar(50) DEFAULT NULL,
  `idUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopbangiay.orders: ~1 rows (approximately)
INSERT INTO `orders` (`id`, `status`, `total_payment`, `order_date`, `delivery_date`, `address`, `idUser`) VALUES
	(40, 'Delivered', 750000.000000, '2024-11-25 11:25:46', '2024-11-27 15:03:54', '16/3 Nguyễn Hữu Thọ, Đà Nẵng, Việt Nam', 7),
	(47, 'Delivered', 667500.000000, '2024-11-30 01:27:30', '2024-11-30 02:20:44', '16/3 Nguyễn Hữu Thọ, Đà Nẵng, Việt Nam', 7);

-- Dumping structure for table shopbangiay.order_detail
CREATE TABLE IF NOT EXISTS `order_detail` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idOrders` bigint(20) unsigned NOT NULL DEFAULT 0,
  `color` varchar(50) NOT NULL DEFAULT '',
  `price` int(11) NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `size` int(11) NOT NULL DEFAULT 0,
  `name` varchar(50) NOT NULL DEFAULT '0',
  `img` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `FK_order_detail_orders` (`idOrders`),
  CONSTRAINT `FK_order_detail_orders` FOREIGN KEY (`idOrders`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopbangiay.order_detail: ~1 rows (approximately)
INSERT INTO `order_detail` (`id`, `idOrders`, `color`, `price`, `quantity`, `size`, `name`, `img`) VALUES
	(21, 40, 'purple', 750000, 1, 7, 'Nike Air Zoom GT Cut 3 EP', '../../Admin/img/1.png'),
	(28, 47, 'purple', 750000, 1, 7, 'Nike Air Zoom GT Cut 3 EP', '../../Admin/img/1.png');

-- Dumping structure for table shopbangiay.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(999) NOT NULL,
  `desc` varchar(999) NOT NULL,
  `price` bigint(200) NOT NULL,
  `img` varchar(999) NOT NULL,
  `brands` varchar(999) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `comment` varchar(500) NOT NULL DEFAULT '',
  `idSale` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopbangiay.products: ~3 rows (approximately)
INSERT INTO `products` (`id`, `name`, `desc`, `price`, `img`, `brands`, `gender`, `comment`, `idSale`) VALUES
	(1, 'Nike Air Zoom GT Cut 3 EP', 'The Nike Air Zoom GT Cut 3 EP combines advanced technology with contemporary design, making it a top choice for basketball players looking for both performance and style.', 1000000, 'purple<>1.png,aqua<>1_aqua.png,#ffa200<>cropped7866.png', 'Nike', 'Male', '5☆congthanh: the product is really great||5☆thanhPhong : it good||4☆longnhat: very comfort !!  || 3☆CongThanh: okk || 4☆CongThanh: yeah sir || 4☆CongThanh: good job || 3☆CongThanh: It really good shoes', 1),
	(2, 'The Zegama Men\'s Trail', 'The Zegama Men\'s Trail-Running Shoes are ideal for those who tackle challenging trails, combining comfort, support, and traction to enhance performance during outdoor adventures.', 400000, 'darkgreen<>2.png', 'Nike', 'Male', '', 5),
	(3, 'NIKECOURT Vapor Lite 2 Premium Men', 'The NikeCourt Vapor Lite 2 Premium is an excellent choice for tennis enthusiasts looking for a blend of performance, comfort, and style, helping players perform their best while looking great on the court.', 300000, 'pink<>3.png,#059d6d<>cropped8343.png', 'Puma', 'Male', '', 0);

-- Dumping structure for table shopbangiay.sales
CREATE TABLE IF NOT EXISTS `sales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `pos` varchar(5000) DEFAULT NULL,
  `color` varchar(5000) DEFAULT NULL,
  `discount` varchar(50) DEFAULT NULL,
  `bigsales` varchar(6000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopbangiay.sales: ~5 rows (approximately)
INSERT INTO `sales` (`id`, `name`, `pos`, `color`, `discount`, `bigsales`) VALUES
	(1, 'Sale 25% off', 'top-left', '#ff1500', '25%', NULL),
	(2, 'Sale 30% off', 'top-right', '#ffae00', '30%', NULL),
	(3, 'Sale 50% off for unisex', 'bottom-right', '#a800f0', '50%', NULL),
	(4, 'Sale 70% off Black Friday', 'bottom-full', '#000000', '70%', 'sales95215.png<>\r\nBLACK FRIDAY BÙNG NỔ – DEAL CỰC SỐC!\r\nMUA SẮM THẢ GA – KHÔNG LO VỀ GIÁ\r\n💥 Giảm Giá Tới 70% trên hàng loạt mẫu giày và sneaker từ các thương hiệu hàng đầu: Nike, Adidas, Converse, và nhiều hơn thế nữa!\r\n\r\nCác Ưu Đãi Nổi Bật Chỉ Trong Dịp Black Friday:\r\nFlash Sale Giờ Vàng – Cơ hội sở hữu giày sneaker xịn với giá giảm sâu chưa từng có!\r\nCombo Ưu Đãi – Mua đôi thứ 2 giảm thêm 10%, mua đôi thứ 3 giảm đến 75%.\r\nQuà Tặng Kèm Theo – Tặng miễn phí vớ hoặc phụ kiện giày khi mua đơn hàng từ 1 triệu đồng trở lên.'),
	(5, 'Siêu Sales', 'middle-full', '#ff8a8a', '30%', 'sales62982.jpg<><p><em><strong>Si&ecirc;u sale</strong></em></p>\n<p><em><strong>Ng&agrave;y 12/12 (Shuangshi tại Trung Quốc), đ&acirc;y l&agrave; một trong những ng&agrave;y mua h&agrave;ng giảm gi&aacute; c&oacute; quy m&ocirc; lớn h&agrave;ng năm tại Trung Quốc với những hoạt động mua sắm rất lớn.</strong></em></p>\n<p><em><strong>Đ&acirc;y được coi l&agrave; ng&agrave;y &ldquo;Thương x&oacute;t t&iacute;n đồ mua sắm&rdquo; hoặc &ldquo;Th&iacute;ch th&igrave; giảm gi&aacute;&rdquo; tại quốc gia n&agrave;y. L&yacute; do ra đời những c&aacute;i t&ecirc;n ngộ nghĩnh như vậy l&agrave; bởi v&igrave; những người bu&ocirc;n Trung Quốc thường tận dụng ng&agrave;y n&agrave;y để tăng doanh thu từ cơn sốt giảm gi&aacute; ng&agrave;y 11/11.</strong></em></p>');

-- Dumping structure for table shopbangiay.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(999) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `idCart` int(11) NOT NULL DEFAULT 0,
  `chatbox` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_user_cart` (`idCart`),
  CONSTRAINT `FK_user_cart` FOREIGN KEY (`idCart`) REFERENCES `cart` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopbangiay.user: ~3 rows (approximately)
INSERT INTO `user` (`id`, `username`, `email`, `phone`, `password`, `avatar`, `role`, `idCart`, `chatbox`) VALUES
	(7, 'CongThanh', 'congthanh10000@gmail.com', '', '$2y$10$cLjV8TxEGVMKk64L0bDP7eIHeadaiMD42ieV5Vcg21O9Afy5KgGxG', 'user24755.jpg', 'customer', 1, '👤CongThanh👤Xin chào cho tôi hỏi 🛠️AdminThanh🛠️bạn muốn hỏi gì nào  👤CongThanh👤Tôi muốn biết về thông tin sale gần nhất 👤CongThanh👤chào 👤CongThanh👤hello 👤CongThanh👤này 👤CongThanh👤chào 👤CongThanh👤tôi hỏi bạn 1 chút  👤CongThanh👤bạn ơi 🛠️AdminThanh🛠️sao bạn ơi️ 👤CongThanh👤không có chi 👤CongThanh👤ohaiyou 👤CongThanh👤yamate 🛠️AdminThanh🛠️a iku iku 👤CongThanh👤xin chào'),
	(8, 'AdminThanh', 'helloban0909@gmail.com', '0776389977', '$2y$10$divI4Rn1rcblcmeVPRSypOUlgMYblouvBb9IKZEYwwwkNbubWUIv.', 'user81916.jpg', 'admin', 2, NULL),
	(18, 'ThanhPhong', 'phongtt.23it@vku.udn.vn', '', '$2y$10$divI4Rn1rcblcmeVPRSypOUlgMYblouvBb9IKZEYwwwkNbubWUIv.', '', 'customer', 3, ' 👤ThanhPhong👤xin chào 🛠️AdminThanh🛠️sao bạn ơi');

-- Dumping structure for table shopbangiay.voucher
CREATE TABLE IF NOT EXISTS `voucher` (
  `id` varchar(50) NOT NULL DEFAULT 'AUTO_INCREMENT',
  `discount` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table shopbangiay.voucher: ~1 rows (approximately)
INSERT INTO `voucher` (`id`, `discount`) VALUES
	('SALES12', '20%'),
	('SIEUSALE11', '11%');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
