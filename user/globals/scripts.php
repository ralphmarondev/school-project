<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>


<script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.1.8/af-2.7.0/b-3.1.2/b-colvis-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/date-1.5.4/fc-5.0.4/fh-4.0.1/kt-2.12.1/r-3.0.3/rg-1.5.1/rr-1.5.0/sc-2.4.3/sb-1.8.1/sp-2.3.3/sl-2.1.0/sr-1.4.1/datatables.min.js"></script>

<!-- Sidebar Toggle Script -->
<script>
    const toggleBtn = document.getElementById("toggleSidebar");
    const sidebar = document.getElementById("sidebar");
    const main = document.getElementById("main");
    const topbar = document.getElementById("topbar");

    toggleBtn.addEventListener("click", () => {
        const isSmallScreen = window.innerWidth < 992;

        if (isSmallScreen) {
            sidebar.classList.toggle("show");
            main.classList.toggle("shift");
            topbar.classList.toggle("shift");
        } else {
            sidebar.classList.toggle("hide");
            main.classList.toggle("shrink");
            topbar.classList.toggle("shrink");
        }
    });
</script>