document.getElementById("search-input").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase().trim();
    let keywords = filter.split(" "); // Tách từ khóa thành từng từ riêng biệt
    let rows = document.querySelectorAll(".crud-table tbody tr");

    rows.forEach(row => {
        let msv = row.cells[2].textContent.toLowerCase();
        let name = row.cells[3].textContent.toLowerCase();

        // Kiểm tra nếu tất cả từ khóa đều xuất hiện trong mã sinh viên hoặc tên
        let match = keywords.every(keyword => msv.includes(keyword) || name.includes(keyword));

        row.style.display = match ? "" : "none"; // Ẩn hoặc hiện hàng
    });
}); 