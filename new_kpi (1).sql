-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2025 at 08:33 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `new_kpi`
--

-- --------------------------------------------------------

--
-- Table structure for table `danhgia_kpi`
--

CREATE TABLE `danhgia_kpi` (
  `ID_danhgia` int(11) NOT NULL,
  `ID_phancong` int(11) NOT NULL,
  `Ty_le_hoanthanh` decimal(5,2) DEFAULT NULL,
  `Ketqua_thuchien` decimal(10,0) DEFAULT NULL,
  `Trang_thai` enum('cho_duyet','dat','khong_dat') DEFAULT 'cho_duyet',
  `ID_nguoithamdinh` int(11) DEFAULT NULL,
  `Ngay_thamdinh` timestamp NULL DEFAULT NULL,
  `Nhan_xet` text DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `danhgia_kpi`
--

INSERT INTO `danhgia_kpi` (`ID_danhgia`, `ID_phancong`, `Ty_le_hoanthanh`, `Ketqua_thuchien`, `Trang_thai`, `ID_nguoithamdinh`, `Ngay_thamdinh`, `Nhan_xet`, `updated_at`) VALUES
(10, 204, 100.00, 5, 'dat', 6, '2025-10-18 23:24:16', 'ok roi nhe', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dulieu_kpi`
--

CREATE TABLE `dulieu_kpi` (
  `ID_dulieu` int(11) NOT NULL,
  `ID_phancong` int(11) NOT NULL,
  `ID_nguoigui` int(11) NOT NULL,
  `Ngay_gui` timestamp NOT NULL DEFAULT current_timestamp(),
  `Minh_chung` text NOT NULL,
  `File_path` varchar(255) DEFAULT NULL,
  `File_name` varchar(255) DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dulieu_kpi`
--

INSERT INTO `dulieu_kpi` (`ID_dulieu`, `ID_phancong`, `ID_nguoigui`, `Ngay_gui`, `Minh_chung`, `File_path`, `File_name`, `updated_at`) VALUES
(6, 204, 1, '2025-10-19 05:57:43', 'kkk', 'kpi-submissions/1/MnQhJrEsXz5XbmOD4gVf3TOAGWS9y4qJWWgh2v72.pdf', 'baibao.pdf', NULL),
(7, 204, 1, '2025-10-19 06:15:32', 'nop lan 2', 'kpi-submissions/1/19t2OyZaHVDB3HKrbNf1tqEL4uWYyEWIBYWHbSOy.pdf', 'certificate.pdf', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kpi`
--

CREATE TABLE `kpi` (
  `ID_kpi` int(11) NOT NULL,
  `ID_loai_kpi` int(11) DEFAULT NULL,
  `Ten_kpi` varchar(100) NOT NULL,
  `Chi_tieu` decimal(10,0) NOT NULL,
  `Donvi_tinh` varchar(50) DEFAULT NULL,
  `Do_uu_tien` enum('Rất gấp','Gấp','Trung Bình','Không','') DEFAULT NULL,
  `Ngay_tao` timestamp NOT NULL DEFAULT current_timestamp(),
  `Mo_ta` text DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kpi`
--

INSERT INTO `kpi` (`ID_kpi`, `ID_loai_kpi`, `Ten_kpi`, `Chi_tieu`, `Donvi_tinh`, `Do_uu_tien`, `Ngay_tao`, `Mo_ta`, `updated_at`) VALUES
(1, 1, 'Tăng lượt tương tác mạng xã hội', 120, 'lượt', 'Gấp', '2025-10-15 05:30:26', NULL, NULL),
(2, 2, 'Hoàn thành báo cáo tháng 10/2025', 50, 'trang', 'Trung Bình', '2025-10-15 05:30:26', NULL, NULL),
(3, 3, 'Tạo 15 bài viết marketing mới', 15, 'bài viết', 'Gấp', '2025-10-15 05:30:26', NULL, NULL),
(4, 4, 'Giảm tỷ lệ khách hàng rời bỏ', 5, '%', 'Rất gấp', '2025-10-15 05:30:26', NULL, NULL),
(5, 1, 'Đạt doanh số tuần 2 tháng 10', 10000000, 'VND', 'Trung Bình', '2025-10-15 05:30:26', NULL, NULL),
(10, 1, 'kpi 19/10', 100, 'cai', 'Gấp', '2025-10-19 04:26:54', 'lkk', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `loai_kpi`
--

CREATE TABLE `loai_kpi` (
  `ID_loai_kpi` int(11) NOT NULL,
  `Ten_loai_kpi` varchar(255) NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `loai_kpi`
--

INSERT INTO `loai_kpi` (`ID_loai_kpi`, `Ten_loai_kpi`, `updated_at`) VALUES
(1, 'Marketing', NULL),
(2, 'IT', NULL),
(3, 'HR', NULL),
(4, 'Finance', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nhatky`
--

CREATE TABLE `nhatky` (
  `ID_nhatky` int(11) NOT NULL,
  `ID_nguoithuchien` int(11) NOT NULL,
  `Doi_tuong` varchar(50) NOT NULL,
  `ID_doi_tuong` int(11) NOT NULL,
  `Hanh_dong` enum('them','sua','xoa','duyet','phan cong kpi') NOT NULL,
  `Ngay_thuchien` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhatky`
--

INSERT INTO `nhatky` (`ID_nhatky`, `ID_nguoithuchien`, `Doi_tuong`, `ID_doi_tuong`, `Hanh_dong`, `Ngay_thuchien`, `updated_at`) VALUES
(1, 3, 'user', 9, 'xoa', '2025-10-18 13:58:16', NULL),
(9, 6, 'user', 1, 'phan cong kpi', '2025-10-19 04:26:54', NULL),
(10, 6, 'danhgia_kpi', 10, 'duyet', '2025-10-19 06:11:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `phancong_kpi`
--

CREATE TABLE `phancong_kpi` (
  `ID_Phancong` int(11) NOT NULL,
  `ID_kpi` int(11) NOT NULL,
  `ID_nguoi_phan_cong` int(11) DEFAULT NULL,
  `ID_loai_kpi` int(11) DEFAULT NULL,
  `ID_user` int(11) DEFAULT NULL,
  `ID_phongban` int(11) DEFAULT NULL,
  `Ngay_batdau` date DEFAULT NULL,
  `Ngay_ketthuc` date DEFAULT NULL,
  `Trang_thai` enum('chua_thuc_hien','dang_thuc_hien','hoan_thanh','qua_han') DEFAULT 'chua_thuc_hien',
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phancong_kpi`
--

INSERT INTO `phancong_kpi` (`ID_Phancong`, `ID_kpi`, `ID_nguoi_phan_cong`, `ID_loai_kpi`, `ID_user`, `ID_phongban`, `Ngay_batdau`, `Ngay_ketthuc`, `Trang_thai`, `updated_at`) VALUES
(201, 1, 6, 1, 1, 1, '2025-10-15', '2025-10-25', 'dang_thuc_hien', NULL),
(202, 2, 0, 2, 1, 2, '2025-09-30', '2025-10-20', 'dang_thuc_hien', NULL),
(203, 3, 6, 3, 1, 3, '2025-10-15', '2025-11-04', 'dang_thuc_hien', NULL),
(204, 4, 6, 4, 1, 2, '2025-09-15', '2025-10-13', 'hoan_thanh', NULL),
(205, 5, 6, 1, 1, 3, '2025-10-09', '2025-10-14', 'dang_thuc_hien', NULL),
(210, 10, 6, 1, 1, 2, '2025-10-21', '2025-10-23', 'chua_thuc_hien', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `phongban`
--

CREATE TABLE `phongban` (
  `ID_phongban` int(11) NOT NULL,
  `Ten_phongban` varchar(100) NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phongban`
--

INSERT INTO `phongban` (`ID_phongban`, `Ten_phongban`, `updated_at`) VALUES
(1, 'Phòng IT', NULL),
(2, 'Maketing', NULL),
(3, 'Sale', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quyen`
--

CREATE TABLE `quyen` (
  `ID_quyen` int(11) NOT NULL,
  `Ten_quyen` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quyen`
--

INSERT INTO `quyen` (`ID_quyen`, `Ten_quyen`) VALUES
(1, 'Admin'),
(3, 'Nhân viên'),
(2, 'Quản lý');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `ID_task` int(11) NOT NULL,
  `Ten_task` varchar(255) NOT NULL,
  `Mo_ta` text DEFAULT NULL,
  `ID_user_duocgiao` int(11) NOT NULL,
  `Trang_thai` enum('chua_bat_dau','dang_thuc_hien','hoan_thanh') NOT NULL DEFAULT 'chua_bat_dau',
  `Ngay_giao` datetime NOT NULL DEFAULT current_timestamp(),
  `Ngay_het_han` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`ID_task`, `Ten_task`, `Mo_ta`, `ID_user_duocgiao`, `Trang_thai`, `Ngay_giao`, `Ngay_het_han`, `updated_at`) VALUES
(1, 'Lên kế hoạch tuần mới', NULL, 1, 'hoan_thanh', '2025-10-15 19:44:42', NULL, NULL),
(2, 'Nghiên cứu đối thủ cạnh tranh', NULL, 1, 'dang_thuc_hien', '2025-10-15 19:44:42', NULL, NULL),
(3, 'Viết nội dung blog cho sản phẩm A', NULL, 1, 'hoan_thanh', '2025-10-15 19:44:42', NULL, NULL),
(4, 'Thiết kế banner quảng cáo', NULL, 1, 'dang_thuc_hien', '2025-10-15 19:44:42', NULL, NULL),
(5, 'Phân tích traffic website tuần trước', NULL, 1, 'dang_thuc_hien', '2025-10-15 19:44:42', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `thongbao`
--

CREATE TABLE `thongbao` (
  `ID_thongbao` int(11) NOT NULL,
  `ID_nguoigui` int(11) NOT NULL,
  `ID_nguoinhan` int(11) NOT NULL,
  `Tieu_de` varchar(200) NOT NULL,
  `Noi_dung` text DEFAULT NULL,
  `Loai_thongbao` enum('phancong_kpi','phancong_task','review_kpi','submit_kpi') DEFAULT 'phancong_kpi',
  `Da_xem` tinyint(1) DEFAULT 0,
  `Ngay_gui` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID_user` int(11) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `MK` varchar(255) NOT NULL,
  `MK_hash` text NOT NULL,
  `Ho_ten` varchar(100) DEFAULT NULL,
  `ID_quyen` int(11) NOT NULL,
  `ID_phongban` int(11) DEFAULT NULL,
  `Trang_thai` enum('hoat_dong','khong_hoat_dong','bi_chan','') DEFAULT 'hoat_dong',
  `Ngay_tao` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID_user`, `Email`, `MK`, `MK_hash`, `Ho_ten`, `ID_quyen`, `ID_phongban`, `Trang_thai`, `Ngay_tao`, `updated_at`) VALUES
(1, 'user@gmail.com', '321', '$2y$10$0Mo0tPH0ZZPr29fILEbHLOnS1O9pRoDu9rP.tRLaV6tfsBljRizGy', 'USER', 3, 2, 'hoat_dong', '2025-10-08 23:25:09', NULL),
(3, 'admin@gmail.com', '321', '$2y$10$0Mo0tPH0ZZPr29fILEbHLOnS1O9pRoDu9rP.tRLaV6tfsBljRizGy', 'ADMIN', 1, 1, 'hoat_dong', '2025-10-08 23:25:09', NULL),
(6, 'manager@gmail.com', '123', '$2y$10$0Mo0tPH0ZZPr29fILEbHLOnS1O9pRoDu9rP.tRLaV6tfsBljRizGy', 'MANAGER', 2, 3, 'hoat_dong', '2025-10-16 00:57:39', NULL),
(10, '21004074@gmail.com', '123', '$2y$10$A5x116WFOtwMNuTq7it4zeH.psLPBhLmJbA4zh5pXFSkO7zJSrx/W', 'Nguyễn Thái Vinh', 1, 2, 'hoat_dong', '2025-10-16 05:50:40', '2025-10-18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `danhgia_kpi`
--
ALTER TABLE `danhgia_kpi`
  ADD PRIMARY KEY (`ID_danhgia`),
  ADD KEY `ID_phancong` (`ID_phancong`),
  ADD KEY `ID_nguoithamdinh` (`ID_nguoithamdinh`);

--
-- Indexes for table `dulieu_kpi`
--
ALTER TABLE `dulieu_kpi`
  ADD PRIMARY KEY (`ID_dulieu`),
  ADD KEY `ID_phancong` (`ID_phancong`),
  ADD KEY `ID_nguoigui` (`ID_nguoigui`);

--
-- Indexes for table `kpi`
--
ALTER TABLE `kpi`
  ADD PRIMARY KEY (`ID_kpi`);

--
-- Indexes for table `loai_kpi`
--
ALTER TABLE `loai_kpi`
  ADD PRIMARY KEY (`ID_loai_kpi`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nhatky`
--
ALTER TABLE `nhatky`
  ADD PRIMARY KEY (`ID_nhatky`),
  ADD KEY `ID_nguoithuchien` (`ID_nguoithuchien`);

--
-- Indexes for table `phancong_kpi`
--
ALTER TABLE `phancong_kpi`
  ADD PRIMARY KEY (`ID_Phancong`),
  ADD KEY `ID_kpi` (`ID_kpi`),
  ADD KEY `ID_user` (`ID_user`),
  ADD KEY `ID_phongban` (`ID_phongban`);

--
-- Indexes for table `phongban`
--
ALTER TABLE `phongban`
  ADD PRIMARY KEY (`ID_phongban`);

--
-- Indexes for table `quyen`
--
ALTER TABLE `quyen`
  ADD PRIMARY KEY (`ID_quyen`),
  ADD UNIQUE KEY `Ten_quyen` (`Ten_quyen`);

--
-- Indexes for table `thongbao`
--
ALTER TABLE `thongbao`
  ADD PRIMARY KEY (`ID_thongbao`),
  ADD KEY `ID_nguoinhan` (`ID_nguoinhan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID_user`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Email_2` (`Email`),
  ADD KEY `ID_quyen` (`ID_quyen`),
  ADD KEY `ID_phongban` (`ID_phongban`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `danhgia_kpi`
--
ALTER TABLE `danhgia_kpi`
  MODIFY `ID_danhgia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `dulieu_kpi`
--
ALTER TABLE `dulieu_kpi`
  MODIFY `ID_dulieu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kpi`
--
ALTER TABLE `kpi`
  MODIFY `ID_kpi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `loai_kpi`
--
ALTER TABLE `loai_kpi`
  MODIFY `ID_loai_kpi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nhatky`
--
ALTER TABLE `nhatky`
  MODIFY `ID_nhatky` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `phancong_kpi`
--
ALTER TABLE `phancong_kpi`
  MODIFY `ID_Phancong` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT for table `phongban`
--
ALTER TABLE `phongban`
  MODIFY `ID_phongban` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `quyen`
--
ALTER TABLE `quyen`
  MODIFY `ID_quyen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `thongbao`
--
ALTER TABLE `thongbao`
  MODIFY `ID_thongbao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `danhgia_kpi`
--
ALTER TABLE `danhgia_kpi`
  ADD CONSTRAINT `danhgia_kpi_ibfk_1` FOREIGN KEY (`ID_phancong`) REFERENCES `phancong_kpi` (`ID_Phancong`) ON DELETE CASCADE,
  ADD CONSTRAINT `danhgia_kpi_ibfk_2` FOREIGN KEY (`ID_nguoithamdinh`) REFERENCES `users` (`ID_user`) ON DELETE SET NULL;

--
-- Constraints for table `dulieu_kpi`
--
ALTER TABLE `dulieu_kpi`
  ADD CONSTRAINT `dulieu_kpi_ibfk_1` FOREIGN KEY (`ID_phancong`) REFERENCES `phancong_kpi` (`ID_Phancong`) ON DELETE CASCADE,
  ADD CONSTRAINT `dulieu_kpi_ibfk_2` FOREIGN KEY (`ID_nguoigui`) REFERENCES `users` (`ID_user`) ON DELETE CASCADE;

--
-- Constraints for table `nhatky`
--
ALTER TABLE `nhatky`
  ADD CONSTRAINT `nhatky_ibfk_1` FOREIGN KEY (`ID_nguoithuchien`) REFERENCES `users` (`ID_user`) ON DELETE CASCADE;

--
-- Constraints for table `phancong_kpi`
--
ALTER TABLE `phancong_kpi`
  ADD CONSTRAINT `phancong_kpi_ibfk_1` FOREIGN KEY (`ID_kpi`) REFERENCES `kpi` (`ID_kpi`) ON DELETE CASCADE,
  ADD CONSTRAINT `phancong_kpi_ibfk_2` FOREIGN KEY (`ID_user`) REFERENCES `users` (`ID_user`) ON DELETE SET NULL,
  ADD CONSTRAINT `phancong_kpi_ibfk_3` FOREIGN KEY (`ID_phongban`) REFERENCES `phongban` (`ID_phongban`) ON DELETE SET NULL;

--
-- Constraints for table `thongbao`
--
ALTER TABLE `thongbao`
  ADD CONSTRAINT `thongbao_ibfk_1` FOREIGN KEY (`ID_nguoinhan`) REFERENCES `users` (`ID_user`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`ID_quyen`) REFERENCES `quyen` (`ID_quyen`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`ID_phongban`) REFERENCES `phongban` (`ID_phongban`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
