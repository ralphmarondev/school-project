<?php include "./globals/head.php" ?>

<head>
    <style>
        :root {
            --primary: rgb(174, 14, 14);
            --primary-light: rgb(220, 60, 60);
            --accent: #ff904c;
            --background: #fff7f7;
            --text: #222;
            --border-radius: 12px;
            --box-shadow: 0 2px 12px rgba(174, 14, 14, 0.08);
            --transition: 0.3s cubic-bezier(.25, .8, .25, 1);
        }

        body {
            background: var(--background);
            color: var(--text);
            font-family: 'Poppins', sans-serif;
        }

        .card {
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            max-width: 600px;
            background: #fff;
        }

        @media (min-width: 992px) {
            .card {
                max-width: 900px;
            }
        }

        h2,
        h1,
        h3,
        h4,
        h5,
        h6 {
            color: var(--primary);
            font-weight: bold;
            letter-spacing: 1px;
        }

        .text-primary {
            color: var(--primary) !important;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: none;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background: var(--primary-light);
            color: #fff;
        }

        a {
            color: var(--accent);
            transition: var(--transition);
        }

        a:hover {
            color: var(--primary);
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <?php include "./globals/navbar.php"; ?>
    <!-- Main content -->
    <div id="main">
        <div class="container py-1 mt-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="card shadow-lg border-0 w-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img src="../assets/coea_logo.png" alt="COEA Logo" class="mb-3 rounded-circle border" style="height: 140px; width: 140px; object-fit: cover; background: #f1f1f1;">
                    </div>
                    <h3 class="fw-bold mb-3 text-uppercase text-info">Welcome, Admin!</h3>
                    <p class="mb-4">You have successfully logged in to the C-Trike Predictive Maintenance and Dashboard System.</p>
                    <a href="dashboard.php" class="btn btn-info btn-lg px-4 text-white">Go to Dashboard</a>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <?php include "./globals/scripts.php"; ?>

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

</body>

</html>