# 🌱 Living Cells  
## 🎓 HỆ THỐNG QUẢN LÝ RÈN LUYỆN & KHEN THƯỞNG SINH VIÊN

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?logo=bootstrap&logoColor=white)
![GitHub](https://img.shields.io/badge/GitHub-Repository-181717?logo=github)
![License](https://img.shields.io/badge/License-MIT-green)

> Nền tảng web quản lý tập trung thông tin sinh viên, điểm học tập – rèn luyện, hoạt động tình nguyện, sự kiện và danh hiệu khen thưởng theo mô hình phân quyền.

---

## 📑 Mục lục
1. Giới thiệu dự án  
2. Tổng quan dự án  
3. Đối tượng sử dụng  
4. Tính năng chính  
5. Kiến trúc & Thiết kế hệ thống  
6. Công nghệ sử dụng  
7. Cài đặt & Thiết lập  
8. Deploy Hosting (Production)  
9. Danh sách sinh viên thực hiện  
10. Cấu trúc thư mục  
11. Kiểm thử  
12. Định hướng phát triển  
13. Giấy phép  
14. Thông tin liên hệ  

---

## 📌 Giới thiệu dự án
**Living Cells** là dự án học phần **Lập trình PHP (Laravel)**, xây dựng hệ thống quản lý rèn luyện – khen thưởng sinh viên, phục vụ công tác quản lý trong nhà trường.

---

## 🔍 Tổng quan dự án
- Quản lý tập trung dữ liệu sinh viên  
- Phân quyền rõ ràng theo từng phòng ban  
- Hỗ trợ đăng ký, điểm danh sự kiện  
- Gợi ý danh hiệu sinh viên dựa trên tiêu chí  

---

## 👥 Đối tượng sử dụng
- Admin  
- Phòng Khảo thí  
- Phòng CTCT-HSSV  
- Văn phòng Đoàn Trường  
- Sinh viên  

---

## ✨ Tính năng chính
- Đăng nhập & phân quyền  
- Quản lý sinh viên  
- Quản lý điểm học tập, điểm rèn luyện  
- Quản lý sự kiện & ngày tình nguyện  
- Quản lý danh hiệu – khen thưởng  
- Import / Export Excel  

---

## 🏗️ Kiến trúc & Thiết kế hệ thống
- Mô hình MVC (Laravel)
- MySQL Database
- Blade Template + Bootstrap

---

## ⚙️ Công nghệ sử dụng
- PHP 8.x
- Laravel 12.x
- MySQL 8.x
- Bootstrap 5
- GitHub, Composer, XAMPP

---

## 🚀 Cài đặt & Thiết lập (Localhost)
```bash
git clone https://github.com/your-username/living-cells.git
cd living-cells
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```
Truy cập: http://127.0.0.1:8000

---

## 🌍 Deploy Hosting (Production)

### ✅ Demo URL
👉 https://quanlydrlktsk.great-site.net/

### Tóm tắt deploy
- Upload source Laravel lên hosting
- Trỏ domain về thư mục `public`
- Cấu hình `.env` (production)
- Set quyền `storage/`, `bootstrap/cache/`
- Kiểm tra CSS/JS và route

---

## 👨‍🎓 Danh sách sinh viên thực hiện

| STT | Họ tên | Mã sinh viên | Lớp |
|-----|--------|--------------|-----|
| 1 | Dương Thị Thu Diểm | 49.01.103.011 | 49.01.SPTIN.B |
| 2 | Dương Hải Đăng | 49.01.103.020 | 49.01.SPTIN.B |
| 3 | Hà Trung Hiếu | 49.01.103.028 | 49.01.SPTIN.B |
| 4 | Võ Quỳnh Như | 49.01.103.059 | 49.01.SPTIN.B |
| 5 | Phan Lê Vy | 49.01.103.097 | 49.01.SPTIN.B |
| 6 | Huỳnh Thị Ái Xuân | 49.01.103.098 | 49.01.SPTIN.B |

---

## 📁 Cấu trúc thư mục
```
app/
config/
database/
public/
resources/
routes/
storage/
tests/
```

---

## 📄 Giấy phép
MIT License

---

## 📬 Thông tin liên hệ
**Nhóm Living Cells**  
Khoa Công nghệ Thông tin – Trường Đại học Sư phạm TP. Hồ Chí Minh
