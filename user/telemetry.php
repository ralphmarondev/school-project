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
                    <canvas id="batteryChart"></canvas>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm p-3">
                    <h5 class="text-primary mb-3">Motor Vibration (Hz)</h5>
                    <canvas id="vibrationChart"></canvas>
                </div>
            </div>
        </div>

        <!-- NEW CHARTS: Speed Per Day & Time Traveled Per Day -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm p-3">
                    <h5 class="text-primary mb-3">Speed per Day (km/h)</h5>
                    <canvas id="speedPerDayChart"></canvas>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm p-3">
                    <h5 class="text-primary mb-3">Time Traveled per Day (hrs)</h5>
                    <canvas id="timePerDayChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <?php include "./globals/scripts.php"; ?>

    <script>
        // ================== Battery Chart ==================
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

        // ================== Vibration Chart ==================
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

        // ================== Speed Per Day Chart ==================
        const speedCtx = document.getElementById('speedPerDayChart').getContext('2d');
        const speedChart = new Chart(speedCtx, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Speed (km/h)',
                    data: [55, 60, 52, 63, 58, 65, 59],
                    backgroundColor: 'rgba(174,14,14,0.6)',
                    borderColor: 'rgb(174,14,14)',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Speed (km/h)'
                        }
                    }
                }
            }
        });

        // ================== Time Traveled Per Day Chart ==================
        const timeCtx = document.getElementById('timePerDayChart').getContext('2d');
        const timeChart = new Chart(timeCtx, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Time (hours)',
                    data: [2, 2.5, 3, 1.5, 2.2, 3.5, 2.8],
                    backgroundColor: 'rgba(14,14,174,0.6)',
                    borderColor: 'rgb(14,14,174)',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Hours'
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>