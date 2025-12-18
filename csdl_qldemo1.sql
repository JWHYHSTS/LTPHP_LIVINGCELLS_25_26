-- =======================================
-- 1️⃣ TẠO CƠ SỞ DỮ LIỆU
-- =======================================
CREATE DATABASE IF NOT EXISTS qldemo1
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE qldemo1;

-- =======================================
-- 2️⃣ BẢNG TÀI KHOẢN
-- =======================================
CREATE TABLE BANG_TaiKhoan (
    MaTK INT AUTO_INCREMENT PRIMARY KEY,
    TenDangNhap VARCHAR(50) NOT NULL UNIQUE,
    MatKhau VARCHAR(255) NOT NULL,
    VaiTro ENUM('Admin','SinhVien','KhaoThi','CTCTHSSV','DoanTruong') NOT NULL,
    TrangThai ENUM('Active','Inactive','Locked') DEFAULT 'Active',
    Email VARCHAR(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================
-- 3️⃣ BẢNG SINH VIÊN
-- =======================================
CREATE TABLE BANG_SinhVien (
    MaSV VARCHAR(20) PRIMARY KEY,
    HoTen VARCHAR(100) NOT NULL,
    NgaySinh DATE NOT NULL,
    Khoa VARCHAR(50),
    Lop VARCHAR(50),
    MaTK INT UNIQUE,
    FOREIGN KEY (MaTK) REFERENCES BANG_TaiKhoan(MaTK)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================
-- 4️⃣ BẢNG ADMIN
-- =======================================
CREATE TABLE BANG_Admin (
    MaAdmin VARCHAR(20) PRIMARY KEY,
    MaTK INT UNIQUE NOT NULL,
    FOREIGN KEY (MaTK) REFERENCES BANG_TaiKhoan(MaTK)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================
-- 5️⃣ BẢNG PHÒNG KHẢO THÍ
-- =======================================
CREATE TABLE BANG_KhaoThi (
    MaPKT VARCHAR(20) PRIMARY KEY,
    TenPhong VARCHAR(50) UNIQUE NOT NULL,
    NguoiQL VARCHAR(50) NOT NULL,
    MaTK INT UNIQUE,
    FOREIGN KEY (MaTK) REFERENCES BANG_TaiKhoan(MaTK)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================
-- 6️⃣ BẢNG CTCT-HSSV
-- =======================================
CREATE TABLE BANG_CTCTHSSV (
    MaCTCT VARCHAR(20) PRIMARY KEY,
    TenPhong VARCHAR(50) UNIQUE NOT NULL,
    NguoiQL VARCHAR(50) NOT NULL,
    MaTK INT UNIQUE,
    FOREIGN KEY (MaTK) REFERENCES BANG_TaiKhoan(MaTK)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================
-- 7️⃣ BẢNG ĐOÀN TRƯỜNG
-- =======================================
CREATE TABLE BANG_DoanTruong (
    MaDT VARCHAR(20) PRIMARY KEY,
    TenDT VARCHAR(50) UNIQUE NOT NULL,
    NguoiQL VARCHAR(50) NOT NULL,
    MaTK INT UNIQUE,
    FOREIGN KEY (MaTK) REFERENCES BANG_TaiKhoan(MaTK)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================
-- 8️⃣ BẢNG ĐIỂM HỌC TẬP
-- =======================================
CREATE TABLE BANG_DiemHocTap (
    MaSV VARCHAR(20) NOT NULL,
    HocKy TINYINT NOT NULL,
    NamHoc VARCHAR(9) NOT NULL,
    DiemHe4 DECIMAL(3,2) NOT NULL,
    XepLoai VARCHAR(50),
    MaPKT VARCHAR(20),
    PRIMARY KEY (MaSV, NamHoc, HocKy),
    FOREIGN KEY (MaSV) REFERENCES BANG_SinhVien(MaSV)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (MaPKT) REFERENCES BANG_KhaoThi(MaPKT)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================
-- 9️⃣ BẢNG ĐIỂM RÈN LUYỆN
-- =======================================
CREATE TABLE BANG_DiemRenLuyen (
    MaSV VARCHAR(20) NOT NULL,
    HocKy TINYINT NOT NULL,
    NamHoc VARCHAR(9) NOT NULL,
    DiemRL SMALLINT NOT NULL,
    XepLoai VARCHAR(20),
    PRIMARY KEY (MaSV, NamHoc, HocKy),
    FOREIGN KEY (MaSV) REFERENCES BANG_SinhVien(MaSV)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================
-- 🔟 BẢNG NGÀY TÌNH NGUYỆN
-- =======================================
CREATE TABLE BANG_NgayTinhNguyen (
    MaNTN INT AUTO_INCREMENT PRIMARY KEY,
    MaSV VARCHAR(20) NOT NULL,
    NgayThamGia DATE NOT NULL,
    TenHoatDong VARCHAR(200) NOT NULL,
    SoNgayTN INT NOT NULL,
    TrangThaiDuyet ENUM('ChuaDuyet','DaDuyet','TuChoi') DEFAULT 'ChuaDuyet',
    FOREIGN KEY (MaSV) REFERENCES BANG_SinhVien(MaSV)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================
-- 1️⃣1️⃣ BẢNG DANH HIỆU
-- =======================================
CREATE TABLE BANG_DanhHieu (
    MaDH INT AUTO_INCREMENT PRIMARY KEY,
    TenDH VARCHAR(100) UNIQUE NOT NULL,
    DieuKienGPA DECIMAL(3,2) NOT NULL,
    DieuKienDRL SMALLINT NOT NULL,
    DieuKienNTN INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================
-- 1️⃣2️⃣ BẢNG SINH VIÊN - DANH HIỆU
-- =======================================
CREATE TABLE BANG_SinhVien_DanhHieu (
    MaSV VARCHAR(20) NOT NULL,
    MaDH INT NOT NULL,
    HocKy TINYINT NOT NULL,
    NamHoc VARCHAR(9) NOT NULL,
    SoQuyetDinh VARCHAR(50) NOT NULL,
    PRIMARY KEY (MaSV, MaDH, NamHoc, HocKy),
    FOREIGN KEY (MaSV) REFERENCES BANG_SinhVien(MaSV)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (MaDH) REFERENCES BANG_DanhHieu(MaDH)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================
-- 1️⃣3️⃣ CHỈ MỤC PHỤ
-- =======================================
CREATE INDEX idx_DHT_MaSV ON BANG_DiemHocTap(MaSV);
CREATE INDEX idx_DRL_MaSV ON BANG_DiemRenLuyen(MaSV);
CREATE INDEX idx_NTN_MaSV ON BANG_NgayTinhNguyen(MaSV);
CREATE INDEX idx_DHT_MaPKT ON BANG_DiemHocTap(MaPKT);
-- =======================================
-- =========================================================
-- 14. QUẢN LÝ SỰ KIỆN + NHIỀU ẢNH + ĐĂNG KÝ + ĐIỂM DANH
-- (ĐÃ CHUẨN HÓA TIMEZONE & KHẮC PHỤC LỆCH GIỜ)
-- =========================================================

/*!40103 SET time_zone = '+07:00' */;

-- =========================================================
-- 14.1. BẢNG SỰ KIỆN
-- =========================================================
CREATE TABLE IF NOT EXISTS BANG_SuKien (
    MaSK BIGINT AUTO_INCREMENT PRIMARY KEY,

    TieuDe VARCHAR(200) NOT NULL,
    NoiDung TEXT NOT NULL,

    ThoiGianBatDau TIMESTAMP NOT NULL,
    ThoiGianKetThuc TIMESTAMP NOT NULL,
    DiaDiem VARCHAR(255) NOT NULL,

    SoLuongToiDa INT NULL,
    TrangThai ENUM('Draft','Open','Closed','Cancelled')
        NOT NULL DEFAULT 'Open',

    TaoLuc TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CapNhatLuc TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_sukien_thoigian
    ON BANG_SuKien(ThoiGianBatDau, ThoiGianKetThuc);

CREATE INDEX idx_sukien_trangthai
    ON BANG_SuKien(TrangThai);


-- =========================================================
-- 14.2. BẢNG ẢNH SỰ KIỆN (NHIỀU ẢNH)
-- =========================================================
CREATE TABLE IF NOT EXISTS BANG_SuKien_Anh (
    MaAnh BIGINT AUTO_INCREMENT PRIMARY KEY,
    MaSK BIGINT NOT NULL,

    DuongDan VARCHAR(500) NOT NULL,
    TenFile VARCHAR(255) NULL,
    ThuTu INT NOT NULL DEFAULT 1,

    TaoLuc TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_sukien_anh_mask
        FOREIGN KEY (MaSK)
        REFERENCES BANG_SuKien(MaSK)
        ON UPDATE CASCADE
        ON DELETE CASCADE

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_sukien_anh_mask
    ON BANG_SuKien_Anh(MaSK);


-- =========================================================
-- 14.3. BẢNG ĐĂNG KÝ SỰ KIỆN + ĐIỂM DANH
-- =========================================================
CREATE TABLE IF NOT EXISTS BANG_DangKySuKien (
    MaSK BIGINT NOT NULL,
    MaSV VARCHAR(20) NOT NULL,

    DangKyLuc TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    TrangThaiDangKy ENUM('Registered','Cancelled')
        NOT NULL DEFAULT 'Registered',

    DaDiemDanh TINYINT(1) NOT NULL DEFAULT 0,
    DiemDanhLuc TIMESTAMP NULL,

    GhiChu VARCHAR(255) NULL,

    PRIMARY KEY (MaSK, MaSV),

    CONSTRAINT fk_dksk_mask
        FOREIGN KEY (MaSK)
        REFERENCES BANG_SuKien(MaSK)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_dksk_masv
        FOREIGN KEY (MaSV)
        REFERENCES BANG_SinhVien(MaSV)
        ON UPDATE CASCADE
        ON DELETE CASCADE

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_dksk_masv
    ON BANG_DangKySuKien(MaSV);

CREATE INDEX idx_dksk_mask
    ON BANG_DangKySuKien(MaSK);

CREATE INDEX idx_dksk_diemdanh
    ON BANG_DangKySuKien(DaDiemDanh, DiemDanhLuc);

CREATE INDEX idx_dksk_trangthai
    ON BANG_DangKySuKien(TrangThaiDangKy);

-- =========================================================
