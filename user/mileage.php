<?php include "./globals/head.php"; ?>

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
            background: #fff;
        }

        h3, h5 {
            color: var(--primary);
            font-weight: bold;
            text-transform: uppercase;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            border-radius: var(--border-radius);
            border: none;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background: var(--primary-light);
        }

        .search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .search-container input {
            max-width: 300px;
            border-radius: var(--border-radius);
            border: 1px solid #ccc;
            padding: 8px 12px;
            transition: var(--transition);
        }

        .search-container input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 4px rgba(174, 14, 14, 0.2);
        }

        #main {
            transition: margin-left 0.3s ease, width 0.3s ease;
            margin-left: 250px;
            width: calc(100% - 250px);
        }

        .sidebar.collapsed+#main {
            margin-left: 80px;
            width: calc(100% - 80px);
        }

        @media (max-width: 991px) {
            #main {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Consistent chart height */
        canvas {
            height: 250px !important;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include "./globals/navbar.php"; ?>

    <div id="main" class="container-fluid py-4 mt-5">
        <!-- Top Mileage Metrics -->
        <div class="row mb-4">
            <div class="col-md-3 mb-2">
                <div class="card text-center p-3 shadow-sm">
                    <h6>Total Mileage</h6>
                    <span id="totalMileage" class="fs-4">1200 km</span>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card text-center p-3 shadow-sm">
                    <h6>Average per Trip</h6>
                    <span id="avgMileage" class="fs-4">35 km</span>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card text-center p-3 shadow-sm">
                    <h6>Trips Today</h6>
                    <span id="tripsToday" class="fs-4">3</span>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card text-center p-3 shadow-sm">
                    <h6>Max Trip</h6>
                    <span id="maxTrip" class="fs-4">80 km</span>
                </div>
            </div>
        </div>

        <!-- Mileage Graph -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm p-3">
                    <h5 class="text-primary mb-3">Mileage Over Time</h5>
                    <canvas id="mileageChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Trip Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-primary mb-0">Trip Details</h5>
                        <div class="search-container">
                            <input type="text" id="tripSearch" placeholder="Search trips...">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle" id="tripsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Distance (km)</th>
                                    <th>Duration</th>
                                    <th>Route</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2025-10-20</td>
                                    <td>40</td>
                                    <td>1h 20m</td>
                                    <td>Downtown – Uptown</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td>2025-10-19</td>
                                    <td>50</td>
                                    <td>1h 45m</td>
                                    <td>Station – Market</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td>2025-10-18</td>
                                    <td>30</td>
                                    <td>1h 10m</td>
                                    <td>Park – Mall</td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "./globals/scripts.php"; ?>

    <script>
        // Chart.js Mileage Graph
        const mileageCtx = document.getElementById('mileageChart').getContext('2d');
        const mileageChart = new Chart(mileageCtx, {
            type: 'line',
            data: {
                labels: ['2025-10-15', '2025-10-16', '2025-10-17', '2025-10-18', '2025-10-19', '2025-10-20'],
                datasets: [{
                    label: 'Distance (km)',
                    data: [30, 25, 40, 30, 50, 40],
                    borderColor: 'rgb(174,14,14)',
                    backgroundColor: 'rgba(174,14,14,0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Trip search filter
        document.getElementById('tripSearch').addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tripsTable tbody tr');
            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>

</html>
