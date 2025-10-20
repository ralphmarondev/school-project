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

        .card-metric {
            height: 100%;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .metric-label {
            font-weight: 600;
            color: var(--primary);
        }

        .metric-value {
            font-size: 1.4rem;
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

        canvas {
            height: 250px !important;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include "./globals/navbar.php"; ?>

    <div id="main" class="container-fluid py-4 mt-5">
        <!-- Top Metric Cards -->
        <div class="row mb-4">
            <div class="col-md-2 mb-2">
                <div class="card card-metric text-center p-3">
                    <h6 class="metric-label">Battery</h6>
                    <span id="battery" class="metric-value">95%</span>
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <div class="card card-metric text-center p-3">
                    <h6 class="metric-label">Vibration</h6>
                    <span id="vibration" class="metric-value">Normal</span>
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <div class="card card-metric text-center p-3">
                    <h6 class="metric-label">Temperature</h6>
                    <span id="temperature" class="metric-value">35°C</span>
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <div class="card card-metric text-center p-3">
                    <h6 class="metric-label">Mileage</h6>
                    <span id="mileage" class="metric-value">120 km</span>
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <div class="card card-metric text-center p-3">
                    <h6 class="metric-label">Tire</h6>
                    <span id="tire" class="metric-value">OK</span>
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <div class="card card-metric text-center p-3">
                    <h6 class="metric-label">Setup</h6>
                    <button class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#setupModal">
                        Set Purchase Dates
                    </button>
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

        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm p-3">
                    <h5 class="text-primary mb-3">Speed per Day (km/h)</h5>
                    <canvas id="speedChart" height="200"></canvas>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm p-3">
                    <h5 class="text-primary mb-3">Time Traveled per Day (hours)</h5>
                    <canvas id="timeChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <?php include "./globals/scripts.php"; ?>

    <!-- Modal for Setup -->
    <div class="modal fade" id="setupModal" tabindex="-1" aria-labelledby="setupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="setupModalLabel">Set Purchase & Installation Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="setupForm">

                        <!-- Tire Row -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-primary">Tire:</label>
                            <div class="row g-2">
                                <div class="col-7">
                                    <input type="date" class="form-control" id="tireDate">
                                </div>
                                <div class="col-5">
                                    <select id="tireCondition" class="form-select">
                                        <option value="new">Brand New</option>
                                        <option value="second">Second Hand</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Battery Row -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-primary">Battery:</label>
                            <div class="row g-2">
                                <div class="col-7">
                                    <input type="date" class="form-control" id="batteryDate">
                                </div>
                                <div class="col-5">
                                    <select id="batteryCondition" class="form-select">
                                        <option value="new">Brand New</option>
                                        <option value="second">Second Hand</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Motor Row -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-primary">Motor:</label>
                            <div class="row g-2">
                                <div class="col-7">
                                    <input type="date" class="form-control" id="motorDate">
                                </div>
                                <div class="col-5">
                                    <select id="motorCondition" class="form-select">
                                        <option value="new">Brand New</option>
                                        <option value="second">Second Hand</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-dismiss="modal" onclick="saveSetup()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Battery Chart
        new Chart(document.getElementById('batteryChart').getContext('2d'), {
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

        // Vibration Chart
        new Chart(document.getElementById('vibrationChart').getContext('2d'), {
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

        // Speed per Day Chart
        new Chart(document.getElementById('speedChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Average Speed (km/h)',
                    data: [45, 50, 48, 52, 55, 60, 58],
                    backgroundColor: 'rgba(174,14,14,0.6)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Time Traveled per Day Chart
        new Chart(document.getElementById('timeChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Time Traveled (hours)',
                    data: [2.5, 3, 2, 4, 3.5, 5, 4.5],
                    backgroundColor: 'rgba(14,14,174,0.6)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        function saveSetup() {
            const setup = {
                tireDate: document.getElementById('tireDate').value,
                tireCondition: document.getElementById('tireCondition').value,
                batteryDate: document.getElementById('batteryDate').value,
                batteryCondition: document.getElementById('batteryCondition').value,
                motorDate: document.getElementById('motorDate').value,
                motorCondition: document.getElementById('motorCondition').value,
            };
            console.log('Saved setup:', setup);
            alert("Setup saved successfully!");
        }
    </script>
</body>

</html>