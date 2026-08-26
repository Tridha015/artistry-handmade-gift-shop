CREATE DATABASE IF NOT EXISTS `artistry_db`;
USE `artistry_db`;

-- ১. ইউজার টেবিল
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `phone` VARCHAR(20) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('Admin', 'Seller', 'Customer', 'Delivery') NOT NULL DEFAULT 'Customer',
  `status` ENUM('Active', 'Pending', 'Suspended') NOT NULL DEFAULT 'Active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ২. ক্রাফট প্রোডাক্ট টেবিল
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `seller_id` INT NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `stock` INT NOT NULL DEFAULT 1,
  `image` VARCHAR(255) NOT NULL DEFAULT 'default_craft.jpg',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`seller_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- ৩. কাস্টম ক্রাফট রিকোয়েস্ট টেবিল
CREATE TABLE IF NOT EXISTS `custom_orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `craft_type` VARCHAR(100) NOT NULL,
  `craft_size` VARCHAR(50) NOT NULL,
  `layers` INT DEFAULT 1,
  `color_theme` VARCHAR(100) NOT NULL,
  `budget` DECIMAL(10,2) NOT NULL,
  `sample_image` VARCHAR(255) DEFAULT 'sample1.jpg',
  `instructions` TEXT,
  `status` ENUM('Pending Review', 'Accepted', 'Making', 'Ready for Pickup', 'Rejected') DEFAULT 'Pending Review',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- ৪. রেডিমেড স্টোর অর্ডার টেবিল
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `total_price` DECIMAL(10,2) NOT NULL,
  `payment_gateway` VARCHAR(50) NOT NULL,
  `sender_number` VARCHAR(20) NOT NULL,
  `trx_id` VARCHAR(100) NOT NULL,
  `delivery_address` TEXT NOT NULL,
  `status` ENUM('Payment pending', 'Confirmed', 'Processing', 'Cancelled') DEFAULT 'Payment pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
);

-- ৫. ডেলিভারি টেবিল
CREATE TABLE IF NOT EXISTS `deliveries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL UNIQUE,
  `rider_id` INT NOT NULL,
  `delivery_address` TEXT NOT NULL,
  `delivery_status` ENUM('Assigned', 'Picked Up', 'Out for Delivery', 'Delivered', 'Failed') DEFAULT 'Assigned',
  `assigned_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`rider_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- ডিফল্ট ইউজার ডেটা (পাসওয়ার্ড: 123456)
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `status`) VALUES
(1, 'Super Admin', 'admin@artistry.com', '01700000000', '$2y$10$wE9l1i76c1iVl8pD0b/Y1uH2Gg8F7HkQn2sW5m0hO1pX0a2L1kKqG', 'Admin', 'Active'),
(2, 'Rafiq Craft Artisan', 'rafiq@gmail.com', '01822998877', '$2y$10$wE9l1i76c1iVl8pD0b/Y1uH2Gg8F7HkQn2sW5m0hO1pX0a2L1kKqG', 'Seller', 'Active'),
(3, 'Sadia Customer', 'sadia@gmail.com', '01911223344', '$2y$10$wE9l1i76c1iVl8pD0b/Y1uH2Gg8F7HkQn2sW5m0hO1pX0a2L1kKqG', 'Customer', 'Active'),
(4, 'Shakil Rider', 'shakil@artistry.com', '01511223344', '$2y$10$wE9l1i76c1iVl8pD0b/Y1uH2Gg8F7HkQn2sW5m0hO1pX0a2L1kKqG', 'Delivery', 'Active')
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);