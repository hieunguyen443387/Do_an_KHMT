function toggle(source) {
    checkboxes = document.getElementsByName('select_all[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
    }
}