# Hệ thống Quản lý Lịch Thi Sinh viên

## 📝 Giới thiệu

Dự án xây dựng một hệ thống web giúp sinh viên đăng ký lịch thi online, đồng thời hỗ trợ giảng viên và quản trị viên tổ chức và quản lý kỳ thi một cách hiệu quả. Hệ thống hỗ trợ phân bổ phòng thi, gửi thông báo, và xuất danh sách thi nhanh chóng.

## 👤 Đối tượng sử dụng

- **Sinh viên**: Đăng nhập, chọn lịch thi phù hợp với học phần đã đăng ký.
- **Quản trị viên**: Tạo và quản lý lịch thi, phòng thi, sinh viên, cán bộ coi thi(giảng viên), học phần sẽ thi, xử lý sự cố hệ thống và xuất thống kê.

## ⚙️ Chức năng chính

- **Quản lý lịch thi**
  - Tạo lịch thi với thông tin: ngày, giờ, môn thi, giám thị, địa điểm.
  - Cập nhật hoặc xóa lịch thi không còn hiệu lực.
  - Tạo thời gian đăng kí lịch thi.

- **Đăng ký lịch thi**
  - Sinh viên lựa chọn lịch thi theo học phần.
  - Kiểm tra điều kiện đăng ký (môn học tiên quyết, giới hạn số lượng).
  - Ngăn đăng ký nếu đã quá hạn hoặc quá số lượng cho phép.

- **Quản lý phòng thi**
  - Tự động phân bổ phòng dựa theo số lượng sinh viên.
  - 
- **Quản lý sinh viên, giảng viên, học phần sẽ thi **
  - CRUD sinh viên, giảng viên, học phần sẽ thi, có thể import danh sách từ file 

- **Báo cáo & thống kê**
  - Xuất danh sách phòng thi, sinh viên theo ca thi.

## 🔁 Luồng hoạt động

1. Quản trị viên tạo các lịch thi và cấu hình thông tin liên quan.
2. Sinh viên đăng nhập và xem danh sách các lịch thi mở.
3. Sinh viên đăng ký lịch thi phù hợp với học phần đã học.
4. Hệ thống kiểm tra điều kiện và xác nhận đăng ký.
5. Trước kỳ thi, hệ thống gửi nhắc lịch cho sinh viên.
6. Sau thi, giảng viên có thể tiếp tục nhập điểm và kết nối với hệ thống điểm (nếu tích hợp).

## 🛠️ Công nghệ sử dụng

- Backend: PHP
- Frontend: HTML, CSS, JavaScript
- Cơ sở dữ liệu: MySQL
- Hệ thống chạy local qua XAMPP hoặc triển khai lên hosting.

