function searchFunction() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("search-input");
    filter = input.value.toUpperCase();
    table = document.querySelector(".crud-table");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) {  // Bỏ qua hàng tiêu đề
        let found = false;
        let tds = tr[i].getElementsByTagName("td");

        if (tds.length > 0) {  // Kiểm tra nếu hàng có dữ liệu
            for (let j = 0; j < tds.length; j++) {
                td = tds[j];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            tr[i].style.display = found ? "" : "none";
        }
    }
}