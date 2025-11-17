document.addEventListener("DOMContentLoaded", function() {
    const toggle = document.getElementById("sidebarToggle");
    const sidebar = document.querySelector(".sidebar");
    toggle.addEventListener("click", function() {
        sidebar.classList.toggle("collapsed");
        document.querySelector(".main-panel").classList.toggle("expanded");
    });
});
