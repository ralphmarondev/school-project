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

        h3,
        h5 {
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

        table th {
            color: var(--primary);
            font-weight: 600;
        }

        table td {
            vertical-align: middle;
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

        /* Graph canvas consistent height */
        canvas {
            height: 250px !important;
            /* fixed height for all charts */
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include "./globals/navbar.php"; ?>

    <div id="main" class="container-fluid py-4 mt-5">
        <!-- Top Metrics Cards -->
        <div class="row mb-4">
            <div class="col-md-2 mb-2">
                <div class="card text-center p-3 shadow-sm">
                    <h6>Battery</h6>
                    <span id="battery" class="fs-4">95%</span>
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <div class="card text-center p-3 shadow-sm">
                    <h6>Vibration</h6>
                    <span id="vibration" class="fs-4">Normal</span>
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <div class="card text-center p-3 shadow-sm">
                    <h6>Temperature</h6>
                    <span id="temperature" class="fs-4">35°C</span>
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <div class="card text-center p-3 shadow-sm">
                    <h6>Mileage</h6>
                    <span id="mileage" class="fs-4">120 km</span>
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <div class="card text-center p-3 shadow-sm">
                    <h6>Tire</h6>
                    <span id="tire" class="fs-4">OK</span>
                </div>
            </div>
        </div>

        <!-- Graphs -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm p-3">
                    <h5 class="text-primary mb-3">Battery Voltage (V)</h5>
                    <canvas id="batteryChart" height="200"></canvas>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm p-3">
                    <h5 class="text-primary mb-3">Motor Vibration (Hz)</h5>
                    <canvas id="vibrationChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Alerts / Predictive Maintenance -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-primary mb-0">Predictive Maintenance Alerts</h5>
                        <div class="search-container">
                            <input type="text" id="alertsSearch" placeholder="Search alerts...">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle" id="alertsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Time</th>
                                    <th>Component</th>
                                    <th>Severity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2025-10-20 10:30</td>
                                    <td>Battery</td>
                                    <td><span class="badge bg-warning text-dark">Warning</span></td>
                                    <td>Check connectors</td>
                                </tr>
                                <tr>
                                    <td>2025-10-20 09:50</td>
                                    <td>Motor</td>
                                    <td><span class="badge bg-danger">Critical</span></td>
                                    <td>Stop vehicle, inspect motor</td>
                                </tr>
                                <tr>
                                    <td>2025-10-19 16:15</td>
                                    <td>Tire</td>
                                    <td><span class="badge bg-success">Normal</span></td>
                                    <td>None</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php include "./globals/scripts.php"; ?>

    <!-- Chart.js scripts -->
    <script>
        const batteryCtx = document.getElementById('batteryChart').getContext('2d');
        const batteryChart = new Chart(batteryCtx, {
            type: 'line',
            data: {
                labels: ['10:00', '10:05', '10:10', '10:15', '10:20'],
                datasets: [{
                    label: 'Battery Voltage (V)',
                    data: [48, 47.8, 47.5, 47.3, 47.0],
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

        const vibrationCtx = document.getElementById('vibrationChart').getContext('2d');
        const vibrationChart = new Chart(vibrationCtx, {
            type: 'line',
            data: {
                labels: ['10:00', '10:05', '10:10', '10:15', '10:20'],
                datasets: [{
                    label: 'Motor Vibration (Hz)',
                    data: [2.1, 2.3, 2.0, 2.5, 2.4],
                    borderColor: 'rgb(14,14,174)',
                    backgroundColor: 'rgba(14,14,174,0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Alerts search filter
        document.getElementById('alertsSearch').addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#alertsTable tbody tr');
            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>

</html>