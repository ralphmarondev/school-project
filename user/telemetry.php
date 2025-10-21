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

        .card-metric {
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

        .toggle-btn {
            float: right;
            font-size: 0.85rem;
            padding: 3px 8px;
        }

        #weekRangeLabel {
            font-weight: bold;
            color: var(--primary);
            margin-top: 8px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include "./globals/navbar.php"; ?>

    <div id="main" class="container-fluid py-4 mt-5">
        <!-- Metric Cards -->
        <div class="row mb-4">
            <div class="col-md-2 mb-2">
                <div class="card card-metric text-center p-3">
                    <h6 class="metric-label">Battery</h6>
                    <span id="batteryMetric" class="metric-value">--</span>
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <div class="card card-metric text-center p-3">
                    <h6 class="metric-label">Vibration</h6>
                    <span id="vibrationMetric" class="metric-value">--</span>
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <div class="card card-metric text-center p-3">
                    <h6 class="metric-label">Temperature</h6>
                    <span id="temperatureMetric" class="metric-value">--</span>
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <div class="card card-metric text-center p-3">
                    <h6 class="metric-label">Mileage</h6>
                    <span id="mileageMetric" class="metric-value">--</span>
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <div class="card card-metric text-center p-3">
                    <h6 class="metric-label">Tire</h6>
                    <span id="tireMetric" class="metric-value">--</span>
                </div>
            </div>
            <div class="col-md-2 mb-2">
                <div class="card card-metric text-center p-3">
                    <h6 class="metric-label">Setup</h6>
                    <button class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#setupModal">Set Purchase Dates</button>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label for="weekPicker" class="form-label fw-bold text-primary">Select Week:</label>
                <input type="week" id="weekPicker" class="form-control" value="2025-W42">
                <div id="weekRangeLabel"></div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm p-3">
                    <h5 class="text-primary mb-3">
                        Battery Voltage (V)
                        <button class="btn btn-outline-primary btn-sm toggle-btn" id="toggleBattery">Bar</button>
                    </h5>
                    <canvas id="batteryChart"></canvas>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm p-3">
                    <h5 class="text-primary mb-3">
                        Motor Vibration (Hz)
                        <button class="btn btn-outline-primary btn-sm toggle-btn" id="toggleVibration">Bar</button>
                    </h5>
                    <canvas id="vibrationChart"></canvas>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm p-3">
                    <h5 class="text-primary mb-3">
                        Speed per Day (km/h)
                        <button class="btn btn-outline-primary btn-sm toggle-btn" id="toggleSpeed">Bar</button>
                    </h5>
                    <canvas id="speedChart"></canvas>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm p-3">
                    <h5 class="text-primary mb-3">
                        Time Traveled per Day (hours)
                        <button class="btn btn-outline-primary btn-sm toggle-btn" id="toggleTime">Bar</button>
                    </h5>
                    <canvas id="timeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <?php include "./globals/scripts.php"; ?>

    <script>
        let charts = {};
        const ctx = {
            battery: document.getElementById('batteryChart').getContext('2d'),
            vibration: document.getElementById('vibrationChart').getContext('2d'),
            speed: document.getElementById('speedChart').getContext('2d'),
            time: document.getElementById('timeChart').getContext('2d')
        };

        // Load immediately
        document.addEventListener('DOMContentLoaded', loadTelemetryData);
        document.getElementById('weekPicker').addEventListener('change', loadTelemetryData);

        function loadTelemetryData() {
            const weekValue = document.getElementById('weekPicker').value;
            const [year, week] = weekValue.split('-W');
            const startDate = getWeekStartDate(year, week);
            const endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + 6);

            const startStr = startDate.toISOString().split('T')[0];
            const endStr = endDate.toISOString().split('T')[0];

            document.getElementById('weekRangeLabel').innerText =
                `Week of ${startStr} to ${endStr}`;

            fetch(`./telemetry_data.php?start=${startStr}&end=${endStr}`)
                .then(res => res.json())
                .then(data => {
                    console.log('Telemetry Data:', data); // Debug check
                    renderAllCharts(data);
                    updateMetrics(data);
                })
                .catch(err => console.error('Error loading telemetry data:', err));
        }

        function getWeekStartDate(year, week) {
            const simple = new Date(year, 0, 1 + (week - 1) * 7);
            const dow = simple.getDay();
            const start = simple;
            if (dow <= 4) start.setDate(simple.getDate() - simple.getDay() + 1);
            else start.setDate(simple.getDate() + 8 - simple.getDay());
            return start;
        }

        function renderAllCharts(data) {
            const config = [{
                    key: 'battery',
                    label: 'Battery Voltage (V)',
                    color: 'rgb(174,14,14)'
                },
                {
                    key: 'vibration',
                    label: 'Motor Vibration (Hz)',
                    color: 'rgb(14,14,174)'
                },
                {
                    key: 'speed',
                    label: 'Average Speed (km/h)',
                    color: 'rgb(174,14,14)'
                },
                {
                    key: 'time',
                    label: 'Time Traveled (hours)',
                    color: 'rgb(14,14,174)'
                }
            ];

            config.forEach(item => {
                const typeBtn = document.getElementById(`toggle${capitalize(item.key)}`);
                const type = typeBtn.textContent.toLowerCase() === 'bar' ? 'bar' : 'line';
                if (charts[item.key]) charts[item.key].destroy();

                charts[item.key] = new Chart(ctx[item.key], {
                    type,
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: item.label,
                            data: data[item.key],
                            backgroundColor: item.color.replace('rgb', 'rgba').replace(')', ',0.6)'),
                            borderColor: item.color,
                            borderWidth: 2,
                            fill: true
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

                typeBtn.onclick = () => {
                    typeBtn.textContent = typeBtn.textContent === 'Bar' ? 'Line' : 'Bar';
                    renderAllCharts(data);
                };
            });
        }

        function updateMetrics(data) {
            document.getElementById('batteryMetric').innerText = data.battery.length ? `${data.battery.at(-1)} V` : '--';
            document.getElementById('vibrationMetric').innerText = data.vibration.length ? `${data.vibration.at(-1)} Hz` : '--';
            document.getElementById('temperatureMetric').innerText = data.temperature.battery.length ? `${data.temperature.battery.at(-1)}°C` : '--';
            document.getElementById('mileageMetric').innerText = data.speed.length ? `${data.speed.at(-1)} km/h` : '--';
            document.getElementById('tireMetric').innerText = data.tire.length ? `${data.tire.at(-1)} PSI` : '--';
        }

        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    </script>
</body>

</html>