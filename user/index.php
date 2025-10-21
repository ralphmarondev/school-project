<?php include "./globals/head.php"; ?>

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: rgb(174, 14, 14);
            --primary-light: rgb(220, 60, 60);
            --accent: #ff904c;
            --background: #fff7f7;
            --text: #222;
            --border-radius: 12px;
            --box-shadow: 0 2px 12px rgba(174, 14, 14, 0.08);
        }

        body {
            background: var(--background);
            color: var(--text);
            font-family: 'Poppins', sans-serif;
        }

        #main {
            transition: margin-left 0.3s ease, width 0.3s ease;
            margin-left: 250px;
            width: calc(100% - 250px);
        }

        @media (max-width: 991px) {
            #main {
                margin-left: 0;
                width: 100%;
            }
        }

        .card {
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            background: #fff;
            transition: 0.3s;
        }

        h5,
        h6 {
            color: var(--primary);
            font-weight: bold;
        }

        .card-fixed {
            height: 130px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .chart-card {
            height: 280px;
        }

        .text-muted {
            font-size: 0.85rem;
        }
    </style>
</head>

<body>
    <?php include "./globals/navbar.php"; ?>

    <div id="main" class="container-fluid py-4 mt-5">

        <!-- Greeting -->
        <?php
        $username = $_SESSION['username'] ?? 'User';
        $hour = date('H');
        $greeting = $hour < 12 ? "Good Morning, $username!" : ($hour < 18 ? "Good Afternoon, $username!" :
            "Good Evening, $username!");
        ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm p-3 text-center">
                    <h3 class="text-primary mb-1"><?= $greeting ?></h3>
                    <p class="text-muted mb-0">Here’s a quick overview of your vehicle’s current health.</p>
                </div>
            </div>
        </div>

        <!-- Today's Metrics -->
        <div class="row mb-4" id="topMetrics"></div>

        <!-- Mini Trend Charts -->
        <div class="row mb-4" id="chartsContainer"></div>

        <!-- Maintenance Alerts -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-primary mb-0">Recent Maintenance Alerts</h5>
                        <input type="text" id="alertsSearch" placeholder="Search alerts..." class="form-control w-auto">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle" id="alertsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Component</th>
                                    <th>Severity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2025-10-20</td>
                                    <td>Battery</td>
                                    <td><span class="badge bg-warning text-dark">Warning</span></td>
                                    <td>Check connectors</td>
                                </tr>
                                <tr>
                                    <td>2025-10-20</td>
                                    <td>Motor</td>
                                    <td><span class="badge bg-danger">Critical</span></td>
                                    <td>Stop vehicle, inspect motor</td>
                                </tr>
                                <tr>
                                    <td>2025-10-19</td>
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

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            fetch("./telemetry_data.php")
                .then(res => res.json())
                .then(data => {
                    renderTopMetrics(data);
                    renderCharts(data);
                });

            document.getElementById('alertsSearch').addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                document.querySelectorAll('#alertsTable tbody tr').forEach(row => {
                    row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
                });
            });
        });

        function renderTopMetrics(data) {
            const lastIndex = data.labels.length - 1;
            const metrics = [{
                    label: 'Battery',
                    value: `${data.battery[lastIndex]} V`,
                    desc: 'Current voltage'
                },
                {
                    label: 'Motor Status',
                    value: 'Normal',
                    desc: 'No vibration anomalies'
                },
                {
                    label: 'Mileage',
                    value: `${Math.round(data.speed[lastIndex] * data.time[lastIndex])} km`,
                    desc: 'Today’s distance'
                },
                {
                    label: 'Temperature',
                    value: `${data.temperature[lastIndex]}°C`,
                    desc: 'Current sensor temp'
                },
                {
                    label: 'Tire',
                    value: 'OK',
                    desc: 'Pressure stable'
                }
            ];

            const container = document.getElementById("topMetrics");
            container.innerHTML = metrics.map(m => `
        <div class="col-md-2 col-6 mb-2">
            <div class="card text-center p-3 shadow-sm card-fixed">
                <h6>${m.label}</h6>
                <span class="fs-4">${m.value}</span>
                <small class="text-muted">${m.desc}</small>
            </div>
        </div>
    `).join("");
        }

        function renderCharts(data) {
            const charts = [{
                    id: 'speedChart',
                    label: 'Speed Trend (Last 7 Days)',
                    values: data.speed,
                    color: 'rgb(0,128,0)'
                },
                {
                    id: 'batteryChart',
                    label: 'Battery Voltage (Last 7 Days)',
                    values: data.battery,
                    color: 'rgb(174,14,14)'
                }
            ];

            const container = document.getElementById("chartsContainer");
            container.innerHTML = charts.map(c => `
        <div class="col-lg-6 col-12 mb-3">
            <div class="card shadow-sm p-3 chart-card">
                <h5 class="text-primary mb-3">${c.label}</h5>
                <canvas id="${c.id}"></canvas>
            </div>
        </div>
    `).join("");

            charts.forEach(c => {
                new Chart(document.getElementById(c.id).getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: c.label,
                            data: c.values,
                            borderColor: c.color,
                            backgroundColor: c.color.replace('rgb', 'rgba').replace(')', ',0.2)'),
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: false
                            }
                        }
                    }
                });
            });
        }
    </script>
</body>

</html>