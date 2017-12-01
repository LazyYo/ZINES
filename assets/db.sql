-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 01, 2017 at 11:23 PM
-- Server version: 5.6.28
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `zines`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `mail` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'data/incal.jpg',
  `first_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Membres';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `mail`, `username`, `password`, `avatar`, `first_name`, `last_name`) VALUES
(1, 'castellani.yoan@gmail.com', 'LazyYo', '$2y$10$ZSy71l0rVlySGePqQHIUt.ABmGmuwjVwddH5Cfh0c5SzxCdCnPCR6', 'assets/medias/user-1-avatar.jpg', 'Yoan', 'Castellani'),
(2, 'rollinger.nicolas@gmail.com', 'RLLN', '$2y$10$ZSy71l0rVlySGePqQHIUt.ABmGmuwjVwddH5Cfh0c5SzxCdCnPCR6', 'assets/medias/user-2-avatar.png', 'Nicolas', 'Rollinger');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
