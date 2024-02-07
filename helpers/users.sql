-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2024 at 10:27 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `africa_relief`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_login` varchar(60) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `password` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `phone` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_login`, `password`, `user_nicename`, `email`, `name`, `phone`, `address`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'webmaster@arcd', '$P$B17w8ziZRItijd6OGMh49AbS6prHNU0', 'webmasterarcd', 'eng.safaa_2011@yahoo.com', 'webmaster@arcd', NULL, NULL, NULL, NULL, NULL),
(2, 'info@rubikcreative.com', '$P$Bn7h/SAGcAiu3bI4O6QUkiM8n7etpt/', 'inforubikcreative-com', 'info@rubikcreative.com', 'Africa Relief', NULL, NULL, NULL, NULL, NULL),
(3, 'bassam.khalaf', '$P$BVCqTrX7NzR19KxvwA8rrR4he8lMlS1', 'bassam-khalaf', 'bassamb85@gmail.com', 'Bassam Khalaf', NULL, NULL, NULL, NULL, NULL),
(6, 'helzeidy303', '$P$BmNZHBLHFIrp2ewpQwidsSB9nLT826.', 'helzeidy303', 'helzeidy303@gmail.com', 'helzeidy303', NULL, NULL, NULL, NULL, NULL),
(7, 'shaheer.mirza', '$P$B2CrHfnZr6Or1Tsk571agbQQ1TjkjG1', 'shaheer-mirza', 'shamirza224@gmail.com', 'Shaheer Mirza', NULL, NULL, NULL, NULL, NULL),
(8, 'ahmad.osman', '$P$BKY1dZX/UlqaX8g7YG8kBT1hevSaXW.', 'ahmad-osman', 'kareem111111@gmail.com', 'Ahmad Osman', NULL, NULL, NULL, NULL, NULL),
(9, 'jouhara.saadeh', '$P$BjycTIgGIyCP.qeFL28HIAYOvFtgYK1', 'jouhara-saadeh', 'zainebsaadeh@gmail.com', 'Jouhara Saadeh', NULL, NULL, NULL, NULL, NULL),
(10, 'agendy_82', '$P$B1MC68fsqaOlgiCRRnvAMNHq42MXmr/', 'agendy_82', 'agendy_82@yahoo.com', 'Abdalrahman Algendy', NULL, NULL, NULL, NULL, NULL),
(11, 'makamel', '$P$BIPqlL2i1fRl6vQhYeIr/1xcL/D64e1', 'makamel', 'makamel@gmail.com', 'makamel', NULL, NULL, NULL, NULL, NULL),
(12, 'bouzareah', '$P$B3B0tv6ysH0VLq1bI1tw3Qv5Fy4MVk.', 'bouzareah', 'bouzareah@msn.com', 'bouzareah', NULL, NULL, NULL, NULL, NULL),
(13, 'zuhaib.mirza', '$P$BO59IRiRxQls7a5Vlq6vdI64bfZhoj/', 'zuhaib-mirza', 'zm1232000x2@gmail.com', 'Zuhaib Mirza', NULL, NULL, NULL, NULL, NULL),
(14, 'haytham_dahman', '$P$B.BS9GAVqkMQItFtA2Jx8MrIzD6GA80', 'haytham_dahman', 'haytham_dahman@hotmail.com', 'haytham_dahman', NULL, NULL, NULL, NULL, NULL),
(15, 'stvmaozfzb', '$P$BR/R67gkxbsEKe2kvi5awJUWa9BMSp0', 'stvmaozfzb', 'stvmaozfzb@mail.com', 'stvmaozfzb', NULL, NULL, NULL, NULL, NULL),
(16, 'fatima', '$P$BhRCHSYgOuwOhxtLzmV5Uf0.vKnTLG.', 'fatima', 'fatima@africa-relief.org', 'fatima', NULL, NULL, NULL, NULL, NULL),
(17, 'ahmed.elshazly', '$P$BBjp1y.0Tx3l2EFwzEAJJGfbprPNzn.', 'ahmed-elshazly', 'a.elshazly94@gmail.com', 'Ahmed Elshazly', NULL, NULL, NULL, NULL, NULL),
(18, 'qusai.farraj', '$P$BKAwEm2/cmiOiLT.PICO3ziYRdp76Q/', 'qusai-farraj', 'qusai919@gmail.com', 'Qusai Farraj', NULL, NULL, NULL, NULL, NULL),
(19, 'Hassan', '$P$Bip9DYcYknTp928FMaTtRPDXD/NV9b0', 'hassan', 'hassan10ahmed@gmail.com', 'Hassan Ahmed', NULL, NULL, NULL, NULL, NULL),
(20, 'mmiraoui2010', '$P$BKpSOfZ.fX9vRqVxT3/PRasBda.ANf0', 'mmiraoui2010', 'mmiraoui2010@yahoo.com', 'Moez', NULL, NULL, NULL, NULL, NULL),
(21, 'eyadhasan', '$P$BftTWQWARvVW2hyySyyOhoDsGVDV0L0', 'eyadhasan', 'eyadhasan@hotmail.com', 'eyadhasan', NULL, NULL, NULL, NULL, NULL),
(22, 'aqifameer', '$P$BmQeJToAG62tVrzGLXE/qM9OsqVWeN.', 'aqifameer', 'aqifameer@hotmail.com', 'aqifameer', NULL, NULL, NULL, NULL, NULL),
(23, 'samah.mahmoud', '$P$BzGQQTYGD9DN9qmsXD1BPMZHOKbCjY1', 'samah-mahmoud', 'samahmahmoud2002@yahoo.com', 'Samah mahmoud', NULL, NULL, NULL, NULL, NULL),
(24, 'msalamah81', '$P$BJLLUDapyRwOYlaw82bp4KzBOT3/Sn.', 'msalamah81', 'msalamah81@gmail.com', 'msalamah81', NULL, NULL, NULL, NULL, NULL),
(25, 'kareman.ibrahem', '$P$BOxebmqgA2b93SqOvLmITDoEj8Rb3u/', 'kareman-ibrahem', 'kibrahem8190@gmail.com', 'Kareman Ibrahem', NULL, NULL, NULL, NULL, NULL),
(26, 'sabreen.salem', '$P$Bf5PDHMLnV6her509qzvNAxyJVOzTL.', 'sabreen-salem', 'sabreensalem20@gmail.com', 'Sabreen Salem', NULL, NULL, NULL, NULL, NULL),
(27, 'abdulrahimhamadeh', '$P$BfNw.vUemfnyJyxMBQSgzazQ6d2NQZ1', 'abdulrahimhamadeh', 'abdulrahimhamadeh@yahoo.com', 'abdulrahimhamadeh', NULL, NULL, NULL, NULL, NULL),
(28, 'alwareed.abdellatif', '$P$BLnTCpd9GUm/J0DGGHQ3jE928.adDZ1', 'alwareed-abdellatif', 'alwareed@buffalo.edu', 'Alwareed Abdellatif', NULL, NULL, NULL, NULL, NULL),
(29, 'mohammad.ossaimee', '$P$BsjlO5De7DRk2Pw6Ol5yGZVzVh9G5O/', 'mohammad-ossaimee', 'ossaimee@hotmail.com', 'Mohammad Ossaimee', NULL, NULL, NULL, NULL, NULL),
(30, 'mohammad.adam', '$P$BaMaNeKrZptyiqAjIAPgXZ5q6nOUhC0', 'mohammad-adam', 'erum_sqbhatti@yahoo.com', 'Mohammad Adam', NULL, NULL, NULL, NULL, NULL),
(31, 'rebhi.karkat', '$P$Bu3ftnF58ECbxVBE1qTCtakLHXS1yk.', 'rebhi-karkat', 'Mkarkat@yahoo.com', 'Rebhi karkat', NULL, NULL, NULL, NULL, NULL),
(32, 'jodyhusam', '$P$B3Qu40KWygu/hHsNe3FnQi6JWxTyIQ.', 'jodyhusam', 'jodyhusam@gmail.com', 'jodyhusam', NULL, NULL, NULL, NULL, NULL),
(33, 'mfo4', '$P$BYJV4dTAS1CKHn8GwbKUvweDOXs9lo0', 'mfo4', 'mfo4@cornell.edu', 'mfo4', NULL, NULL, NULL, NULL, NULL),
(34, 'kamelgharaibeh', '$P$BF9D7gWZa66SzD/Wp94Muwue/4VtWa1', 'kamelgharaibeh', 'kamelgharaibeh@yahoo.com', 'kamelgharaibeh', NULL, NULL, NULL, NULL, NULL),
(35, 'zaid.bayan', '$P$Bdek6MZTbm50PhMyEpI89jawSceNrt0', 'zaid-bayan', 'zaidinc7@gmail.com', 'Zaid Bayan', NULL, NULL, NULL, NULL, NULL),
(36, 'billel.djemil', '$P$B7O./hnINP.dZo/SQrW7W/z7zWNZwp/', 'billel-djemil', 'billel.djemil@gmail.com', 'Billel Djemil', NULL, NULL, NULL, NULL, NULL),
(37, 'majed.abid', '$P$BlkLoHRNzy.XHQYZrIEs3sgIrdU1yJ.', 'majed-abid', 'majed.abid@hotmail.com', 'majed.abid', NULL, NULL, NULL, NULL, NULL),
(38, 'ashraf.hasan ali', '$P$BY6xBFNCkbacacUd2WNwx9Mq9hmQnp/', 'ashraf-hasan-ali', 'myashraf83@gmail.com', 'Ashraf Hasan Ali', NULL, NULL, NULL, NULL, NULL),
(39, 'mohamed.ganda', '$P$BdKnapkhx9sm8cbAAikx5mkMmvw6SH1', 'mohamed-ganda', 'm.ganda2000@gmail.com', 'Mohamed Ganda', NULL, NULL, NULL, NULL, NULL),
(40, 'btricic', '$P$Bf9lcLeGwAF2K4Cjp6Uf1GGW4uZqkx0', 'btricic', 'btricic@gmail.com', 'btricic', NULL, NULL, NULL, NULL, NULL),
(41, 'mohammed.bayan', '$P$BDhTX5DQ1zZ7ob0D.I5WI4hInMWrVF0', 'mohammed-bayan', 'mohammedbayan98@gmail.com', 'Mohammed Bayan', NULL, NULL, NULL, NULL, NULL),
(42, 'ahmad.elrefahy', '$P$BVLPWsdeoyYPAggOxRr8Pf3w5VzWwZ/', 'ahmad-elrefahy', 'ahmedhusam72@gmail.com', 'Ahmad Elrefahy', NULL, NULL, NULL, NULL, NULL),
(43, 'obiedofcairo', '$P$BQGSoSyQtfUNdoIbOWe4MDtnN3Iljw/', 'obiedofcairo', 'obiedofcairo@yahoo.com', 'obiedofcairo', NULL, NULL, NULL, NULL, NULL),
(44, 'synsoliman', '$P$B8UrIOLlI16X.uPUIBYQ7KKTrn3fD6.', 'synsoliman', 'synsoliman@gmail.com', 'synsoliman', NULL, NULL, NULL, NULL, NULL),
(45, 'sleemao', '$P$BVUtv1vvJPd0OYqRESJ4rp/QkLAgDK/', 'sleemao', 'sleemao@gmail.com', 'sleemao', NULL, NULL, NULL, NULL, NULL),
(46, 'moustafamaarouf99', '$P$BpgRWP7Epq5JLpvxYp7z8CzXTdGdeE1', 'moustafamaarouf99', 'moustafamaarouf99@gmail.com', 'moustafamaarouf99', NULL, NULL, NULL, NULL, NULL),
(47, 'thaeird', '$P$BYEkeKZ413e/m4MlpOpeLlD2./J3e10', 'thaeird', 'thaeird@gmail.com', 'thaeird', NULL, NULL, NULL, NULL, NULL),
(48, 'omer.javed', '$P$BzrIOljVraKBilpXSDc/fs505aBQHD.', 'omer-javed', 'omerjaved2@gmail.com', 'Omer Javed', NULL, NULL, NULL, NULL, NULL),
(49, 'mm_eldeeb', '$P$Bkg/BltpTdHWNEgPxthtquLT7RnakO/', 'mm_eldeeb', 'mm_eldeeb@yahoo.com', 'mm_eldeeb', NULL, NULL, NULL, NULL, NULL),
(50, 'ashraf.elthmody', '$P$Br63zmdn6PqrwaHfwQVUSO4YrV8rIn.', 'ashraf-elthmody', 'lmody@yahoo.com', 'Ashraf Elthmody', NULL, NULL, NULL, NULL, NULL),
(51, 'ehabfikry2005', '$P$B5wOD7N3VxWIgG3e3.n6U50b2hx2gj/', 'ehabfikry2005', 'ehabfikry2005@gmail.com', 'ehabfikry2005', NULL, NULL, NULL, NULL, NULL),
(52, 'mahmoud.azzam', '$P$BcXH9PEDn1WYrvuOSmcp6GGxShttJq.', 'mahmoud-azzam', 'mkazzam@yahoo.com', 'Mahmoud azzam', NULL, NULL, NULL, NULL, NULL),
(53, 'sharif.elhosseiny', '$P$BrvFNCAvYajv5AdJbEVUN113oEbwnF.', 'sharif-elhosseiny', 'Sharif@accessmedlab.com', 'Sharif Elhosseiny', NULL, NULL, NULL, NULL, NULL),
(54, 'abdulaijawara281', '$P$BR11qk8UQBS1tD7L9Ttq16bDdkfqKb1', 'abdulaijawara281', 'abdulaijawara281@gmail.com', 'abdulaijawara281', NULL, NULL, NULL, NULL, NULL),
(55, 'gasdo.org', '$P$BZhJne84U0F18jhdBvPRu9tGvHz6Iw/', 'gasdo-org', 'gasdo.org@gmail.com', 'gasdo.org', NULL, NULL, NULL, NULL, NULL),
(56, 'ahmedelladl', '$P$BiQRCqJCCWsZmleu9x4b6GMqrVU0yA/', 'ahmedelladl', 'ahmedelladl@gmail.com', 'Ahmed Aboelenen', NULL, NULL, NULL, NULL, NULL),
(57, 'ibrahim.fahmy', '$P$B3O4kNwR3TdMj3ehRh94n83haBv5.w.', 'ibrahim-fahmy', 'ib.fahmy@gmail.com', 'Ibrahim Fahmy', NULL, NULL, NULL, NULL, NULL),
(58, 'nedal.sumrein', '$P$B7Wp4glkoZMMhO026Wl0SckzPFfQ03/', 'nedal-sumrein', 'nsumrein@gmail.com', 'Nedal Sumrein', NULL, NULL, NULL, NULL, NULL),
(59, 'jianglinxuan1', '$P$BNWhLgZfBhb5NM7g86sUiYU6Jqx.rg.', 'jianglinxuan1', 'jianglinxuan1@gmail.com', 'jianglinxuan1', NULL, NULL, NULL, NULL, NULL),
(60, 'mohammad.elrefaie', '$P$BJMnd2c5a2xOxSqqoLYlikuO8.vIO80', 'mohammad-elrefaie', 'mohammad.elref96@gmail.com', 'Mohammad Elrefaie', NULL, NULL, NULL, NULL, NULL),
(61, 'yasser.khouj', '$P$B..buPtfsjUC4WOWM1mV1XS3yhrz7e0', 'yasser-khouj', 'yasser.kj@gmail.com', 'Yasser Khouj', NULL, NULL, NULL, NULL, NULL),
(62, 'redouane.kaloune', '$P$B5U5gcuyPNtIoPAe4ETMf0bDolIl.p0', 'redouane-kaloune', 'redouanekaloune@yahoo.com', 'Redouane Kaloune', NULL, NULL, NULL, NULL, NULL),
(63, 'abdelkader.alrout', '$P$Bdn2lGcoAMW0xKYCLEoM9Da.9xZSTz0', 'abdelkader-alrout', 'kaderakrout@hotmail.com', 'Abdelkader Alrout', NULL, NULL, NULL, NULL, NULL),
(64, 'wajiha.basit', '$P$BC1SwEQK2m8z0CUI0.RnPcBEN19io00', 'wajiha-basit', 'wajihabasit@gmail.com', 'Wajiha Basit', NULL, NULL, NULL, NULL, NULL),
(65, 'amr.kotb', '$P$Ba4SIzB1kbTs2pqdIZf2kAUddQccct1', 'amr-kotb', 'amr.kotb@icloud.com', 'amr.kotb', NULL, NULL, NULL, NULL, NULL),
(66, 'hasmerza', '$P$BPtaE0K6/UaSj9e/I.rzBNCN27lFl2.', 'hasmerza', 'hasmerza@yahoo.com', 'hasmerza', NULL, NULL, NULL, NULL, NULL),
(67, 'kelvin1234gyimah', '$P$B7.0CDK/SQBEHLreRjfolWrzqXvkeB.', 'kelvin1234gyimah', 'kelvin1234gyimah@gmail.com', 'kelvin1234gyimah', NULL, NULL, NULL, NULL, NULL),
(68, 'omar.saleh', '$P$BhhCdIaSF9.ExEuXiNQNHYBUFQtU04/', 'omar-saleh', 'omarsaleh222@gmail.com', 'Omar Saleh', NULL, NULL, NULL, NULL, NULL),
(69, 'ajilisamar.army', '$P$BTZlnD4E.xKzwBx5S1lEduT1X9o5DD/', 'ajilisamar-army', 'ajilisamar.army@gmail.com', 'ajilisamar.army', NULL, NULL, NULL, NULL, NULL),
(70, 'BGA', '$P$B5U0CfgUvC5qzHCw8Qm7J2iJD.EYkK0', 'bga', 'admin@africa-relief.org', 'ARCD', NULL, NULL, NULL, NULL, NULL),
(71, 'hesham.el-rewini', '$P$BoGQ9fdyMgBN/LwuwZmM.ZhP/2XjZl/', 'hesham-el-rewini', 'rewini77@gmail.com', 'Hesham El-Rewini', NULL, NULL, NULL, NULL, NULL),
(72, 'azeem.siddiqui', '$P$BlQjw2iYb5xw4q0UDzudffLitlmFKx1', 'azeem-siddiqui', 'ajsiddiqui041992@gmail.com', 'AZEEM SIDDIQUI', NULL, NULL, NULL, NULL, NULL),
(73, 'mouhcine.lazraq', '$P$B1pX1gYXh78PFHeJOdg/ahCvk1LP97/', 'mouhcine-lazraq', 'Lazraq81@gmail.com', 'MOUHCINE Lazraq', NULL, NULL, NULL, NULL, NULL),
(74, 'zeeshan.anwar', '$P$BDbRW5vBeTk76P3ghSapIEZmXj78h71', 'zeeshan-anwar', 'zeeshananvar@gmail.com', 'Zeeshan Anwar', NULL, NULL, NULL, NULL, NULL),
(75, 'asismaeil', '$P$B7OV9IeqaBP7ZSSzAix/O0qNkYy3nN.', 'asismaeil', 'asismaeil@verizon.net', 'ASHRAF ISMAEIL', NULL, NULL, NULL, NULL, NULL),
(76, 'adelalyusa', '$P$BC6IJsvbzrLau7OC0qsq5cWVFoQep/.', 'adelalyusa', 'adelalyusa@gmail.com', 'adelalyusa', NULL, NULL, NULL, NULL, NULL),
(77, 'esra.eltilib', '$P$BA97h7KWc0pHAx9QQ1.VWenH1SAIQI0', 'esra-eltilib', 'esraeltilib@yahoo.com', 'Esra Eltilib', NULL, NULL, NULL, NULL, NULL),
(78, 'iqra.yousuf', '$P$BV.sDhOrtnsJ87vg9.qMUHoxyCkeXd1', 'iqra-yousuf', 'iqrayousuf789@gmail.com', 'Iqra Yousuf', NULL, NULL, NULL, NULL, NULL),
(79, 'manar_nwab', '$P$BNneh9EJ.gJFL8zIUCbILzT94WTnL71', 'manar_nwab', 'manar_nwab@yahoo.com', 'manar_nwab', NULL, NULL, NULL, NULL, NULL),
(80, 'mohamed.elhadary', '$P$BgPzo3VYFb.dceVx4pPhbZR7r0ub5Q1', 'mohamed-elhadary', 'm.sakr87@yahoo.com', 'Mohamed Elhadary', NULL, NULL, NULL, NULL, NULL),
(81, 'adel.sabour', '$P$BxpCLiFb.5E7oL25nOBC5sufk06Y2p1', 'adel-sabour', 'adelsabour@gmail.com', 'Adel Sabour', NULL, NULL, NULL, NULL, NULL),
(82, 'e.hilali', '$P$Btva2mWOXkVtqTf/CtfBMAQ/uYhzOC/', 'e-hilali', 'e.hilali@africa-relief.org', 'e.hilali', NULL, NULL, NULL, NULL, NULL),
(83, 'mohamed17aweys', '$P$BNMuT6R8.NgEQzvX2HmFjUYyl4xJZq/', 'mohamed17aweys', 'mohamed17aweys@gmail.com', 'mohamed17aweys', NULL, NULL, NULL, NULL, NULL),
(84, 'tawfiq.aboudahech', '$P$BhXR3.y73.n3NScAabgOn9nvdckxjC0', 'tawfiq-aboudahech', 'tawfiq.aboudahech@gmail.com', 'tawfiq.aboudahech', NULL, NULL, NULL, NULL, NULL),
(85, 'jbadr1', '$P$BPGnfSe7Uq1pgh7a7qdjPRb.HzhyLn1', 'jbadr1', 'jbadr1@verizon.net', 'jbadr1', NULL, NULL, NULL, NULL, NULL),
(86, 'elmansy8', '$P$BY3zgEGTUCGoHrCMMH/oylpM4Q09Zu/', 'elmansy8', 'Elmansy8@hotmail.com', 'Mohamed', NULL, NULL, NULL, NULL, NULL),
(87, 'osman.nayeem', '$P$BtaOe8sEH2ov7jzliKvOzx1vrNyD560', 'osman-nayeem', 'osman.nayeem@gmail.com', 'S M M OSMAN NAYEEM', NULL, NULL, NULL, NULL, NULL),
(88, 'sthiitvczz', '$P$Boj/OqEN.sMKj3RSH6l0XF18JcmPF0/', 'sthiitvczz', 'sthiitvczz@mail.com', 'sthiitvczz', NULL, NULL, NULL, NULL, NULL),
(89, 'styomkrans', '$P$Bes6h40pXjgH64xXlfLN8pE.ElINLf/', 'styomkrans', 'styomkrans@mail.com', 'styomkrans', NULL, NULL, NULL, NULL, NULL),
(90, 'stevksyydb', '$P$BRJo546d31OnX5HZCtdj4NdyQR0K0T/', 'stevksyydb', 'stevksyydb@mail.com', 'stevksyydb', NULL, NULL, NULL, NULL, NULL),
(91, 'lyvantw9', '$P$BUNVGcj7LZqjtRenCZv93Mo60cAgEB/', 'lyvantw9', 'lyvantw9@gmail.com', 'lyvantw9', NULL, NULL, NULL, NULL, NULL),
(92, 'imamihab', '$P$B.nbL1mRLjpvkGKF94qiQ6exnJJd6u/', 'imamihab', 'imamihab@ihsanonline.org', 'imamihab', NULL, NULL, NULL, NULL, NULL),
(93, 'hasan34023', '$P$BPOEZWHZ..FQYdCXxw5iLxYmssvj.i.', 'hasan34023', 'hasan34023@gmail.com', 'hasan34023', NULL, NULL, NULL, NULL, NULL),
(94, 'stzainlqli', '$P$B1icZJr2cc96L/JqvoOnhVxu7.mEO4.', 'stzainlqli', 'stzainlqli@mail.com', 'stzainlqli', NULL, NULL, NULL, NULL, NULL),
(95, 'stvfytogld', '$P$BqGqzcHJUPC.U6j.zFK67QwGPfiRR.1', 'stvfytogld', 'stvfytogld@mail.com', 'stvfytogld', NULL, NULL, NULL, NULL, NULL),
(96, 'stjkxcrtzc', '$P$B7b.NamoLjsGaFPT7q.InudEQBESyM/', 'stjkxcrtzc', 'stjkxcrtzc@mail.com', 'stjkxcrtzc', NULL, NULL, NULL, NULL, NULL),
(97, 'stxlagbecg', '$P$BUUtQkGKBdClHclwcT0RhMhk1lxpkG/', 'stxlagbecg', 'stxlagbecg@mail.com', 'stxlagbecg', NULL, NULL, NULL, NULL, NULL),
(98, 'stmdqiycoh', '$P$BLYXbSqSQGaccnv444BB4DeE1hH0UQ0', 'stmdqiycoh', 'stmdqiycoh@mail.com', 'stmdqiycoh', NULL, NULL, NULL, NULL, NULL),
(99, 'prietrojanae3', '$P$BMiW8IISZ90G3FUWhwqOeWcOQ2.zyL.', 'prietrojanae3', 'prietrojanae3@gmail.com', 'prietrojanae3', NULL, NULL, NULL, NULL, NULL),
(100, 'engr.abdulkareem', '$P$B0.Y.ddgyi3J7aZw7quR7nO6pxogsS.', 'engr-abdulkareem', 'engr.abdulkareem@gmail.com', 'engr.abdulkareem', NULL, NULL, NULL, NULL, NULL),
(101, 'tonnymasaba4', '$P$BaWq9IvdNTM.KSYYYUa2XcFb5fnBT//', 'tonnymasaba4', 'tonnymasaba4@gmail.com', 'tonnymasaba4', NULL, NULL, NULL, NULL, NULL),
(102, 'oherachi.kamel', '$P$BhVP9E69x5I38jdqID/YCX3s4HNZos/', 'oherachi-kamel', 'oherachi.kamel@gmail.com', 'oherachi.kamel', NULL, NULL, NULL, NULL, NULL),
(103, 'naomisoler62', '$P$BvdanwEchPeAOpi9UXUoE1EdBjnQhq1', 'naomisoler62', 'NAOMISOLER62@gmail.com', 'naomisoler62', NULL, NULL, NULL, NULL, NULL),
(104, 'attyanmm', '$P$BoQ68GGnDMsD1iDvI1seiSJ0odSOfg/', 'attyanmm', 'attyanmm@gmail.com', 'attyanmm', NULL, NULL, NULL, NULL, NULL),
(105, 'jogranamehul301', '$P$Br.5RvUIjNXFYEbj81TXDeU.dAY22M1', 'jogranamehul301', 'jogranamehul301@gmail.com', 'jogranamehul301', NULL, NULL, NULL, NULL, NULL),
(106, 'raymondaseye', '$P$BG6l2NUVj4jHwgDdGRW7b5cBrOCVVP1', 'raymondaseye', 'raymondaseye@gmail.com', 'raymondk', NULL, NULL, NULL, NULL, NULL),
(107, 'hopeforeternityfoundation', '$P$BffIjo5LV1eqMrBthbtTarHbwvuU8e0', 'hopeforeternityfoundation', 'hopeforeternityfoundation@gmail.com', 'hopeforeternityfoundation', NULL, NULL, NULL, NULL, NULL),
(108, 'abircoti', '$P$BZpf7I7SIPBqyc1gdOSKA3JzTBdDN3.', 'abircoti', 'Abircoti@gmail.com', 'abircoti', NULL, NULL, NULL, NULL, NULL),
(109, 'shakur130', '$P$Bu49RVtySUBTx/L1h33uPJPBJqxdws/', 'shakur130', 'shakur130@yahoo.com', 'shakur130', NULL, NULL, NULL, NULL, NULL),
(110, 'kingibrahkyeyune', '$P$B6YClhHXh0qVvHD5qj2KHz3kzPVtvz.', 'kingibrahkyeyune', 'Kingibrahkyeyune@gmail.com', 'kingibrahkyeyune', NULL, NULL, NULL, NULL, NULL),
(111, 'bluesky_shaimaa_seo', '$P$Bwbh07h1IMA0jmBMfCEI8eAQyMWuq2.', 'bluesky_shaimaa_seo', 'shaimaa.bluesky@gmail.com', 'Shaimaa BlueSky', NULL, NULL, NULL, NULL, NULL),
(112, 'mgalal', '$P$BIikqRkXAhnTwM5jsj/z3LWgQi0Bzj/', 'mgalal', 'mgalal@cpapai.com', 'Mohamed Galal', NULL, NULL, NULL, NULL, NULL),
(113, 'mosayeh899', '$P$BzqVwTFEfic6hDFkyrBKUcSlojH1pB/', 'mosayeh899', 'malsayeh@bluskyint.com', 'Mohamed Elsayeh', NULL, NULL, NULL, NULL, NULL),
(114, 'atakhalil', '$P$B.VeTliFCRGt8/9mq9tLWyEPwxmFbE0', 'atakhalil', 'atakhalil7@gmail.com', 'atakhalil', NULL, NULL, NULL, NULL, NULL),
(115, 'omarmosaad', '$P$B/5Yg6vCtLA81qoMWicFNZgc9lPX07/', 'omarmosaad', 'omarmossad1995@gmail.com', 'Omar Mosaad', NULL, NULL, NULL, NULL, NULL),
(116, 'Zeghachou Younes', '$P$Bd13PZXndncRbWxOHbOzay5CTVwrZD.', 'zeghachou-younes', 'zeghachouy@gmail.com', 'Zeghachou Younes', NULL, NULL, NULL, NULL, NULL),
(117, 'Rshasabri', '$P$BF0pLR/QkKc9YGuxXnn4u.rnFIBs0a0', 'rshasabri', 'sabri.rsha@gmail.com', 'Rshasabri', NULL, NULL, NULL, NULL, NULL),
(118, 'momen.elbadri.bluesky', '$P$BF/08zQlqTNkqVbZRfq5K5dH2XLk4z/', 'momen-elbadri-bluesky', 'MomenElbadri@gmail.com', 'Momen Elbadri', NULL, NULL, NULL, NULL, NULL),
(121, 'abdelrahman ismael', '$P$BbTPVX7cztfJwIPX.0UI7sQcx3/8sz1', 'abdelrahman-ismael', 'a.ismael@bluskyint.com', 'abdelrahman ismael', NULL, NULL, NULL, NULL, NULL),
(122, 'samah.quran', '$P$Bh6fIJAbnBHfyGC10OnC7baHjCl.de.', 'samah-quran', 's.quran@africa-relief.org', 'Samah Quran', NULL, NULL, NULL, NULL, NULL),
(123, 'tareq', '$P$BQ/H9nrRl2XZld7J9IhLPK0n7r6NMy.', 'tareq', 'tareq3381@gmail.com', 'tareq', NULL, NULL, NULL, NULL, NULL),
(130, '', '$P$BB7cJroWTpB0wJYYMqn8fSb9d4irI4/', '', 'ahmed@gmail.com', 'ahmed', NULL, NULL, NULL, '2024-02-05 12:04:11', '2024-02-05 12:04:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_login_key` (`user_login`),
  ADD KEY `user_nicename` (`user_nicename`),
  ADD KEY `user_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
