-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 20, 2025 lúc 02:53 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `qldemo1`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_admin`
--

CREATE TABLE `bang_admin` (
  `MaAdmin` varchar(20) NOT NULL,
  `MaTK` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bang_admin`
--

INSERT INTO `bang_admin` (`MaAdmin`, `MaTK`) VALUES
('0000', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_ctcthssv`
--

CREATE TABLE `bang_ctcthssv` (
  `MaCTCT` varchar(20) NOT NULL,
  `TenPhong` varchar(50) NOT NULL,
  `NguoiQL` varchar(50) NOT NULL,
  `MaTK` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bang_ctcthssv`
--

INSERT INTO `bang_ctcthssv` (`MaCTCT`, `TenPhong`, `NguoiQL`, `MaTK`) VALUES
('CT02', 'A002', 'Võ Quỳnh Như', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_dangkysukien`
--

CREATE TABLE `bang_dangkysukien` (
  `MaSK` bigint(20) NOT NULL,
  `MaSV` varchar(20) NOT NULL,
  `DangKyLuc` datetime NOT NULL DEFAULT current_timestamp(),
  `TrangThaiDangKy` enum('Registered','Cancelled') NOT NULL DEFAULT 'Registered',
  `DaDiemDanh` tinyint(1) NOT NULL DEFAULT 0,
  `DiemDanhLuc` datetime DEFAULT NULL,
  `GhiChu` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bang_dangkysukien`
--

INSERT INTO `bang_dangkysukien` (`MaSK`, `MaSV`, `DangKyLuc`, `TrangThaiDangKy`, `DaDiemDanh`, `DiemDanhLuc`, `GhiChu`) VALUES
(1, '49.01.103.001', '2025-12-18 06:18:22', 'Registered', 1, '2025-12-19 12:39:56', NULL),
(1, '49.01.103.002', '2025-12-18 18:48:43', 'Registered', 1, '2025-12-18 22:47:58', NULL),
(1, '49.01.103.006', '2025-12-18 20:36:09', 'Registered', 0, NULL, NULL),
(1, '49.01.103.020', '2025-12-18 06:53:51', 'Registered', 1, '2025-12-18 22:51:00', NULL),
(3, '49.01.103.001', '2025-12-18 07:04:38', 'Registered', 1, '2025-12-19 00:44:53', NULL),
(3, '49.01.103.002', '2025-12-18 18:21:15', 'Registered', 1, '2025-12-18 22:59:48', NULL),
(3, '49.01.103.020', '2025-12-18 06:53:50', 'Registered', 1, '2025-12-18 14:33:07', NULL),
(7, '49.01.103.001', '2025-12-18 23:06:15', 'Registered', 0, NULL, NULL),
(7, '49.01.103.020', '2025-12-19 00:41:12', 'Registered', 0, NULL, NULL),
(7, '49.01.103.022', '2025-12-19 12:40:18', 'Registered', 0, NULL, NULL),
(8, '49.01.103.001', '2025-12-19 10:56:53', 'Registered', 0, NULL, NULL),
(8, '49.01.103.020', '2025-12-19 00:41:11', 'Registered', 1, '2025-12-19 00:45:01', NULL),
(8, '49.01.103.022', '2025-12-19 12:40:21', 'Registered', 0, NULL, NULL),
(9, '49.01.103.001', '2025-12-19 00:44:29', 'Registered', 0, NULL, NULL),
(9, '49.01.103.020', '2025-12-19 00:41:10', 'Registered', 0, NULL, NULL),
(9, '49.01.103.022', '2025-12-19 12:40:19', 'Registered', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_danhhieu`
--

CREATE TABLE `bang_danhhieu` (
  `MaDH` int(11) NOT NULL,
  `TenDH` varchar(100) NOT NULL,
  `DieuKienGPA` decimal(3,2) NOT NULL,
  `DieuKienDRL` smallint(6) NOT NULL,
  `DieuKienNTN` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bang_danhhieu`
--

INSERT INTO `bang_danhhieu` (`MaDH`, `TenDH`, `DieuKienGPA`, `DieuKienDRL`, `DieuKienNTN`) VALUES
(1, 'Đoàn viên ưu tú', 3.60, 80, 20),
(2, 'Sinh Viên 5 Tốt', 3.60, 90, 15),
(3, 'Tình Nguyện Xuất Sắc', 3.20, 90, 30);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_diemhoctap`
--

CREATE TABLE `bang_diemhoctap` (
  `MaSV` varchar(20) NOT NULL,
  `HocKy` tinyint(4) NOT NULL,
  `NamHoc` varchar(9) NOT NULL,
  `DiemHe4` decimal(3,2) NOT NULL,
  `XepLoai` varchar(50) DEFAULT NULL,
  `MaPKT` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bang_diemhoctap`
--

INSERT INTO `bang_diemhoctap` (`MaSV`, `HocKy`, `NamHoc`, `DiemHe4`, `XepLoai`, `MaPKT`) VALUES
('49.01.103.001', 1, '2024-2025', 4.00, 'Xuất sắc', NULL),
('49.01.103.002', 1, '2024-2025', 3.98, 'Xuất sắc', NULL),
('49.01.103.003', 1, '2024-2025', 2.09, 'Trung bình', NULL),
('49.01.103.004', 1, '2024-2025', 2.59, 'Yếu', NULL),
('49.01.103.005', 1, '2024-2025', 2.92, 'Khá', NULL),
('49.01.103.006', 1, '2024-2025', 3.82, 'Xuất sắc', NULL),
('49.01.103.007', 1, '2024-2025', 3.94, 'Xuất sắc', NULL),
('49.01.103.008', 1, '2024-2025', 3.22, 'Giỏi', NULL),
('49.01.103.009', 1, '2024-2025', 3.48, 'Giỏi', NULL),
('49.01.103.010', 1, '2024-2025', 3.40, 'Giỏi', NULL),
('49.01.103.011', 1, '2024-2025', 2.29, 'Trung bình', NULL),
('49.01.103.012', 1, '2024-2025', 3.86, 'Xuất sắc', NULL),
('49.01.103.013', 1, '2024-2025', 2.21, 'Trung bình', NULL),
('49.01.103.014', 1, '2024-2025', 2.05, 'Trung bình', NULL),
('49.01.103.015', 1, '2024-2025', 2.55, 'Khá', NULL),
('49.01.103.016', 1, '2024-2025', 2.59, 'Khá', NULL),
('49.01.103.017', 1, '2024-2025', 3.85, 'Xuất sắc', NULL),
('49.01.103.018', 1, '2024-2025', 2.01, 'Trung bình', NULL),
('49.01.103.019', 1, '2024-2025', 2.26, 'Trung bình', NULL),
('49.01.103.020', 1, '2024-2025', 3.06, 'Khá', NULL),
('49.01.103.021', 1, '2024-2025', 2.58, 'Khá', NULL),
('49.01.103.022', 1, '2024-2025', 2.85, 'Khá', NULL),
('49.01.103.023', 1, '2024-2025', 3.94, 'Xuất sắc', NULL),
('49.01.103.024', 1, '2024-2025', 2.06, 'Trung bình', NULL),
('49.01.103.025', 1, '2024-2025', 3.60, 'Xuất sắc', NULL),
('49.01.103.026', 1, '2024-2025', 2.07, 'Trung bình', NULL),
('49.01.103.027', 1, '2024-2025', 2.10, 'Trung bình', NULL),
('49.01.103.028', 1, '2024-2025', 3.80, 'Xuất sắc', NULL),
('49.01.103.029', 1, '2024-2025', 3.45, 'Giỏi', NULL),
('49.01.103.030', 1, '2024-2025', 3.94, 'Xuất sắc', NULL),
('49.01.103.031', 1, '2024-2025', 2.28, 'Trung bình', NULL),
('49.01.103.032', 1, '2024-2025', 3.91, 'Xuất sắc', NULL),
('49.01.103.033', 1, '2024-2025', 2.49, 'Trung bình', NULL),
('49.01.103.034', 1, '2024-2025', 3.93, 'Xuất sắc', NULL),
('49.01.103.035', 1, '2024-2025', 2.04, 'Trung bình', NULL),
('49.01.103.036', 1, '2024-2025', 3.84, 'Xuất sắc', NULL),
('49.01.103.037', 1, '2024-2025', 2.43, 'Trung bình', NULL),
('49.01.103.038', 1, '2024-2025', 3.38, 'Giỏi', NULL),
('49.01.103.039', 1, '2024-2025', 2.05, 'Trung bình', NULL),
('49.01.103.040', 1, '2024-2025', 2.72, 'Khá', NULL),
('49.01.103.041', 1, '2024-2025', 3.46, 'Giỏi', NULL),
('49.01.103.042', 1, '2024-2025', 2.07, 'Trung bình', NULL),
('49.01.103.043', 1, '2024-2025', 3.31, 'Giỏi', NULL),
('49.01.103.044', 1, '2024-2025', 3.85, 'Xuất sắc', NULL),
('49.01.103.045', 1, '2024-2025', 2.38, 'Trung bình', NULL),
('49.01.103.046', 1, '2024-2025', 2.58, 'Khá', NULL),
('49.01.103.047', 1, '2024-2025', 2.34, 'Trung bình', NULL),
('49.01.103.048', 1, '2024-2025', 2.97, 'Khá', NULL),
('49.01.103.049', 1, '2024-2025', 3.24, 'Giỏi', NULL),
('49.01.103.050', 1, '2024-2025', 3.83, 'Xuất sắc', NULL),
('49.01.103.052', 1, '2024-2025', 3.40, 'Giỏi', NULL),
('49.01.103.091', 1, '2024-2025', 3.50, 'Giỏi', NULL),
('49.01.103.101', 1, '2024-2025', 4.00, 'Xuất sắc', NULL),
('49.01.103.102', 1, '2024-2025', 3.90, 'Xuất sắc', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_diemrenluyen`
--

CREATE TABLE `bang_diemrenluyen` (
  `MaSV` varchar(20) NOT NULL,
  `HocKy` tinyint(4) NOT NULL,
  `NamHoc` varchar(9) NOT NULL,
  `DiemRL` smallint(6) NOT NULL,
  `XepLoai` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bang_diemrenluyen`
--

INSERT INTO `bang_diemrenluyen` (`MaSV`, `HocKy`, `NamHoc`, `DiemRL`, `XepLoai`) VALUES
('49.01.103.001', 1, '2024-2025', 98, 'Xuất sắc'),
('49.01.103.002', 1, '2024-2025', 98, 'Xuất sắc'),
('49.01.103.003', 1, '2024-2025', 80, 'Tốt'),
('49.01.103.004', 1, '2024-2025', 98, 'Xuất sắc'),
('49.01.103.005', 1, '2024-2025', 80, 'Tốt'),
('49.01.103.006', 1, '2024-2025', 94, 'Xuất sắc'),
('49.01.103.007', 1, '2024-2025', 95, 'Xuất sắc'),
('49.01.103.008', 1, '2024-2025', 100, 'Xuất sắc'),
('49.01.103.009', 1, '2024-2025', 93, 'Xuất sắc'),
('49.01.103.010', 1, '2024-2025', 88, 'Tốt'),
('49.01.103.011', 1, '2024-2025', 76, 'Khá'),
('49.01.103.012', 1, '2024-2025', 82, 'Tốt'),
('49.01.103.013', 1, '2024-2025', 88, 'Tốt'),
('49.01.103.014', 1, '2024-2025', 79, 'Khá'),
('49.01.103.015', 1, '2024-2025', 64, 'Trung bình'),
('49.01.103.016', 1, '2024-2025', 99, 'Xuất sắc'),
('49.01.103.017', 1, '2024-2025', 71, 'Khá'),
('49.01.103.018', 1, '2024-2025', 75, 'Khá'),
('49.01.103.019', 1, '2024-2025', 87, 'Tốt'),
('49.01.103.020', 1, '2024-2025', 71, 'Khá'),
('49.01.103.021', 1, '2024-2025', 65, 'Trung bình'),
('49.01.103.022', 1, '2024-2025', 72, 'Khá'),
('49.01.103.023', 1, '2024-2025', 75, 'Khá'),
('49.01.103.024', 1, '2024-2025', 94, 'Xuất sắc'),
('49.01.103.025', 1, '2024-2025', 81, 'Tốt'),
('49.01.103.026', 1, '2024-2025', 64, 'Trung bình'),
('49.01.103.027', 1, '2024-2025', 90, 'Xuất sắc'),
('49.01.103.028', 1, '2024-2025', 94, 'Xuất sắc'),
('49.01.103.029', 1, '2024-2025', 71, 'Khá'),
('49.01.103.030', 1, '2024-2025', 94, 'Xuất sắc'),
('49.01.103.031', 1, '2024-2025', 69, 'Trung bình'),
('49.01.103.032', 1, '2024-2025', 63, 'Trung bình'),
('49.01.103.033', 1, '2024-2025', 96, 'Xuất sắc'),
('49.01.103.034', 1, '2024-2025', 61, 'Trung bình'),
('49.01.103.035', 1, '2024-2025', 63, 'Trung bình'),
('49.01.103.036', 1, '2024-2025', 89, 'Tốt'),
('49.01.103.037', 1, '2024-2025', 71, 'Khá'),
('49.01.103.038', 1, '2024-2025', 92, 'Xuất sắc'),
('49.01.103.039', 1, '2024-2025', 80, 'Tốt'),
('49.01.103.040', 1, '2024-2025', 97, 'Xuất sắc'),
('49.01.103.041', 1, '2024-2025', 72, 'Khá'),
('49.01.103.042', 1, '2024-2025', 85, 'Tốt'),
('49.01.103.043', 1, '2024-2025', 86, 'Tốt'),
('49.01.103.044', 1, '2024-2025', 99, 'Xuất sắc'),
('49.01.103.045', 1, '2024-2025', 79, 'Khá'),
('49.01.103.046', 1, '2024-2025', 91, 'Xuất sắc'),
('49.01.103.047', 1, '2024-2025', 89, 'Tốt'),
('49.01.103.048', 1, '2024-2025', 69, 'Trung bình'),
('49.01.103.049', 1, '2024-2025', 60, 'Trung bình'),
('49.01.103.101', 1, '2024-2025', 100, 'Xuất Sắc'),
('49.01.103.102', 1, '2024-2025', 93, 'Xuất sắc');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_doantruong`
--

CREATE TABLE `bang_doantruong` (
  `MaDT` varchar(20) NOT NULL,
  `TenDT` varchar(50) NOT NULL,
  `NguoiQL` varchar(50) NOT NULL,
  `MaTK` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bang_doantruong`
--

INSERT INTO `bang_doantruong` (`MaDT`, `TenDT`, `NguoiQL`, `MaTK`) VALUES
('1012', 'C505', 'Nguyễn Xuân Phát 2.0', 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_khaothi`
--

CREATE TABLE `bang_khaothi` (
  `MaPKT` varchar(20) NOT NULL,
  `TenPhong` varchar(50) NOT NULL,
  `NguoiQL` varchar(50) NOT NULL,
  `MaTK` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bang_khaothi`
--

INSERT INTO `bang_khaothi` (`MaPKT`, `TenPhong`, `NguoiQL`, `MaTK`) VALUES
('KT01', 'A001', 'Dương Thị Thu Diểm', 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_ngaytinhnguyen`
--

CREATE TABLE `bang_ngaytinhnguyen` (
  `MaNTN` int(11) NOT NULL,
  `MaSV` varchar(20) NOT NULL,
  `NgayThamGia` date NOT NULL,
  `TenHoatDong` varchar(200) NOT NULL,
  `SoNgayTN` int(11) NOT NULL,
  `TrangThaiDuyet` enum('ChuaDuyet','DaDuyet','TuChoi') DEFAULT 'ChuaDuyet'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bang_ngaytinhnguyen`
--

INSERT INTO `bang_ngaytinhnguyen` (`MaNTN`, `MaSV`, `NgayThamGia`, `TenHoatDong`, `SoNgayTN`, `TrangThaiDuyet`) VALUES
(1, '49.01.103.001', '2025-11-02', 'Tham gia Tiếp sức mùa thi', 29, 'DaDuyet'),
(4, '49.01.103.004', '2024-12-19', 'Tham gia Tiếp sức mùa thi', 4, 'DaDuyet'),
(5, '49.01.103.005', '2025-11-02', 'Tuyên truyền an toàn giao thông', 5, 'DaDuyet'),
(6, '49.01.103.006', '2024-12-30', 'Giúp đỡ học sinh nghèo', 16, 'DaDuyet'),
(7, '49.01.103.007', '2025-11-13', 'Tham gia Tiếp sức mùa thi', 19, 'DaDuyet'),
(8, '49.01.103.008', '2024-12-21', 'Tham gia Tiếp sức mùa thi', 1, 'DaDuyet'),
(9, '49.01.103.009', '2025-11-02', 'Hỗ trợ sinh viên nội trú', 4, 'DaDuyet'),
(10, '49.01.103.010', '2025-11-02', 'Tuyên truyền an toàn giao thông', 19, 'DaDuyet'),
(11, '49.01.103.011', '2024-12-02', 'Tuyên truyền an toàn giao thông', 1, 'TuChoi'),
(12, '49.01.103.012', '2024-12-29', 'Hỗ trợ sinh viên nội trú', 2, 'ChuaDuyet'),
(13, '49.01.103.013', '2024-12-26', 'Hiến máu nhân đạo', 2, 'TuChoi'),
(14, '49.01.103.014', '2024-12-09', 'Tuyên truyền an toàn giao thông', 2, 'DaDuyet'),
(15, '49.01.103.015', '2024-12-23', 'Xuân tình nguyện', 4, 'ChuaDuyet'),
(16, '49.01.103.016', '2024-12-23', 'Hiến máu nhân đạo', 1, 'DaDuyet'),
(17, '49.01.103.017', '2024-12-14', 'Tham gia Tiếp sức mùa thi', 3, 'TuChoi'),
(18, '49.01.103.018', '2024-12-14', 'Hỗ trợ sinh viên nội trú', 5, 'TuChoi'),
(19, '49.01.103.019', '2024-12-05', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(20, '49.01.103.020', '2024-12-18', 'Xuân tình nguyện', 1, 'ChuaDuyet'),
(21, '49.01.103.021', '2024-12-13', 'Dọn vệ sinh khuôn viên trường', 5, 'DaDuyet'),
(22, '49.01.103.022', '2024-12-14', 'Xuân tình nguyện', 5, 'DaDuyet'),
(23, '49.01.103.023', '2024-12-25', 'Hỗ trợ sinh viên nội trú', 3, 'TuChoi'),
(24, '49.01.103.024', '2024-12-08', 'Dọn vệ sinh khuôn viên trường', 3, 'ChuaDuyet'),
(25, '49.01.103.025', '2024-12-23', 'Mùa hè xanh', 3, 'ChuaDuyet'),
(26, '49.01.103.026', '2024-12-10', 'Xuân tình nguyện', 3, 'TuChoi'),
(27, '49.01.103.027', '2024-12-05', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(28, '49.01.103.028', '2024-12-19', 'Mùa hè xanh', 5, 'DaDuyet'),
(29, '49.01.103.029', '2024-12-28', 'Xuân tình nguyện', 1, 'DaDuyet'),
(30, '49.01.103.030', '2024-12-21', 'Giúp đỡ học sinh nghèo', 1, 'ChuaDuyet'),
(31, '49.01.103.031', '2024-12-26', 'Ngày chủ nhật xanh', 5, 'DaDuyet'),
(32, '49.01.103.032', '2024-12-19', 'Hiến máu nhân đạo', 5, 'TuChoi'),
(33, '49.01.103.033', '2024-12-14', 'Hiến máu nhân đạo', 3, 'ChuaDuyet'),
(34, '49.01.103.034', '2024-12-14', 'Xuân tình nguyện', 4, 'DaDuyet'),
(35, '49.01.103.035', '2024-12-18', 'Giúp đỡ học sinh nghèo', 4, 'TuChoi'),
(36, '49.01.103.036', '2024-12-16', 'Tham gia Tiếp sức mùa thi', 3, 'DaDuyet'),
(37, '49.01.103.037', '2024-12-03', 'Hiến máu nhân đạo', 4, 'TuChoi'),
(38, '49.01.103.038', '2024-12-10', 'Ngày chủ nhật xanh', 2, 'TuChoi'),
(39, '49.01.103.039', '2024-12-16', 'Hiến máu nhân đạo', 5, 'TuChoi'),
(40, '49.01.103.040', '2024-12-19', 'Ngày chủ nhật xanh', 3, 'TuChoi'),
(41, '49.01.103.041', '2024-12-30', 'Xuân tình nguyện', 4, 'TuChoi'),
(42, '49.01.103.042', '2024-12-27', 'Ngày chủ nhật xanh', 3, 'DaDuyet'),
(43, '49.01.103.043', '2024-12-23', 'Giúp đỡ học sinh nghèo', 2, 'DaDuyet'),
(44, '49.01.103.044', '2024-12-11', 'Xuân tình nguyện', 4, 'DaDuyet'),
(45, '49.01.103.045', '2024-12-10', 'Hiến máu nhân đạo', 1, 'TuChoi'),
(46, '49.01.103.046', '2024-12-29', 'Tuyên truyền an toàn giao thông', 2, 'ChuaDuyet'),
(47, '49.01.103.047', '2024-12-03', 'Mùa hè xanh', 5, 'ChuaDuyet'),
(48, '49.01.103.048', '2024-12-14', 'Dọn vệ sinh khuôn viên trường', 4, 'TuChoi'),
(49, '49.01.103.049', '2024-12-16', 'Hiến máu nhân đạo', 4, 'DaDuyet'),
(51, '49.01.103.001', '2025-11-02', 'Up', 2, 'DaDuyet'),
(54, '49.01.103.003', '2025-11-02', 'Giúp đỡ học sinh nghèo', 5, 'DaDuyet'),
(60, '49.01.103.009', '2024-12-20', 'Hỗ trợ sinh viên nội trú', 4, 'ChuaDuyet'),
(61, '49.01.103.010', '2025-11-02', 'Tuyên truyền an toàn giao thông', 6, 'DaDuyet'),
(62, '49.01.103.011', '2024-12-02', 'Tuyên truyền an toàn giao thông', 1, 'TuChoi'),
(63, '49.01.103.012', '2024-12-29', 'Hỗ trợ sinh viên nội trú', 2, 'ChuaDuyet'),
(64, '49.01.103.013', '2024-12-26', 'Hiến máu nhân đạo', 2, 'TuChoi'),
(65, '49.01.103.014', '2024-12-09', 'Tuyên truyền an toàn giao thông', 2, 'DaDuyet'),
(66, '49.01.103.015', '2024-12-23', 'Xuân tình nguyện', 4, 'ChuaDuyet'),
(67, '49.01.103.016', '2024-12-23', 'Hiến máu nhân đạo', 1, 'DaDuyet'),
(68, '49.01.103.017', '2024-12-14', 'Tham gia Tiếp sức mùa thi', 3, 'TuChoi'),
(69, '49.01.103.018', '2024-12-14', 'Hỗ trợ sinh viên nội trú', 5, 'TuChoi'),
(70, '49.01.103.019', '2024-12-05', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(71, '49.01.103.020', '2024-12-18', 'Xuân tình nguyện', 1, 'ChuaDuyet'),
(72, '49.01.103.021', '2024-12-13', 'Dọn vệ sinh khuôn viên trường', 5, 'DaDuyet'),
(73, '49.01.103.022', '2024-12-14', 'Xuân tình nguyện', 5, 'DaDuyet'),
(74, '49.01.103.023', '2024-12-25', 'Hỗ trợ sinh viên nội trú', 3, 'TuChoi'),
(75, '49.01.103.024', '2024-12-08', 'Dọn vệ sinh khuôn viên trường', 3, 'ChuaDuyet'),
(76, '49.01.103.025', '2024-12-23', 'Mùa hè xanh', 3, 'ChuaDuyet'),
(77, '49.01.103.026', '2024-12-10', 'Xuân tình nguyện', 3, 'TuChoi'),
(78, '49.01.103.027', '2024-12-05', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(79, '49.01.103.028', '2024-12-19', 'Mùa hè xanh', 5, 'DaDuyet'),
(80, '49.01.103.029', '2024-12-28', 'Xuân tình nguyện', 1, 'DaDuyet'),
(81, '49.01.103.030', '2024-12-21', 'Giúp đỡ học sinh nghèo', 1, 'ChuaDuyet'),
(82, '49.01.103.031', '2024-12-26', 'Ngày chủ nhật xanh', 5, 'DaDuyet'),
(83, '49.01.103.032', '2024-12-19', 'Hiến máu nhân đạo', 5, 'TuChoi'),
(84, '49.01.103.033', '2024-12-14', 'Hiến máu nhân đạo', 3, 'ChuaDuyet'),
(85, '49.01.103.034', '2024-12-14', 'Xuân tình nguyện', 4, 'DaDuyet'),
(86, '49.01.103.035', '2024-12-18', 'Giúp đỡ học sinh nghèo', 4, 'TuChoi'),
(87, '49.01.103.036', '2024-12-16', 'Tham gia Tiếp sức mùa thi', 3, 'DaDuyet'),
(88, '49.01.103.037', '2024-12-03', 'Hiến máu nhân đạo', 4, 'TuChoi'),
(89, '49.01.103.038', '2024-12-10', 'Ngày chủ nhật xanh', 2, 'TuChoi'),
(90, '49.01.103.039', '2024-12-16', 'Hiến máu nhân đạo', 5, 'TuChoi'),
(91, '49.01.103.040', '2024-12-19', 'Ngày chủ nhật xanh', 3, 'TuChoi'),
(92, '49.01.103.041', '2024-12-30', 'Xuân tình nguyện', 4, 'TuChoi'),
(93, '49.01.103.042', '2024-12-27', 'Ngày chủ nhật xanh', 3, 'DaDuyet'),
(94, '49.01.103.043', '2024-12-23', 'Giúp đỡ học sinh nghèo', 2, 'DaDuyet'),
(95, '49.01.103.044', '2024-12-11', 'Xuân tình nguyện', 4, 'DaDuyet'),
(96, '49.01.103.045', '2024-12-10', 'Hiến máu nhân đạo', 1, 'TuChoi'),
(97, '49.01.103.046', '2024-12-29', 'Tuyên truyền an toàn giao thông', 2, 'ChuaDuyet'),
(98, '49.01.103.047', '2024-12-03', 'Mùa hè xanh', 5, 'ChuaDuyet'),
(99, '49.01.103.048', '2024-12-14', 'Dọn vệ sinh khuôn viên trường', 4, 'ChuaDuyet'),
(100, '49.01.103.049', '2024-12-16', 'Hiến máu nhân đạo', 4, 'DaDuyet'),
(101, '49.01.103.050', '2024-12-04', 'Trồng cây gây rừng', 2, 'ChuaDuyet'),
(110, '49.01.103.009', '2024-12-20', 'Hỗ trợ sinh viên nội trú', 4, 'ChuaDuyet'),
(111, '49.01.103.010', '2024-12-25', 'Tuyên truyền an toàn giao thông', 1, 'TuChoi'),
(112, '49.01.103.011', '2024-12-02', 'Tuyên truyền an toàn giao thông', 1, 'TuChoi'),
(113, '49.01.103.012', '2024-12-29', 'Hỗ trợ sinh viên nội trú', 2, 'ChuaDuyet'),
(114, '49.01.103.013', '2024-12-26', 'Hiến máu nhân đạo', 2, 'TuChoi'),
(115, '49.01.103.014', '2024-12-09', 'Tuyên truyền an toàn giao thông', 2, 'DaDuyet'),
(116, '49.01.103.015', '2024-12-23', 'Xuân tình nguyện', 4, 'ChuaDuyet'),
(117, '49.01.103.016', '2024-12-23', 'Hiến máu nhân đạo', 1, 'DaDuyet'),
(118, '49.01.103.017', '2024-12-14', 'Tham gia Tiếp sức mùa thi', 3, 'TuChoi'),
(119, '49.01.103.018', '2024-12-14', 'Hỗ trợ sinh viên nội trú', 5, 'TuChoi'),
(120, '49.01.103.019', '2024-12-05', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(121, '49.01.103.020', '2024-12-18', 'Xuân tình nguyện', 1, 'ChuaDuyet'),
(122, '49.01.103.021', '2024-12-13', 'Dọn vệ sinh khuôn viên trường', 5, 'DaDuyet'),
(123, '49.01.103.022', '2024-12-14', 'Xuân tình nguyện', 5, 'DaDuyet'),
(124, '49.01.103.023', '2024-12-25', 'Hỗ trợ sinh viên nội trú', 3, 'TuChoi'),
(125, '49.01.103.024', '2024-12-08', 'Dọn vệ sinh khuôn viên trường', 3, 'ChuaDuyet'),
(126, '49.01.103.025', '2024-12-23', 'Mùa hè xanh', 3, 'ChuaDuyet'),
(127, '49.01.103.026', '2024-12-10', 'Xuân tình nguyện', 3, 'TuChoi'),
(128, '49.01.103.027', '2024-12-05', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(129, '49.01.103.028', '2024-12-19', 'Mùa hè xanh', 5, 'DaDuyet'),
(130, '49.01.103.029', '2024-12-28', 'Xuân tình nguyện', 1, 'DaDuyet'),
(131, '49.01.103.030', '2024-12-21', 'Giúp đỡ học sinh nghèo', 1, 'ChuaDuyet'),
(132, '49.01.103.031', '2024-12-26', 'Ngày chủ nhật xanh', 5, 'DaDuyet'),
(133, '49.01.103.032', '2024-12-19', 'Hiến máu nhân đạo', 5, 'TuChoi'),
(134, '49.01.103.033', '2024-12-14', 'Hiến máu nhân đạo', 3, 'ChuaDuyet'),
(135, '49.01.103.034', '2024-12-14', 'Xuân tình nguyện', 4, 'DaDuyet'),
(136, '49.01.103.035', '2024-12-18', 'Giúp đỡ học sinh nghèo', 4, 'TuChoi'),
(137, '49.01.103.036', '2024-12-16', 'Tham gia Tiếp sức mùa thi', 3, 'DaDuyet'),
(138, '49.01.103.037', '2024-12-03', 'Hiến máu nhân đạo', 4, 'TuChoi'),
(139, '49.01.103.038', '2024-12-10', 'Ngày chủ nhật xanh', 2, 'TuChoi'),
(140, '49.01.103.039', '2024-12-16', 'Hiến máu nhân đạo', 5, 'TuChoi'),
(141, '49.01.103.040', '2024-12-19', 'Ngày chủ nhật xanh', 3, 'TuChoi'),
(142, '49.01.103.041', '2024-12-30', 'Xuân tình nguyện', 4, 'TuChoi'),
(143, '49.01.103.042', '2024-12-27', 'Ngày chủ nhật xanh', 3, 'DaDuyet'),
(144, '49.01.103.043', '2024-12-23', 'Giúp đỡ học sinh nghèo', 2, 'DaDuyet'),
(145, '49.01.103.044', '2024-12-11', 'Xuân tình nguyện', 4, 'DaDuyet'),
(146, '49.01.103.045', '2024-12-10', 'Hiến máu nhân đạo', 1, 'TuChoi'),
(147, '49.01.103.046', '2024-12-29', 'Tuyên truyền an toàn giao thông', 2, 'ChuaDuyet'),
(148, '49.01.103.047', '2024-12-03', 'Mùa hè xanh', 5, 'ChuaDuyet'),
(149, '49.01.103.048', '2024-12-14', 'Dọn vệ sinh khuôn viên trường', 4, 'ChuaDuyet'),
(150, '49.01.103.049', '2024-12-16', 'Hiến máu nhân đạo', 4, 'DaDuyet'),
(151, '49.01.103.050', '2024-12-04', 'Trồng cây gây rừng', 2, 'ChuaDuyet'),
(154, '49.01.103.002', '2025-11-02', 'Hiến máu nhân đạo', 23, 'DaDuyet'),
(161, '49.01.103.009', '2024-12-20', 'Hỗ trợ sinh viên nội trú', 4, 'ChuaDuyet'),
(162, '49.01.103.010', '2024-12-25', 'Tuyên truyền an toàn giao thông', 1, 'TuChoi'),
(163, '49.01.103.011', '2024-12-02', 'Tuyên truyền an toàn giao thông', 1, 'TuChoi'),
(164, '49.01.103.012', '2024-12-29', 'Hỗ trợ sinh viên nội trú', 2, 'ChuaDuyet'),
(165, '49.01.103.013', '2024-12-26', 'Hiến máu nhân đạo', 2, 'TuChoi'),
(166, '49.01.103.014', '2024-12-09', 'Tuyên truyền an toàn giao thông', 2, 'DaDuyet'),
(167, '49.01.103.015', '2024-12-23', 'Xuân tình nguyện', 4, 'ChuaDuyet'),
(168, '49.01.103.016', '2024-12-23', 'Hiến máu nhân đạo', 1, 'DaDuyet'),
(169, '49.01.103.017', '2024-12-14', 'Tham gia Tiếp sức mùa thi', 3, 'TuChoi'),
(170, '49.01.103.018', '2024-12-14', 'Hỗ trợ sinh viên nội trú', 5, 'TuChoi'),
(171, '49.01.103.019', '2024-12-05', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(172, '49.01.103.020', '2024-12-18', 'Xuân tình nguyện', 1, 'ChuaDuyet'),
(173, '49.01.103.021', '2024-12-13', 'Dọn vệ sinh khuôn viên trường', 5, 'DaDuyet'),
(174, '49.01.103.022', '2024-12-14', 'Xuân tình nguyện', 5, 'DaDuyet'),
(175, '49.01.103.023', '2024-12-25', 'Hỗ trợ sinh viên nội trú', 3, 'TuChoi'),
(176, '49.01.103.024', '2024-12-08', 'Dọn vệ sinh khuôn viên trường', 3, 'ChuaDuyet'),
(177, '49.01.103.025', '2024-12-23', 'Mùa hè xanh', 3, 'ChuaDuyet'),
(178, '49.01.103.026', '2024-12-10', 'Xuân tình nguyện', 3, 'TuChoi'),
(179, '49.01.103.027', '2024-12-05', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(180, '49.01.103.028', '2024-12-19', 'Mùa hè xanh', 5, 'DaDuyet'),
(181, '49.01.103.029', '2024-12-28', 'Xuân tình nguyện', 1, 'DaDuyet'),
(182, '49.01.103.030', '2024-12-21', 'Giúp đỡ học sinh nghèo', 1, 'ChuaDuyet'),
(183, '49.01.103.031', '2024-12-26', 'Ngày chủ nhật xanh', 5, 'DaDuyet'),
(184, '49.01.103.032', '2024-12-19', 'Hiến máu nhân đạo', 5, 'TuChoi'),
(185, '49.01.103.033', '2024-12-14', 'Hiến máu nhân đạo', 3, 'ChuaDuyet'),
(186, '49.01.103.034', '2024-12-14', 'Xuân tình nguyện', 4, 'DaDuyet'),
(187, '49.01.103.035', '2024-12-18', 'Giúp đỡ học sinh nghèo', 4, 'TuChoi'),
(188, '49.01.103.036', '2024-12-16', 'Tham gia Tiếp sức mùa thi', 3, 'DaDuyet'),
(189, '49.01.103.037', '2024-12-03', 'Hiến máu nhân đạo', 4, 'TuChoi'),
(190, '49.01.103.038', '2024-12-10', 'Ngày chủ nhật xanh', 2, 'TuChoi'),
(191, '49.01.103.039', '2024-12-16', 'Hiến máu nhân đạo', 5, 'TuChoi'),
(192, '49.01.103.040', '2024-12-19', 'Ngày chủ nhật xanh', 3, 'TuChoi'),
(193, '49.01.103.041', '2024-12-30', 'Xuân tình nguyện', 4, 'TuChoi'),
(194, '49.01.103.042', '2024-12-27', 'Ngày chủ nhật xanh', 3, 'DaDuyet'),
(195, '49.01.103.043', '2024-12-23', 'Giúp đỡ học sinh nghèo', 2, 'DaDuyet'),
(196, '49.01.103.044', '2024-12-11', 'Xuân tình nguyện', 4, 'DaDuyet'),
(197, '49.01.103.045', '2024-12-10', 'Hiến máu nhân đạo', 1, 'TuChoi'),
(198, '49.01.103.046', '2024-12-29', 'Tuyên truyền an toàn giao thông', 2, 'ChuaDuyet'),
(199, '49.01.103.047', '2024-12-03', 'Mùa hè xanh', 5, 'ChuaDuyet'),
(200, '49.01.103.048', '2024-12-14', 'Dọn vệ sinh khuôn viên trường', 4, 'ChuaDuyet'),
(201, '49.01.103.049', '2024-12-16', 'Hiến máu nhân đạo', 4, 'DaDuyet'),
(202, '49.01.103.050', '2024-12-04', 'Trồng cây gây rừng', 2, 'ChuaDuyet'),
(203, '49.01.103.101', '2025-11-05', 'Tham gia Tiếp sức mùa thi', 36, 'DaDuyet'),
(204, '49.01.103.102', '2025-11-08', 'Tuyên truyền an toàn giao thông', 19, 'DaDuyet'),
(205, '49.01.103.102', '2025-11-07', 'Xuân tình nguyện', 20, 'DaDuyet'),
(206, '49.01.103.001', '2024-12-10', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(207, '49.01.103.002', '2024-12-05', 'Hiến máu nhân đạo', 1, 'DaDuyet'),
(208, '49.01.103.003', '2024-12-13', 'Giúp đỡ học sinh nghèo', 5, 'ChuaDuyet'),
(209, '49.01.103.004', '2024-12-19', 'Tham gia Tiếp sức mùa thi', 4, 'DaDuyet'),
(210, '49.01.103.005', '2024-12-09', 'Tuyên truyền an toàn giao thông', 5, 'TuChoi'),
(211, '49.01.103.006', '2024-12-30', 'Giúp đỡ học sinh nghèo', 4, 'DaDuyet'),
(212, '49.01.103.007', '2024-12-19', 'Tham gia Tiếp sức mùa thi', 2, 'TuChoi'),
(213, '49.01.103.008', '2024-12-21', 'Tham gia Tiếp sức mùa thi', 1, 'DaDuyet'),
(214, '49.01.103.009', '2024-12-20', 'Hỗ trợ sinh viên nội trú', 4, 'ChuaDuyet'),
(215, '49.01.103.010', '2024-12-25', 'Tuyên truyền an toàn giao thông', 1, 'TuChoi'),
(216, '49.01.103.011', '2024-12-02', 'Tuyên truyền an toàn giao thông', 1, 'TuChoi'),
(217, '49.01.103.012', '2024-12-29', 'Hỗ trợ sinh viên nội trú', 2, 'ChuaDuyet'),
(218, '49.01.103.013', '2024-12-26', 'Hiến máu nhân đạo', 2, 'TuChoi'),
(219, '49.01.103.014', '2024-12-09', 'Tuyên truyền an toàn giao thông', 2, 'DaDuyet'),
(220, '49.01.103.015', '2024-12-23', 'Xuân tình nguyện', 4, 'ChuaDuyet'),
(221, '49.01.103.016', '2024-12-23', 'Hiến máu nhân đạo', 1, 'DaDuyet'),
(222, '49.01.103.017', '2024-12-14', 'Tham gia Tiếp sức mùa thi', 3, 'TuChoi'),
(223, '49.01.103.018', '2024-12-14', 'Hỗ trợ sinh viên nội trú', 5, 'TuChoi'),
(224, '49.01.103.019', '2024-12-05', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(225, '49.01.103.020', '2024-12-18', 'Xuân tình nguyện', 1, 'ChuaDuyet'),
(226, '49.01.103.021', '2024-12-13', 'Dọn vệ sinh khuôn viên trường', 5, 'DaDuyet'),
(227, '49.01.103.022', '2024-12-14', 'Xuân tình nguyện', 5, 'DaDuyet'),
(228, '49.01.103.023', '2024-12-25', 'Hỗ trợ sinh viên nội trú', 3, 'TuChoi'),
(229, '49.01.103.024', '2024-12-08', 'Dọn vệ sinh khuôn viên trường', 3, 'ChuaDuyet'),
(230, '49.01.103.025', '2024-12-23', 'Mùa hè xanh', 3, 'ChuaDuyet'),
(231, '49.01.103.026', '2024-12-10', 'Xuân tình nguyện', 3, 'TuChoi'),
(232, '49.01.103.027', '2024-12-05', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(233, '49.01.103.028', '2024-12-19', 'Mùa hè xanh', 5, 'DaDuyet'),
(234, '49.01.103.029', '2024-12-28', 'Xuân tình nguyện', 1, 'DaDuyet'),
(235, '49.01.103.030', '2024-12-21', 'Giúp đỡ học sinh nghèo', 1, 'ChuaDuyet'),
(236, '49.01.103.031', '2024-12-26', 'Ngày chủ nhật xanh', 5, 'DaDuyet'),
(237, '49.01.103.032', '2024-12-19', 'Hiến máu nhân đạo', 5, 'TuChoi'),
(238, '49.01.103.033', '2024-12-14', 'Hiến máu nhân đạo', 3, 'ChuaDuyet'),
(239, '49.01.103.034', '2024-12-14', 'Xuân tình nguyện', 4, 'DaDuyet'),
(240, '49.01.103.035', '2024-12-18', 'Giúp đỡ học sinh nghèo', 4, 'TuChoi'),
(241, '49.01.103.036', '2024-12-16', 'Tham gia Tiếp sức mùa thi', 3, 'DaDuyet'),
(242, '49.01.103.037', '2024-12-03', 'Hiến máu nhân đạo', 4, 'TuChoi'),
(243, '49.01.103.038', '2024-12-10', 'Ngày chủ nhật xanh', 2, 'TuChoi'),
(244, '49.01.103.039', '2024-12-16', 'Hiến máu nhân đạo', 5, 'TuChoi'),
(245, '49.01.103.040', '2024-12-19', 'Ngày chủ nhật xanh', 3, 'TuChoi'),
(246, '49.01.103.041', '2024-12-30', 'Xuân tình nguyện', 4, 'TuChoi'),
(247, '49.01.103.042', '2024-12-27', 'Ngày chủ nhật xanh', 3, 'DaDuyet'),
(248, '49.01.103.043', '2024-12-23', 'Giúp đỡ học sinh nghèo', 2, 'DaDuyet'),
(249, '49.01.103.044', '2024-12-11', 'Xuân tình nguyện', 4, 'DaDuyet'),
(250, '49.01.103.045', '2024-12-10', 'Hiến máu nhân đạo', 1, 'TuChoi'),
(251, '49.01.103.046', '2024-12-29', 'Tuyên truyền an toàn giao thông', 2, 'ChuaDuyet'),
(252, '49.01.103.047', '2024-12-03', 'Mùa hè xanh', 5, 'ChuaDuyet'),
(253, '49.01.103.048', '2024-12-14', 'Dọn vệ sinh khuôn viên trường', 4, 'ChuaDuyet'),
(254, '49.01.103.049', '2024-12-16', 'Hiến máu nhân đạo', 4, 'DaDuyet'),
(255, '49.01.103.050', '2024-12-04', 'Trồng cây gây rừng', 2, 'ChuaDuyet'),
(256, '49.01.103.001', '2025-12-20', 'Tham gia Tiếp sức mùa thi', 5, 'DaDuyet'),
(257, '49.01.103.002', '2024-12-05', 'Hiến máu nhân đạo', 1, 'DaDuyet'),
(258, '49.01.103.003', '2024-12-13', 'Giúp đỡ học sinh nghèo', 5, 'ChuaDuyet'),
(259, '49.01.103.004', '2024-12-19', 'Tham gia Tiếp sức mùa thi', 4, 'DaDuyet'),
(260, '49.01.103.005', '2024-12-09', 'Tuyên truyền an toàn giao thông', 5, 'TuChoi'),
(261, '49.01.103.006', '2024-12-30', 'Giúp đỡ học sinh nghèo', 4, 'DaDuyet'),
(262, '49.01.103.007', '2024-12-19', 'Tham gia Tiếp sức mùa thi', 2, 'TuChoi'),
(263, '49.01.103.008', '2024-12-21', 'Tham gia Tiếp sức mùa thi', 1, 'DaDuyet'),
(264, '49.01.103.009', '2024-12-20', 'Hỗ trợ sinh viên nội trú', 4, 'ChuaDuyet'),
(265, '49.01.103.010', '2024-12-25', 'Tuyên truyền an toàn giao thông', 1, 'TuChoi'),
(266, '49.01.103.011', '2024-12-02', 'Tuyên truyền an toàn giao thông', 1, 'TuChoi'),
(267, '49.01.103.012', '2024-12-29', 'Hỗ trợ sinh viên nội trú', 2, 'ChuaDuyet'),
(268, '49.01.103.013', '2024-12-26', 'Hiến máu nhân đạo', 2, 'TuChoi'),
(269, '49.01.103.014', '2024-12-09', 'Tuyên truyền an toàn giao thông', 2, 'DaDuyet'),
(270, '49.01.103.015', '2024-12-23', 'Xuân tình nguyện', 4, 'ChuaDuyet'),
(271, '49.01.103.016', '2024-12-23', 'Hiến máu nhân đạo', 1, 'DaDuyet'),
(272, '49.01.103.017', '2024-12-14', 'Tham gia Tiếp sức mùa thi', 3, 'TuChoi'),
(273, '49.01.103.018', '2024-12-14', 'Hỗ trợ sinh viên nội trú', 5, 'TuChoi'),
(274, '49.01.103.019', '2024-12-05', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(275, '49.01.103.020', '2024-12-18', 'Xuân tình nguyện', 1, 'ChuaDuyet'),
(276, '49.01.103.021', '2024-12-13', 'Dọn vệ sinh khuôn viên trường', 5, 'DaDuyet'),
(277, '49.01.103.022', '2024-12-14', 'Xuân tình nguyện', 5, 'DaDuyet'),
(278, '49.01.103.023', '2024-12-25', 'Hỗ trợ sinh viên nội trú', 3, 'TuChoi'),
(279, '49.01.103.024', '2024-12-08', 'Dọn vệ sinh khuôn viên trường', 3, 'ChuaDuyet'),
(280, '49.01.103.025', '2024-12-23', 'Mùa hè xanh', 3, 'ChuaDuyet'),
(281, '49.01.103.026', '2024-12-10', 'Xuân tình nguyện', 3, 'TuChoi'),
(282, '49.01.103.027', '2024-12-05', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(283, '49.01.103.028', '2024-12-19', 'Mùa hè xanh', 5, 'DaDuyet'),
(284, '49.01.103.029', '2024-12-28', 'Xuân tình nguyện', 1, 'DaDuyet'),
(285, '49.01.103.030', '2024-12-21', 'Giúp đỡ học sinh nghèo', 1, 'ChuaDuyet'),
(286, '49.01.103.031', '2024-12-26', 'Ngày chủ nhật xanh', 5, 'DaDuyet'),
(287, '49.01.103.032', '2024-12-19', 'Hiến máu nhân đạo', 5, 'TuChoi'),
(288, '49.01.103.033', '2024-12-14', 'Hiến máu nhân đạo', 3, 'ChuaDuyet'),
(289, '49.01.103.034', '2024-12-14', 'Xuân tình nguyện', 4, 'DaDuyet'),
(290, '49.01.103.035', '2024-12-18', 'Giúp đỡ học sinh nghèo', 4, 'TuChoi'),
(291, '49.01.103.036', '2024-12-16', 'Tham gia Tiếp sức mùa thi', 3, 'DaDuyet'),
(292, '49.01.103.037', '2024-12-03', 'Hiến máu nhân đạo', 4, 'TuChoi'),
(293, '49.01.103.038', '2024-12-10', 'Ngày chủ nhật xanh', 2, 'TuChoi'),
(294, '49.01.103.039', '2024-12-16', 'Hiến máu nhân đạo', 5, 'TuChoi'),
(295, '49.01.103.040', '2024-12-19', 'Ngày chủ nhật xanh', 3, 'TuChoi'),
(296, '49.01.103.041', '2024-12-30', 'Xuân tình nguyện', 4, 'TuChoi'),
(297, '49.01.103.042', '2024-12-27', 'Ngày chủ nhật xanh', 3, 'DaDuyet'),
(298, '49.01.103.043', '2024-12-23', 'Giúp đỡ học sinh nghèo', 2, 'DaDuyet'),
(299, '49.01.103.044', '2024-12-11', 'Xuân tình nguyện', 4, 'DaDuyet'),
(300, '49.01.103.045', '2024-12-10', 'Hiến máu nhân đạo', 1, 'TuChoi'),
(301, '49.01.103.046', '2024-12-29', 'Tuyên truyền an toàn giao thông', 2, 'ChuaDuyet'),
(302, '49.01.103.047', '2024-12-03', 'Mùa hè xanh', 5, 'ChuaDuyet'),
(303, '49.01.103.048', '2024-12-14', 'Dọn vệ sinh khuôn viên trường', 4, 'ChuaDuyet'),
(304, '49.01.103.049', '2024-12-16', 'Hiến máu nhân đạo', 4, 'DaDuyet'),
(305, '49.01.103.050', '2024-12-04', 'Trồng cây gây rừng', 2, 'ChuaDuyet'),
(306, '49.01.103.001', '2025-12-19', 'Tham gia Tiếp sức mùa thi', 5, 'DaDuyet'),
(307, '49.01.103.001', '2024-12-10', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(308, '49.01.103.002', '2024-12-05', 'Hiến máu nhân đạo', 1, 'DaDuyet'),
(309, '49.01.103.003', '2024-12-13', 'Giúp đỡ học sinh nghèo', 5, 'ChuaDuyet'),
(310, '49.01.103.004', '2024-12-19', 'Tham gia Tiếp sức mùa thi', 4, 'DaDuyet'),
(311, '49.01.103.005', '2024-12-09', 'Tuyên truyền an toàn giao thông', 5, 'TuChoi'),
(312, '49.01.103.006', '2024-12-30', 'Giúp đỡ học sinh nghèo', 4, 'DaDuyet'),
(313, '49.01.103.007', '2024-12-19', 'Tham gia Tiếp sức mùa thi', 2, 'TuChoi'),
(314, '49.01.103.008', '2024-12-21', 'Tham gia Tiếp sức mùa thi', 1, 'DaDuyet'),
(315, '49.01.103.009', '2024-12-20', 'Hỗ trợ sinh viên nội trú', 4, 'ChuaDuyet'),
(316, '49.01.103.010', '2024-12-25', 'Tuyên truyền an toàn giao thông', 1, 'TuChoi'),
(317, '49.01.103.011', '2024-12-02', 'Tuyên truyền an toàn giao thông', 1, 'TuChoi'),
(318, '49.01.103.012', '2024-12-29', 'Hỗ trợ sinh viên nội trú', 2, 'ChuaDuyet'),
(319, '49.01.103.013', '2024-12-26', 'Hiến máu nhân đạo', 2, 'TuChoi'),
(320, '49.01.103.014', '2024-12-09', 'Tuyên truyền an toàn giao thông', 2, 'DaDuyet'),
(321, '49.01.103.015', '2024-12-23', 'Xuân tình nguyện', 4, 'ChuaDuyet'),
(322, '49.01.103.016', '2024-12-23', 'Hiến máu nhân đạo', 1, 'DaDuyet'),
(323, '49.01.103.017', '2024-12-14', 'Tham gia Tiếp sức mùa thi', 3, 'TuChoi'),
(324, '49.01.103.018', '2024-12-14', 'Hỗ trợ sinh viên nội trú', 5, 'TuChoi'),
(325, '49.01.103.019', '2024-12-05', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(326, '49.01.103.020', '2024-12-18', 'Xuân tình nguyện', 1, 'ChuaDuyet'),
(327, '49.01.103.021', '2024-12-13', 'Dọn vệ sinh khuôn viên trường', 5, 'DaDuyet'),
(328, '49.01.103.022', '2024-12-14', 'Xuân tình nguyện', 5, 'DaDuyet'),
(329, '49.01.103.023', '2024-12-25', 'Hỗ trợ sinh viên nội trú', 3, 'TuChoi'),
(330, '49.01.103.024', '2024-12-08', 'Dọn vệ sinh khuôn viên trường', 3, 'ChuaDuyet'),
(331, '49.01.103.025', '2024-12-23', 'Mùa hè xanh', 3, 'ChuaDuyet'),
(332, '49.01.103.026', '2024-12-10', 'Xuân tình nguyện', 3, 'TuChoi'),
(333, '49.01.103.027', '2024-12-05', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(334, '49.01.103.028', '2024-12-19', 'Mùa hè xanh', 5, 'DaDuyet'),
(335, '49.01.103.029', '2024-12-28', 'Xuân tình nguyện', 1, 'DaDuyet'),
(336, '49.01.103.030', '2024-12-21', 'Giúp đỡ học sinh nghèo', 1, 'ChuaDuyet'),
(337, '49.01.103.031', '2024-12-26', 'Ngày chủ nhật xanh', 5, 'DaDuyet'),
(338, '49.01.103.032', '2024-12-19', 'Hiến máu nhân đạo', 5, 'TuChoi'),
(339, '49.01.103.033', '2024-12-14', 'Hiến máu nhân đạo', 3, 'ChuaDuyet'),
(340, '49.01.103.034', '2024-12-14', 'Xuân tình nguyện', 4, 'DaDuyet'),
(341, '49.01.103.035', '2024-12-18', 'Giúp đỡ học sinh nghèo', 4, 'TuChoi'),
(342, '49.01.103.036', '2024-12-16', 'Tham gia Tiếp sức mùa thi', 3, 'DaDuyet'),
(343, '49.01.103.037', '2024-12-03', 'Hiến máu nhân đạo', 4, 'TuChoi'),
(344, '49.01.103.038', '2024-12-10', 'Ngày chủ nhật xanh', 2, 'TuChoi'),
(345, '49.01.103.039', '2024-12-16', 'Hiến máu nhân đạo', 5, 'TuChoi'),
(346, '49.01.103.040', '2024-12-19', 'Ngày chủ nhật xanh', 3, 'TuChoi'),
(347, '49.01.103.041', '2024-12-30', 'Xuân tình nguyện', 4, 'TuChoi'),
(348, '49.01.103.042', '2024-12-27', 'Ngày chủ nhật xanh', 3, 'DaDuyet'),
(349, '49.01.103.043', '2024-12-23', 'Giúp đỡ học sinh nghèo', 2, 'DaDuyet'),
(350, '49.01.103.044', '2024-12-11', 'Xuân tình nguyện', 4, 'DaDuyet'),
(351, '49.01.103.045', '2024-12-10', 'Hiến máu nhân đạo', 1, 'TuChoi'),
(352, '49.01.103.046', '2024-12-29', 'Tuyên truyền an toàn giao thông', 2, 'ChuaDuyet'),
(353, '49.01.103.047', '2024-12-03', 'Mùa hè xanh', 5, 'ChuaDuyet'),
(354, '49.01.103.048', '2024-12-14', 'Dọn vệ sinh khuôn viên trường', 4, 'ChuaDuyet'),
(355, '49.01.103.049', '2024-12-16', 'Hiến máu nhân đạo', 4, 'DaDuyet'),
(356, '49.01.103.050', '2024-12-04', 'Trồng cây gây rừng', 2, 'ChuaDuyet'),
(357, '49.01.103.001', '2024-12-10', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(358, '49.01.103.002', '2024-12-05', 'Hiến máu nhân đạo', 1, 'DaDuyet'),
(359, '49.01.103.003', '2024-12-13', 'Giúp đỡ học sinh nghèo', 5, 'ChuaDuyet'),
(360, '49.01.103.004', '2024-12-19', 'Tham gia Tiếp sức mùa thi', 4, 'DaDuyet'),
(361, '49.01.103.005', '2024-12-09', 'Tuyên truyền an toàn giao thông', 5, 'TuChoi'),
(362, '49.01.103.006', '2024-12-30', 'Giúp đỡ học sinh nghèo', 4, 'DaDuyet'),
(363, '49.01.103.007', '2024-12-19', 'Tham gia Tiếp sức mùa thi', 2, 'TuChoi'),
(364, '49.01.103.008', '2024-12-21', 'Tham gia Tiếp sức mùa thi', 1, 'DaDuyet'),
(365, '49.01.103.009', '2024-12-20', 'Hỗ trợ sinh viên nội trú', 4, 'ChuaDuyet'),
(366, '49.01.103.010', '2024-12-25', 'Tuyên truyền an toàn giao thông', 1, 'TuChoi'),
(367, '49.01.103.011', '2024-12-02', 'Tuyên truyền an toàn giao thông', 1, 'TuChoi'),
(368, '49.01.103.012', '2024-12-29', 'Hỗ trợ sinh viên nội trú', 2, 'ChuaDuyet'),
(369, '49.01.103.013', '2024-12-26', 'Hiến máu nhân đạo', 2, 'TuChoi'),
(370, '49.01.103.014', '2024-12-09', 'Tuyên truyền an toàn giao thông', 2, 'DaDuyet'),
(371, '49.01.103.015', '2024-12-23', 'Xuân tình nguyện', 4, 'ChuaDuyet'),
(372, '49.01.103.016', '2024-12-23', 'Hiến máu nhân đạo', 1, 'DaDuyet'),
(373, '49.01.103.017', '2024-12-14', 'Tham gia Tiếp sức mùa thi', 3, 'TuChoi'),
(374, '49.01.103.018', '2024-12-14', 'Hỗ trợ sinh viên nội trú', 5, 'TuChoi'),
(375, '49.01.103.019', '2024-12-05', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(376, '49.01.103.020', '2024-12-18', 'Xuân tình nguyện', 1, 'ChuaDuyet'),
(377, '49.01.103.021', '2024-12-13', 'Dọn vệ sinh khuôn viên trường', 5, 'DaDuyet'),
(378, '49.01.103.022', '2024-12-14', 'Xuân tình nguyện', 5, 'DaDuyet'),
(379, '49.01.103.023', '2024-12-25', 'Hỗ trợ sinh viên nội trú', 3, 'TuChoi'),
(380, '49.01.103.024', '2024-12-08', 'Dọn vệ sinh khuôn viên trường', 3, 'ChuaDuyet'),
(381, '49.01.103.025', '2024-12-23', 'Mùa hè xanh', 3, 'ChuaDuyet'),
(382, '49.01.103.026', '2024-12-10', 'Xuân tình nguyện', 3, 'TuChoi'),
(383, '49.01.103.027', '2024-12-05', 'Tham gia Tiếp sức mùa thi', 5, 'TuChoi'),
(384, '49.01.103.028', '2024-12-19', 'Mùa hè xanh', 5, 'DaDuyet'),
(385, '49.01.103.029', '2024-12-28', 'Xuân tình nguyện', 1, 'DaDuyet'),
(386, '49.01.103.030', '2024-12-21', 'Giúp đỡ học sinh nghèo', 1, 'ChuaDuyet'),
(387, '49.01.103.031', '2024-12-26', 'Ngày chủ nhật xanh', 5, 'DaDuyet'),
(388, '49.01.103.032', '2024-12-19', 'Hiến máu nhân đạo', 5, 'TuChoi'),
(389, '49.01.103.033', '2024-12-14', 'Hiến máu nhân đạo', 3, 'ChuaDuyet'),
(390, '49.01.103.034', '2024-12-14', 'Xuân tình nguyện', 4, 'DaDuyet'),
(391, '49.01.103.035', '2024-12-18', 'Giúp đỡ học sinh nghèo', 4, 'TuChoi'),
(392, '49.01.103.036', '2024-12-16', 'Tham gia Tiếp sức mùa thi', 3, 'DaDuyet'),
(393, '49.01.103.037', '2024-12-03', 'Hiến máu nhân đạo', 4, 'TuChoi'),
(394, '49.01.103.038', '2024-12-10', 'Ngày chủ nhật xanh', 2, 'TuChoi'),
(395, '49.01.103.039', '2024-12-16', 'Hiến máu nhân đạo', 5, 'TuChoi'),
(396, '49.01.103.040', '2024-12-19', 'Ngày chủ nhật xanh', 3, 'TuChoi'),
(397, '49.01.103.041', '2024-12-30', 'Xuân tình nguyện', 4, 'TuChoi'),
(398, '49.01.103.042', '2024-12-27', 'Ngày chủ nhật xanh', 3, 'DaDuyet'),
(399, '49.01.103.043', '2024-12-23', 'Giúp đỡ học sinh nghèo', 2, 'DaDuyet'),
(400, '49.01.103.044', '2024-12-11', 'Xuân tình nguyện', 4, 'DaDuyet'),
(401, '49.01.103.045', '2024-12-10', 'Hiến máu nhân đạo', 1, 'TuChoi'),
(402, '49.01.103.046', '2024-12-29', 'Tuyên truyền an toàn giao thông', 2, 'ChuaDuyet'),
(403, '49.01.103.047', '2024-12-03', 'Mùa hè xanh', 5, 'ChuaDuyet'),
(404, '49.01.103.048', '2024-12-14', 'Dọn vệ sinh khuôn viên trường', 4, 'ChuaDuyet'),
(405, '49.01.103.049', '2024-12-16', 'Hiến máu nhân đạo', 4, 'DaDuyet'),
(406, '49.01.103.050', '2024-12-04', 'Trồng cây gây rừng', 2, 'ChuaDuyet');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_sinhvien`
--

CREATE TABLE `bang_sinhvien` (
  `MaSV` varchar(20) NOT NULL,
  `HoTen` varchar(100) NOT NULL,
  `NgaySinh` date NOT NULL,
  `Khoa` varchar(50) DEFAULT NULL,
  `Lop` varchar(50) DEFAULT NULL,
  `MaTK` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bang_sinhvien`
--

INSERT INTO `bang_sinhvien` (`MaSV`, `HoTen`, `NgaySinh`, `Khoa`, `Lop`, `MaTK`) VALUES
('49.01.103.001', 'Hoàng Minh Hà', '2005-12-28', 'Công nghệ thông tin', '49.CNTT.C', 5),
('49.01.103.002', 'Hoàng Thùy Nam', '2005-11-08', 'Công nghệ thông tin', '49.CNTT.B', 6),
('49.01.103.003', 'Đỗ Anh Dũng', '2005-11-27', 'Công nghệ thông tin', '49.SPTIN.A', 7),
('49.01.103.004', 'Phạm Hữu Giang', '2005-10-23', 'Công nghệ thông tin', '49.SPTIN.A', 8),
('49.01.103.005', 'Lê Văn Long', '2005-04-01', 'Công nghệ thông tin', '49.CNTT.B', 9),
('49.01.103.006', 'Võ Anh Trang', '2005-01-17', 'Công nghệ thông tin', '49.CNTT.B', 10),
('49.01.103.007', 'Bùi Anh Chi', '2005-07-11', 'Công nghệ thông tin', '49.CNTT.A', 11),
('49.01.103.008', 'Bùi Minh Chi', '2005-02-15', 'Công nghệ thông tin', '49.CNTT.D', 12),
('49.01.103.009', 'Bùi Văn Bình', '2005-03-26', 'Công nghệ thông tin', '49.CNTT.B', 13),
('49.01.103.010', 'Đặng Anh Hà', '2005-09-27', 'Công nghệ thông tin', '49.CNTT.C', 14),
('49.01.103.011', 'Huỳnh Quốc Giang', '2005-07-28', 'Công nghệ thông tin', '49.SPTIN.B', 15),
('49.01.103.012', 'Đặng Hữu Long', '2005-10-10', 'Công nghệ thông tin', '49.CNTT.D', 16),
('49.01.103.013', 'Nguyễn Phương Sơn', '2005-12-11', 'Công nghệ thông tin', '49.CNTT.C', 17),
('49.01.103.014', 'Lê Hữu Nam', '2005-09-25', 'Công nghệ thông tin', '49.SPTIN.A', 18),
('49.01.103.015', 'Huỳnh Quốc Nam', '2005-03-18', 'Công nghệ thông tin', '49.CNTT.C', 19),
('49.01.103.016', 'Bùi Thanh Sơn', '2005-11-07', 'Công nghệ thông tin', '49.CNTT.D', 20),
('49.01.103.017', 'Lê Ngọc Giang', '2005-08-24', 'Công nghệ thông tin', '49.SPTIN.A', 21),
('49.01.103.018', 'Bùi Văn Hùng', '2005-06-16', 'Công nghệ thông tin', '49.SPTIN.A', 22),
('49.01.103.019', 'Lê Thùy Bình', '2005-12-26', 'Công nghệ thông tin', '49.CNTT.B', 23),
('49.01.103.020', 'Phạm Ngọc Nam', '2005-08-27', 'Công nghệ thông tin', '49.CNTT.C', 24),
('49.01.103.021', 'Nguyễn Phương Mai', '2005-11-14', 'Công nghệ thông tin', '49.CNTT.C', 25),
('49.01.103.022', 'Đỗ Quốc Lan', '2005-07-13', 'Công nghệ thông tin', '49.CNTT.B', 26),
('49.01.103.023', 'Võ Quốc Lan', '2005-03-01', 'Công nghệ thông tin', '49.CNTT.B', 27),
('49.01.103.024', 'Bùi Thanh Lan', '2005-09-28', 'Công nghệ thông tin', '49.SPTIN.B', 28),
('49.01.103.025', 'Đỗ Thị Bình', '2005-07-11', 'Công nghệ thông tin', '49.CNTT.C', 29),
('49.01.103.026', 'Đặng Ngọc Dũng', '2005-02-02', 'Công nghệ thông tin', '49.CNTT.A', 30),
('49.01.103.027', 'Đặng Minh Bình', '2005-06-09', 'Công nghệ thông tin', '49.CNTT.B', 31),
('49.01.103.028', 'Đỗ Thị Nga', '2005-05-01', 'Công nghệ thông tin', '49.SPTIN.A', 32),
('49.01.103.029', 'Nguyễn Quốc An', '2005-08-06', 'Công nghệ thông tin', '49.SPTIN.A', 33),
('49.01.103.030', 'Đặng Thị Mai', '2005-10-23', 'Công nghệ thông tin', '49.SPTIN.B', 34),
('49.01.103.031', 'Đặng Thị Hùng', '2005-03-05', 'Công nghệ thông tin', '49.CNTT.B', 35),
('49.01.103.032', 'Hoàng Anh Nga', '2005-07-13', 'Công nghệ thông tin', '49.SPTIN.B', 36),
('49.01.103.033', 'Hoàng Thanh Hùng', '2005-11-08', 'Công nghệ thông tin', '49.SPTIN.B', 37),
('49.01.103.034', 'Võ Thanh Mai', '2005-11-05', 'Công nghệ thông tin', '49.CNTT.D', 38),
('49.01.103.035', 'Hoàng Thị An', '2005-11-14', 'Công nghệ thông tin', '49.CNTT.A', 39),
('49.01.103.036', 'Đặng Thùy Nga', '2005-12-30', 'Công nghệ thông tin', '49.SPTIN.A', 40),
('49.01.103.037', 'Phạm Thùy Lan', '2005-11-04', 'Công nghệ thông tin', '49.CNTT.A', 41),
('49.01.103.038', 'Đỗ Văn Yến', '2005-05-27', 'Công nghệ thông tin', '49.SPTIN.A', 42),
('49.01.103.039', 'Phạm Thùy Trang', '2005-05-21', 'Công nghệ thông tin', '49.SPTIN.A', 43),
('49.01.103.040', 'Bùi Văn Hà', '2005-03-05', 'Công nghệ thông tin', '49.CNTT.A', 44),
('49.01.103.041', 'Nguyễn Phương An', '2005-04-01', 'Công nghệ thông tin', '49.CNTT.B', 45),
('49.01.103.042', 'Đặng Ngọc Yến', '2005-12-30', 'Công nghệ thông tin', '49.SPTIN.B', 46),
('49.01.103.043', 'Võ Ngọc Long', '2005-11-20', 'Công nghệ thông tin', '49.CNTT.D', 47),
('49.01.103.044', 'Võ Thanh Bình', '2005-10-15', 'Công nghệ thông tin', '49.CNTT.B', 48),
('49.01.103.045', 'Phạm Thùy Phúc', '2005-11-15', 'Công nghệ thông tin', '49.CNTT.A', 49),
('49.01.103.046', 'Huỳnh Phương Mai', '2005-02-08', 'Công nghệ thông tin', '49.SPTIN.B', 50),
('49.01.103.047', 'Võ Minh Hà', '2005-12-12', 'Công nghệ thông tin', '49.CNTT.A', 51),
('49.01.103.048', 'Trần Minh An', '2005-08-16', 'Công nghệ thông tin', '49.CNTT.C', 52),
('49.01.103.049', 'Hoàng Ngọc Mai', '2005-07-24', 'Công nghệ thông tin', '49.SPTIN.A', 53),
('49.01.103.050', 'Bùi Quốc Trang', '2005-11-14', 'Công nghệ thông tin', '49.SPTIN.B', 54),
('49.01.103.052', 'Nguyễn Xuân Phát', '2005-06-09', 'Công nghệ thông tin', '49.SPTIN.A', 55),
('49.01.103.091', 'Võ Quỳnh Như', '2005-06-08', 'Công nghệ thông tin', '49.SPTINB', 58),
('49.01.103.101', 'Nguyễn Văn Luân', '2005-12-29', 'Công nghệ thông tin', '49.CNTT.D', 60),
('49.01.103.102', 'Huỳnh Thị Ái Xuân', '2005-07-08', 'Công nghệ thông tin', '49.SPTin.C', 62);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_sinhvien_danhhieu`
--

CREATE TABLE `bang_sinhvien_danhhieu` (
  `MaSV` varchar(20) NOT NULL,
  `MaDH` int(11) NOT NULL,
  `HocKy` tinyint(4) NOT NULL,
  `NamHoc` varchar(9) NOT NULL,
  `SoQuyetDinh` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_sukien`
--

CREATE TABLE `bang_sukien` (
  `MaSK` bigint(20) NOT NULL,
  `TieuDe` varchar(200) NOT NULL,
  `NoiDung` text NOT NULL,
  `ThoiGianBatDau` datetime NOT NULL,
  `ThoiGianKetThuc` datetime NOT NULL,
  `DiaDiem` varchar(255) NOT NULL,
  `SoLuongToiDa` int(11) DEFAULT NULL,
  `TrangThai` enum('Draft','Open','Closed','Cancelled') NOT NULL DEFAULT 'Open',
  `TaoLuc` datetime NOT NULL DEFAULT current_timestamp(),
  `CapNhatLuc` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bang_sukien`
--

INSERT INTO `bang_sukien` (`MaSK`, `TieuDe`, `NoiDung`, `ThoiGianBatDau`, `ThoiGianKetThuc`, `DiaDiem`, `SoLuongToiDa`, `TrangThai`, `TaoLuc`, `CapNhatLuc`) VALUES
(1, 'Up', 'Ngày tình nguyện: 2 ngày', '2025-12-18 12:58:00', '2025-12-19 12:58:00', 'Khu B', 4, 'Open', '2025-12-18 05:58:38', '2025-12-18 18:49:52'),
(3, 'Up2', 'Yp', '2025-12-18 14:30:00', '2025-12-18 18:00:00', 'Khu A', 6, 'Open', '2025-12-18 06:01:39', '2025-12-18 18:20:45'),
(6, 'Tiếp Sức Mùa Thi', 'Quy đổi: 1 buổi/1ntn', '2025-12-18 20:38:00', '2025-12-18 20:45:00', 'Đại học sư phạm TP Hồ Chí Minh', 10, 'Open', '2025-12-18 20:39:49', '2025-12-18 21:57:34'),
(7, 'Tháng Thanh niên', 'Các hoạt động hưởng ứng tinh thần xung kích vì cộng đồng, tổ chức thường niên vào tháng 2.', '2025-12-18 22:05:00', '2025-12-19 22:05:00', 'Khu A', 10, 'Open', '2025-12-18 22:05:39', '2025-12-18 22:05:39'),
(8, 'Chuyển đổi số', 'Phổ cập AI, kiến thức số, \"bình dân học số\" cho người dân.', '2025-12-18 22:29:00', '2025-12-21 00:30:00', 'Đại học sư phạm TP Hồ Chí Minh', 18, 'Open', '2025-12-18 22:28:42', '2025-12-18 23:50:59'),
(9, 'Phát 2.0', 'Tham gia hỗ trợ NXP đi quay Data', '2025-12-18 23:51:00', '2025-12-20 23:53:00', 'Khu A', 15, 'Open', '2025-12-18 23:52:50', '2025-12-19 12:40:02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_sukien_anh`
--

CREATE TABLE `bang_sukien_anh` (
  `MaAnh` bigint(20) NOT NULL,
  `MaSK` bigint(20) NOT NULL,
  `DuongDan` varchar(500) NOT NULL,
  `TenFile` varchar(255) DEFAULT NULL,
  `ThuTu` int(11) NOT NULL DEFAULT 1,
  `TaoLuc` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bang_sukien_anh`
--

INSERT INTO `bang_sukien_anh` (`MaAnh`, `MaSK`, `DuongDan`, `TenFile`, `ThuTu`, `TaoLuc`) VALUES
(1, 1, 'storage/sukien/TAP0DWBE16KdYsAhUDdckTuKylLK0C8G2UnuC0lT.jpg', 'anh_dai_dien.jpg', 1, '2025-12-18 05:58:38'),
(3, 3, 'storage/sukien/R5yWXyIu5B3iJbGVldQ0Bqn81InfpebVn8Sq60pm.png', 'Screenshot (3).png', 1, '2025-12-18 06:01:39'),
(7, 6, 'storage/sukien/JUTBzheENkQPweZRbvGNM6dkDPnAPqArMMPMV8gG.jpg', 'hoc-phi-dai-hoc-su-pham-tphcm-thumbnail.jpg', 1, '2025-12-18 20:39:50'),
(8, 7, 'storage/sukien/baWbXY9MEUVr8UwcQpiwzFW642c1IwrdNbxuKok0.jpg', 'lenovo.jpg', 1, '2025-12-18 22:05:39'),
(9, 8, 'storage/sukien/24JkcEDkDXI4RT1peFkTNqTtcM1pwAy4e8114R5B.jpg', 'hoc-phi-dai-hoc-su-pham-tphcm-thumbnail.jpg', 1, '2025-12-18 22:28:42'),
(10, 9, 'storage/sukien/xCvvRWZTfQsGR8Kpi6GVbFDdMS6c97QrlOnD83bi.jpg', 'Image.jpg', 1, '2025-12-18 23:52:50');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_taikhoan`
--

CREATE TABLE `bang_taikhoan` (
  `MaTK` int(11) NOT NULL,
  `TenDangNhap` varchar(50) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `VaiTro` enum('Admin','SinhVien','KhaoThi','CTCTHSSV','DoanTruong') NOT NULL,
  `TrangThai` enum('Active','Inactive','Locked') DEFAULT 'Active',
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bang_taikhoan`
--

INSERT INTO `bang_taikhoan` (`MaTK`, `TenDangNhap`, `MatKhau`, `VaiTro`, `TrangThai`, `Email`) VALUES
(1, 'admin', '$2y$12$PuSP0lbfeTKT1EwhScWRR.yz4nBwtOqUnCaCCwEiWWfEnd10dTFKy', 'Admin', 'Active', 'admin@example.com'),
(2, 'ctcthssv', '$2y$12$nXMofUi6wT3Gsw8eqa0M5u1uW4eIJ6SoX33NM9rkm.q1gDMRTDvrq', 'CTCTHSSV', 'Active', 'ctct@example.com'),
(3, 'khaothi', '$2y$12$MfHbYqkAXDDbp826DBx8LO7rQXM7vBJrz2LR3k2OmSR996SEZwv9K', 'KhaoThi', 'Active', 'khaothi@example.com'),
(4, 'doantruong', '$2y$12$9J/1dOTQjav.o.4FORy6g.hvzK5TdNOZDD6xIpRumQeMA25YhK.hC', 'DoanTruong', 'Active', 'doan@example.com'),
(5, '49.01.103.001', '$2y$12$Y1d/JgCnhyKAvh1pB7ZkNu.hivLOBMGajckLFF33VV01742Xj7q7a', 'SinhVien', 'Active', '4901103001@student.test'),
(6, '49.01.103.002', '$2y$12$PexM.q/YpTpPVOZdYy1/weNTDu3HLzBEQQtCwdTDj/qWM5WK5kO0i', 'SinhVien', 'Active', '4901103002@student.test'),
(7, '49.01.103.003', '$2y$12$on25FxuqNPFCWhj3mJglYefO7W43Fk.W0fD7XwyCWAyCuc9IYaJve', 'SinhVien', 'Active', '4901103003@student.test'),
(8, '49.01.103.004', '$2y$12$eni6EzB2L.JhylGLULIm3u7DSGQMsqezutnauFP651IDhm7ZU0wlK', 'SinhVien', 'Active', '4901103004@student.test'),
(9, '49.01.103.005', '$2y$12$vWHMhCHfWL9WNGwXc0lhZOHGuihS6.Ye8rJ2LcdP1Vd5fartvrRAC', 'SinhVien', 'Active', '4901103005@student.test'),
(10, '49.01.103.006', '$2y$12$6KrZk4OxT62mH6IIIK1HQu3uct4oFuSnw59Uk0hGNP6YFVKIwBH5u', 'SinhVien', 'Active', '4901103006@student.test'),
(11, '49.01.103.007', '$2y$12$6T3Fl.Rn90t7QSoqSUO0DOizqDy2JEezo14fXhqFZSpXPGpvbxvdO', 'SinhVien', 'Active', '4901103007@student.test'),
(12, '49.01.103.008', '$2y$12$vWunFsTlnfCwdd9qbNEUl.vgnqFk8kORpNAGfCmN67yNB.1UfEx0C', 'SinhVien', 'Active', '4901103008@student.test'),
(13, '49.01.103.009', '$2y$12$tk/eCa8onGjNKK5QOa9Ikexmlhsg3N03VfdQJB4r3O0Dl5l4Hyr2a', 'SinhVien', 'Active', '4901103009@student.test'),
(14, '49.01.103.010', '$2y$12$eRE5rCsJHjyWarE06anC8ukdd/iQcguF23AdmZr1a/190hTT9d0b6', 'SinhVien', 'Active', '4901103010@student.test'),
(15, '49.01.103.011', '$2y$12$ZvPg5VY/qvo4ZwDD6HYE4uh.lQ9dtxxGgbMbLQ589NdaEkfbsSFgS', 'SinhVien', 'Active', '4901103011@student.test'),
(16, '49.01.103.012', '$2y$12$IZgYWCUjTOL0lgRC0Hy0v.1/tev3IEyWzlQbxG1a/cQSQQUVMNueW', 'SinhVien', 'Active', '4901103012@student.test'),
(17, '49.01.103.013', '$2y$12$jVepvMtjVeguwiMW4/4ZzuNCVDUekQZC2gaP1.NrZUuP0DvVoydBi', 'SinhVien', 'Active', '4901103013@student.test'),
(18, '49.01.103.014', '$2y$12$jwFrHG5E2rAKu2oPM92rP..Dym9PXMqqWN1fPXqAGG5BAcipna.8q', 'SinhVien', 'Active', '4901103014@student.test'),
(19, '49.01.103.015', '$2y$12$7chIC7CqDQ9yumr0OlETLuk.9z5FdjLGFn55G9LOKS8ctWnKbSyGu', 'SinhVien', 'Active', '4901103015@student.test'),
(20, '49.01.103.016', '$2y$12$EP1HiggNEuw85D0GXayR1OZSLSMNoVQP/OOCNy1gCaBhfm/Mr.9X.', 'SinhVien', 'Active', '4901103016@student.test'),
(21, '49.01.103.017', '$2y$12$9IvNpUyiHsJStn4NKnqqW.k0BNFOmmm43cgb9MZ6aMQqqo.x1M3cu', 'SinhVien', 'Active', '4901103017@student.test'),
(22, '49.01.103.018', '$2y$12$9faR1sAMp5g08E0nmw1tM.T6vjrhdcAaOCPShCZwPB/9SNHhMvVru', 'SinhVien', 'Active', '4901103018@student.test'),
(23, '49.01.103.019', '$2y$12$wzdMShF6ZktdFn2PcFqQpeGbP20uHP2KdOnchcTppNtISodBvzJZa', 'SinhVien', 'Active', '4901103019@student.test'),
(24, '49.01.103.020', '$2y$12$LQXB2iWH4Gazn7wbV7B1Ve6CPwtiih2yd4PgfGP5yjyBAt/4JJNpG', 'SinhVien', 'Active', '4901103020@student.test'),
(25, '49.01.103.021', '$2y$12$nQtLO8VWMpipPOKLyG3okeoXAxtpZxnU7KG8KMSkqbDJvCQlwJvk6', 'SinhVien', 'Active', '4901103021@student.test'),
(26, '49.01.103.022', '$2y$12$FLqQG588dKtcyQrUvgO/T.7klZyk2udA9n9FgXUc3.pSBaGxVkU.m', 'SinhVien', 'Active', '4901103022@student.test'),
(27, '49.01.103.023', '$2y$12$EgFulD5qG3unIAagMV5kUurXPyJm7KvSEQDeXL0RAiei8IMGLWfxm', 'SinhVien', 'Active', '4901103023@student.test'),
(28, '49.01.103.024', '$2y$12$MsNj4garxz59Sav5IJYnsuNJo1Um.pgExC4RSAMO1RaHesLnlq39q', 'SinhVien', 'Active', '4901103024@student.test'),
(29, '49.01.103.025', '$2y$12$P340nHRIhZkMG64AF3l.sOOOBGnQ53XPrFxme06wRQMA7fCNfAd9G', 'SinhVien', 'Active', '4901103025@student.test'),
(30, '49.01.103.026', '$2y$12$R3Q7.qvg3zB8tvV5zCfdhOIoExm7jrhqLNa3RsqEoTTKj8q4svexa', 'SinhVien', 'Active', '4901103026@student.test'),
(31, '49.01.103.027', '$2y$12$UVYVKE3EbFbG0BhqJWWVDeTg41hTEfcBCOQmpd8rlN2kGH/E2rFLi', 'SinhVien', 'Active', '4901103027@student.test'),
(32, '49.01.103.028', '$2y$12$6Vwqij4.QNm971uWDNm2L.nMWW2oQYLhhLgPsW38XHPUw3DOJ4lzm', 'SinhVien', 'Active', '4901103028@student.test'),
(33, '49.01.103.029', '$2y$12$wSdvIwyfrFdFLQ./ao0y6u7RqVDcx5HCseqVUPTufQEA6prB8rS/W', 'SinhVien', 'Active', '4901103029@student.test'),
(34, '49.01.103.030', '$2y$12$Net70GGWQ.22J06ksPmbEeLbXzF8u4RTQ71We2odvYJWaTZJT3Uq2', 'SinhVien', 'Active', '4901103030@student.test'),
(35, '49.01.103.031', '$2y$12$mgkIaTJAon0S/jD3n3iameE/YdJeYOqX38xMU86AZxdje4aFPliHy', 'SinhVien', 'Active', '4901103031@student.test'),
(36, '49.01.103.032', '$2y$12$7Q.gwZ.PVggA0hSbLeR38u5P5818.jPVgANtO2DODCf1G/XISDwuC', 'SinhVien', 'Active', '4901103032@student.test'),
(37, '49.01.103.033', '$2y$12$uFL0Pb8RywGBfsY6XIcdSOYCay3xK6MpVseXO7P1dM.JLpYhTf0EG', 'SinhVien', 'Active', '4901103033@student.test'),
(38, '49.01.103.034', '$2y$12$wEZcxlRx0OY4Oh/28d9OGevH22Ko9pyHta/VRs6S1fhjVdiDQnvGW', 'SinhVien', 'Active', '4901103034@student.test'),
(39, '49.01.103.035', '$2y$12$Ums9a3bSpEPikhThEmpUFOytl9Ehzpb07d0TE2jj3/1ts0dCCYEPu', 'SinhVien', 'Active', '4901103035@student.test'),
(40, '49.01.103.036', '$2y$12$NyMiQieSqVO2ytSWwt5FmO.zsZjJ/yhw2QMUJWMT90MiWFa8pGzE2', 'SinhVien', 'Active', '4901103036@student.test'),
(41, '49.01.103.037', '$2y$12$1DIj5YnESbgD/lj1eTCoROalZW0H56HwFaNs60IDEp42U3dLjk.9C', 'SinhVien', 'Active', '4901103037@student.test'),
(42, '49.01.103.038', '$2y$12$8eL/nU1DsWdqaaqM2gukRuQe0wi8AkydtmXlXSdxhup67QzDakNMq', 'SinhVien', 'Active', '4901103038@student.test'),
(43, '49.01.103.039', '$2y$12$Ijdnzmz68K2MXy/mU1Ln5ObdcucaFlPRrJlZcpoen/P1N9IRBlqZC', 'SinhVien', 'Active', '4901103039@student.test'),
(44, '49.01.103.040', '$2y$12$HSD9hT68InjM5315XHZdaeOqGpp9DnuBoDUkeIeF3rQVpSzmD.6YW', 'SinhVien', 'Active', '4901103040@student.test'),
(45, '49.01.103.041', '$2y$12$O6OqkS313R4Qa0cKXUEtN.AibmETdxJesT3nuhTDynEDene2UK4A6', 'SinhVien', 'Active', '4901103041@student.test'),
(46, '49.01.103.042', '$2y$12$AFfKGj6mKhW38MJi.gqVqu8BNtNuA/sOjK4t3XSN8z2GOq2ovs7ZW', 'SinhVien', 'Active', '4901103042@student.test'),
(47, '49.01.103.043', '$2y$12$/Yh92iJLMgpdS.kEz57ChOy0W5YC75TqmDpli8dYIAJ5yWwFXpUbu', 'SinhVien', 'Active', '4901103043@student.test'),
(48, '49.01.103.044', '$2y$12$iQ.Ti7cjgs6dHml93lt9FOsNShxGPvKuKtdhomifUWrig4NrrA2bq', 'SinhVien', 'Active', '4901103044@student.test'),
(49, '49.01.103.045', '$2y$12$RD75FsYoDxV/H3Ou51WGXOWG4KKa7.jun96vdEru9HPgE6JIzCw1C', 'SinhVien', 'Active', '4901103045@student.test'),
(50, '49.01.103.046', '$2y$12$vRY716UK42sQl/tGH5IZnOkheysQXHVtIBj.dFlexJMQVn7yD9/fS', 'SinhVien', 'Active', '4901103046@student.test'),
(51, '49.01.103.047', '$2y$12$B03QmLDwXysTCO2gVmlYiuXYz4145WPpsosv8nobUwT6vS3FxMPl6', 'SinhVien', 'Active', '4901103047@student.test'),
(52, '49.01.103.048', '$2y$12$syuyxWcu/Wke68BNU5CpWO.jKAiYAP0EHRbMKztl75/ERbbADm7NO', 'SinhVien', 'Active', '4901103048@student.test'),
(53, '49.01.103.049', '$2y$12$85wg./N2OsEFpI6LeBP6juznWCQj8ZE6ngiuuBvhkDmWmekgsRCIW', 'SinhVien', 'Active', '4901103049@student.test'),
(54, '49.01.103.050', '$2y$12$GBzThldL/E8wlGxnYpf4a.zo5sJA8xjG.bN/CZ7uoQRl4i7bwAmqG', 'SinhVien', 'Active', '4901103050@student.test'),
(55, '49.01.103.052', '$2y$12$KgZ7n6kwCbdGDkQqSIl2oOlrIz94l8QTTN/2ldhczfTJwU3qijX3C', 'SinhVien', 'Active', '4901103052@student.test'),
(58, '49.01.103.091', '$2y$12$n7maEgMJQv.ODAe4jg2L2e6FSy60IfBZ8aLsmvKiJdfgIJN.pYlM2', 'SinhVien', 'Active', '4901103090@student.test'),
(60, '49.01.103.101', '$2y$12$GpKlvYbqMg2UB..idKyDje19EuZ/wDpjwVN6ujswh3OMhsO.RQbRe', 'SinhVien', 'Active', '4901103101@student.test'),
(61, '49.01.103.061', '$2y$12$D436SIHUgyqnglPEBitW6unjNptzm71WcPgMia3JgBPLAucZ3gi5y', 'SinhVien', 'Active', '4901103060@student.test'),
(62, '49.01.103.102', '$2y$12$jYT/IvcnnifMBbKRFd98EuWNHL9fynOX2d1MJt3Z7sM65TZCyornW', 'SinhVien', 'Active', '4901103102@student.test');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('KXgVIdoqByML9b4KvjvUp8rKUhV6qVfFdZD00Fdk', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVWFzMDJza3V1REFSdk1GeFllN0U1T0dGaEU1aE9vVkFEMlN1T21MdSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTY6InJlc2V0X29rX3VzZXJfaWQiO2k6MTt9', 1766159884);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bang_admin`
--
ALTER TABLE `bang_admin`
  ADD PRIMARY KEY (`MaAdmin`),
  ADD UNIQUE KEY `MaTK` (`MaTK`);

--
-- Chỉ mục cho bảng `bang_ctcthssv`
--
ALTER TABLE `bang_ctcthssv`
  ADD PRIMARY KEY (`MaCTCT`),
  ADD UNIQUE KEY `TenPhong` (`TenPhong`),
  ADD UNIQUE KEY `MaTK` (`MaTK`);

--
-- Chỉ mục cho bảng `bang_dangkysukien`
--
ALTER TABLE `bang_dangkysukien`
  ADD PRIMARY KEY (`MaSK`,`MaSV`),
  ADD KEY `idx_dksk_mask` (`MaSK`),
  ADD KEY `idx_dksk_diemdanh` (`DaDiemDanh`,`DiemDanhLuc`),
  ADD KEY `idx_dksk_trangthai` (`TrangThaiDangKy`),
  ADD KEY `idx_dksk_masv` (`MaSV`);

--
-- Chỉ mục cho bảng `bang_danhhieu`
--
ALTER TABLE `bang_danhhieu`
  ADD PRIMARY KEY (`MaDH`),
  ADD UNIQUE KEY `TenDH` (`TenDH`);

--
-- Chỉ mục cho bảng `bang_diemhoctap`
--
ALTER TABLE `bang_diemhoctap`
  ADD PRIMARY KEY (`MaSV`,`NamHoc`,`HocKy`),
  ADD KEY `idx_DHT_MaSV` (`MaSV`),
  ADD KEY `idx_DHT_MaPKT` (`MaPKT`);

--
-- Chỉ mục cho bảng `bang_diemrenluyen`
--
ALTER TABLE `bang_diemrenluyen`
  ADD PRIMARY KEY (`MaSV`,`NamHoc`,`HocKy`),
  ADD KEY `idx_DRL_MaSV` (`MaSV`);

--
-- Chỉ mục cho bảng `bang_doantruong`
--
ALTER TABLE `bang_doantruong`
  ADD PRIMARY KEY (`MaDT`),
  ADD UNIQUE KEY `TenDT` (`TenDT`),
  ADD UNIQUE KEY `MaTK` (`MaTK`);

--
-- Chỉ mục cho bảng `bang_khaothi`
--
ALTER TABLE `bang_khaothi`
  ADD PRIMARY KEY (`MaPKT`),
  ADD UNIQUE KEY `TenPhong` (`TenPhong`),
  ADD UNIQUE KEY `MaTK` (`MaTK`);

--
-- Chỉ mục cho bảng `bang_ngaytinhnguyen`
--
ALTER TABLE `bang_ngaytinhnguyen`
  ADD PRIMARY KEY (`MaNTN`),
  ADD KEY `idx_NTN_MaSV` (`MaSV`);

--
-- Chỉ mục cho bảng `bang_sinhvien`
--
ALTER TABLE `bang_sinhvien`
  ADD PRIMARY KEY (`MaSV`),
  ADD UNIQUE KEY `MaTK` (`MaTK`);

--
-- Chỉ mục cho bảng `bang_sinhvien_danhhieu`
--
ALTER TABLE `bang_sinhvien_danhhieu`
  ADD PRIMARY KEY (`MaSV`,`MaDH`,`NamHoc`,`HocKy`),
  ADD KEY `MaDH` (`MaDH`);

--
-- Chỉ mục cho bảng `bang_sukien`
--
ALTER TABLE `bang_sukien`
  ADD PRIMARY KEY (`MaSK`),
  ADD KEY `idx_sukien_thoigian` (`ThoiGianBatDau`,`ThoiGianKetThuc`),
  ADD KEY `idx_sukien_trangthai` (`TrangThai`);

--
-- Chỉ mục cho bảng `bang_sukien_anh`
--
ALTER TABLE `bang_sukien_anh`
  ADD PRIMARY KEY (`MaAnh`),
  ADD KEY `idx_sukien_anh_mask` (`MaSK`);

--
-- Chỉ mục cho bảng `bang_taikhoan`
--
ALTER TABLE `bang_taikhoan`
  ADD PRIMARY KEY (`MaTK`),
  ADD UNIQUE KEY `TenDangNhap` (`TenDangNhap`);

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bang_danhhieu`
--
ALTER TABLE `bang_danhhieu`
  MODIFY `MaDH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `bang_ngaytinhnguyen`
--
ALTER TABLE `bang_ngaytinhnguyen`
  MODIFY `MaNTN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=407;

--
-- AUTO_INCREMENT cho bảng `bang_sukien`
--
ALTER TABLE `bang_sukien`
  MODIFY `MaSK` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `bang_sukien_anh`
--
ALTER TABLE `bang_sukien_anh`
  MODIFY `MaAnh` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `bang_taikhoan`
--
ALTER TABLE `bang_taikhoan`
  MODIFY `MaTK` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bang_admin`
--
ALTER TABLE `bang_admin`
  ADD CONSTRAINT `bang_admin_ibfk_1` FOREIGN KEY (`MaTK`) REFERENCES `bang_taikhoan` (`MaTK`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bang_ctcthssv`
--
ALTER TABLE `bang_ctcthssv`
  ADD CONSTRAINT `bang_ctcthssv_ibfk_1` FOREIGN KEY (`MaTK`) REFERENCES `bang_taikhoan` (`MaTK`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bang_dangkysukien`
--
ALTER TABLE `bang_dangkysukien`
  ADD CONSTRAINT `fk_dksk_mask` FOREIGN KEY (`MaSK`) REFERENCES `bang_sukien` (`MaSK`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dksk_masv` FOREIGN KEY (`MaSV`) REFERENCES `bang_sinhvien` (`MaSV`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bang_diemhoctap`
--
ALTER TABLE `bang_diemhoctap`
  ADD CONSTRAINT `bang_diemhoctap_ibfk_1` FOREIGN KEY (`MaSV`) REFERENCES `bang_sinhvien` (`MaSV`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bang_diemhoctap_ibfk_2` FOREIGN KEY (`MaPKT`) REFERENCES `bang_khaothi` (`MaPKT`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bang_diemrenluyen`
--
ALTER TABLE `bang_diemrenluyen`
  ADD CONSTRAINT `bang_diemrenluyen_ibfk_1` FOREIGN KEY (`MaSV`) REFERENCES `bang_sinhvien` (`MaSV`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bang_doantruong`
--
ALTER TABLE `bang_doantruong`
  ADD CONSTRAINT `bang_doantruong_ibfk_1` FOREIGN KEY (`MaTK`) REFERENCES `bang_taikhoan` (`MaTK`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bang_khaothi`
--
ALTER TABLE `bang_khaothi`
  ADD CONSTRAINT `bang_khaothi_ibfk_1` FOREIGN KEY (`MaTK`) REFERENCES `bang_taikhoan` (`MaTK`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bang_ngaytinhnguyen`
--
ALTER TABLE `bang_ngaytinhnguyen`
  ADD CONSTRAINT `bang_ngaytinhnguyen_ibfk_1` FOREIGN KEY (`MaSV`) REFERENCES `bang_sinhvien` (`MaSV`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bang_sinhvien`
--
ALTER TABLE `bang_sinhvien`
  ADD CONSTRAINT `bang_sinhvien_ibfk_1` FOREIGN KEY (`MaTK`) REFERENCES `bang_taikhoan` (`MaTK`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bang_sinhvien_danhhieu`
--
ALTER TABLE `bang_sinhvien_danhhieu`
  ADD CONSTRAINT `bang_sinhvien_danhhieu_ibfk_1` FOREIGN KEY (`MaSV`) REFERENCES `bang_sinhvien` (`MaSV`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bang_sinhvien_danhhieu_ibfk_2` FOREIGN KEY (`MaDH`) REFERENCES `bang_danhhieu` (`MaDH`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bang_sukien_anh`
--
ALTER TABLE `bang_sukien_anh`
  ADD CONSTRAINT `fk_sukien_anh_mask` FOREIGN KEY (`MaSK`) REFERENCES `bang_sukien` (`MaSK`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
