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

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .chart-header button {
            font-size: 0.85rem;
            padding: 4px 10px;
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
                    <span id="totalMileage" class="fs-4 text-primary">0 km</span>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card text-center p-3 shadow-sm">
                    <h6>Average per Trip</h6>
                    <span id="avgMileage" class="fs-4 text-primary">0 km</span>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card text-center p-3 shadow-sm">
                    <h6>Trips This Week</h6>
                    <span id="tripsWeek" class="fs-4 text-primary">0</span>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card text-center p-3 shadow-sm">
                    <h6>Max Daily Mileage</h6>
                    <span id="maxTrip" class="fs-4 text-primary">0 km</span>
                </div>
            </div>
        </div>

        <!-- Week Selector -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="weekPicker" class="form-label fw-bold text-primary">Select Week:</label>
                <input type="week" id="weekPicker" class="form-control" value="2025-W42">
            </div>
        </div>

        <!-- Mileage Graph -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm p-3">
                    <div class="chart-header">
                        <h5 id="chartTitle" class="text-primary mb-0">Mileage per Day (km)</h5>
                        <button class="btn btn-outline-primary btn-sm" id="toggleChartType">Switch to Line</button>
                    </div>
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
                        <input type="text" id="tripSearch" placeholder="Search trips..." class="form-control" style="max-width: 300px;">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle" id="tripsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Day</th>
                                    <th>Velocity (km/h)</th>
                                    <th>Time Traveled (h)</th>
                                    <th>Mileage (km)</th>
                                    <th>Route</th>
                                </tr>
                            </thead>
                            <tbody id="tripBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "./globals/scripts.php"; ?>

    <script>
        const ctx = document.getElementById('mileageChart').getContext('2d');
        let mileageChart;
        let chartType = 'bar';
        let telemetryData = null;

        // Fetch telemetry data
        async function fetchTelemetryData() {
            const response = await fetch('./telemetry_data.php');
            const data = await response.json();
            telemetryData = data;
            updateAll();
        }

        function calculateMileage() {
            return telemetryData.speed.map((v, i) => v * telemetryData.time[i]);
        }

        function updateAll() {
            const mileage = calculateMileage();
            renderStats(mileage);
            renderChart(mileage);
            renderTable(mileage);
        }

        function renderStats(mileage) {
            const total = mileage.reduce((a, b) => a + b, 0);
            const avg = total / mileage.length;
            const max = Math.max(...mileage);

            document.getElementById('totalMileage').textContent = `${total.toFixed(1)} km`;
            document.getElementById('avgMileage').textContent = `${avg.toFixed(1)} km`;
            document.getElementById('tripsWeek').textContent = mileage.length;
            document.getElementById('maxTrip').textContent = `${max.toFixed(1)} km`;
        }

        function renderChart(mileage) {
            if (mileageChart) mileageChart.destroy();
            mileageChart = new Chart(ctx, {
                type: chartType,
                data: {
                    labels: telemetryData.labels,
                    datasets: [{
                        label: 'Mileage (km)',
                        data: mileage,
                        backgroundColor: 'rgba(174,14,14,0.6)',
                        borderColor: 'rgb(174,14,14)',
                        fill: true,
                        tension: 0.3
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
        }

        function renderTable(mileage) {
            const tbody = document.getElementById('tripBody');
            tbody.innerHTML = telemetryData.labels.map((day, i) => `
            <tr>
                <td>${day}</td>
                <td>${telemetryData.speed[i]}</td>
                <td>${telemetryData.time[i]}</td>
                <td>${mileage[i].toFixed(1)}</td>
                <td>Sample Route</td>
            </tr>
        `).join('');
        }

        // Chart type toggle
        document.getElementById('toggleChartType').addEventListener('click', () => {
            chartType = chartType === 'bar' ? 'line' : 'bar';
            document.getElementById('toggleChartType').textContent =
                chartType === 'bar' ? 'Switch to Line' : 'Switch to Bar';
            updateAll();
        });

        // Week selector change
        document.getElementById('weekPicker').addEventListener('change', (e) => {
            const week = e.target.value;
            document.getElementById('chartTitle').textContent = `Mileage per Day (km) — ${week}`;
            // Optionally, fetch different week data here later
        });

        // Search filter
        document.getElementById('tripSearch').addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            document.querySelectorAll('#tripBody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
            });
        });

        // Initialize
        fetchTelemetryData();
    </script>
</body>

</html>