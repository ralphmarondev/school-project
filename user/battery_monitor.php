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
			height: 100%;
		}

		h3,
		h5 {
			color: var(--primary);
			font-weight: bold;
			text-transform: uppercase;
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

		<!-- Top Battery Metrics -->
		<div class="row mb-4">
			<div class="col-md-3 mb-2">
				<div class="card text-center p-3 shadow-sm h-100">
					<h6>Average Voltage</h6>
					<span id="avgVoltage" class="fs-4">-- V</span>
				</div>
			</div>
			<div class="col-md-3 mb-2">
				<div class="card text-center p-3 shadow-sm h-100">
					<h6>Max Voltage</h6>
					<span id="maxVoltage" class="fs-4">-- V</span>
				</div>
			</div>
			<div class="col-md-3 mb-2">
				<div class="card text-center p-3 shadow-sm h-100">
					<h6>Min Voltage</h6>
					<span id="minVoltage" class="fs-4">-- V</span>
				</div>
			</div>
			<div class="col-md-3 mb-2">
				<div class="card text-center p-3 shadow-sm h-100">
					<h6>Status</h6>
					<span id="batteryStatus" class="fs-4">--</span>
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

		<!-- Voltage Graph -->
		<div class="row mb-4">
			<div class="col-12">
				<div class="card shadow-sm p-3">
					<div class="chart-header">
						<h5 id="chartTitle" class="text-primary mb-0">Battery Voltage per Day (V)</h5>
						<button class="btn btn-outline-primary btn-sm" id="toggleChartType">Switch to Line</button>
					</div>
					<canvas id="voltageChart"></canvas>
				</div>
			</div>
		</div>
	</div>

	<?php include "./globals/scripts.php"; ?>

	<script>
		const weekPicker = document.getElementById('weekPicker');
		let chartType = 'bar';
		let batteryData = [];

		async function loadBatteryData(week) {
			try {
				const [year, weekNum] = week.split('-W').map(Number);
				const monday = getDateOfISOWeek(weekNum, year);
				const sunday = new Date(monday);
				sunday.setDate(monday.getDate() + 6);

				const start = monday.toISOString().split('T')[0];
				const end = sunday.toISOString().split('T')[0];

				const response = await fetch(`./telemetry_data.php?start=${start}&end=${end}`);
				const data = await response.json();

				batteryData = data.labels.map((day, i) => ({
					day,
					voltage: data.battery[i] ?? 0
				}));

				updateMetrics();
				updateChart();
			} catch (err) {
				console.error('Failed to load battery data:', err);
			}
		}

		function getDateOfISOWeek(week, year) {
			const simple = new Date(year, 0, 1 + (week - 1) * 7);
			const dow = simple.getDay();
			const ISOweekStart = simple;
			if (dow <= 4) ISOweekStart.setDate(simple.getDate() - simple.getDay() + 1);
			else ISOweekStart.setDate(simple.getDate() + 8 - simple.getDay());
			return ISOweekStart;
		}

		function getStatus(voltage) {
			if (voltage >= 60) return 'Normal';
			if (voltage >= 45) return 'Warning';
			return 'Critical';
		}

		function updateMetrics() {
			const voltages = batteryData.map(d => d.voltage);
			if (!voltages.length) return;

			const avg = voltages.reduce((a, b) => a + b, 0) / voltages.length;
			const max = Math.max(...voltages);
			const min = Math.min(...voltages);

			document.getElementById('avgVoltage').textContent = avg.toFixed(2) + ' V';
			document.getElementById('maxVoltage').textContent = max.toFixed(2) + ' V';
			document.getElementById('minVoltage').textContent = min.toFixed(2) + ' V';

			const status = getStatus(avg);
			const elem = document.getElementById('batteryStatus');
			elem.textContent = status;
			elem.className = 'fs-4 ' + (status === 'Normal' ? 'text-success' : status === 'Warning' ? 'text-warning' : 'text-danger');
		}

		const ctx = document.getElementById('voltageChart').getContext('2d');
		let voltageChart = createChart(chartType);

		function createChart(type) {
			return new Chart(ctx, {
				type,
				data: {
					labels: [],
					datasets: [{
						label: 'Voltage (V) [Min: 30, Max: 72]',
						data: [],
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
							beginAtZero: false,
							min: 30,
							max: 72
						}
					}
				}
			});
		}

		function updateChart() {
			voltageChart.data.labels = batteryData.map(d => d.day);
			voltageChart.data.datasets[0].data = batteryData.map(d => d.voltage);
			voltageChart.update();
		}

		document.getElementById('toggleChartType').addEventListener('click', () => {
			chartType = chartType === 'bar' ? 'line' : 'bar';
			voltageChart.destroy();
			voltageChart = createChart(chartType);
			updateChart();
			document.getElementById('toggleChartType').textContent =
				chartType === 'bar' ? 'Switch to Line' : 'Switch to Bar';
		});

		weekPicker.addEventListener('change', (e) => {
			const week = e.target.value;
			document.getElementById('chartTitle').textContent = `Battery Voltage per Day (V) — ${week}`;
			loadBatteryData(week);
		});

		loadBatteryData(weekPicker.value);
	</script>
</body>

</html>