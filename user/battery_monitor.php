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

		.status-normal {
			color: green;
			font-weight: bold;
		}

		.status-warning {
			color: orange;
			font-weight: bold;
		}

		.status-critical {
			color: red;
			font-weight: bold;
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
					<span id="batteryStatus" class="fs-4 status-normal">Normal</span>
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

		<!-- Battery Table -->
		<div class="row">
			<div class="col-12">
				<div class="card shadow-sm p-3">
					<div class="d-flex justify-content-between align-items-center mb-3">
						<h5 class="text-primary mb-0">Battery Readings</h5>
						<input type="text" id="batterySearch" placeholder="Search readings..." class="form-control" style="max-width: 300px;">
					</div>
					<div class="table-responsive">
						<table class="table table-bordered text-center align-middle" id="batteryTable">
							<thead class="table-light">
								<tr>
									<th>Day</th>
									<th>Voltage (V)</th>
									<th>Status</th>
									<th>Remarks</th>
								</tr>
							</thead>
							<tbody id="batteryBody"></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php include "./globals/scripts.php"; ?>

	<script>
		const weekPicker = document.getElementById('weekPicker');
		let chartType = 'bar';
		let batteryData = [];

		// Fetch from telemetry_data.php
		async function loadBatteryData(week) {
			try {
				const response = await fetch(`./telemetry_data.php?week=${week}`);
				const data = await response.json();

				// Use battery field (voltage)
				batteryData = data.labels.map((day, i) => ({
					day,
					voltage: data.battery[i]
				}));

				updateMetrics();
				renderTable();
				updateChart();
			} catch (err) {
				console.error('Failed to load battery data:', err);
			}
		}

		function getStatus(voltage) {
			if (voltage >= 12.4) return 'Normal';
			if (voltage >= 12.0) return 'Warning';
			return 'Critical';
		}

		function getStatusClass(status) {
			if (status === 'Normal') return 'status-normal';
			if (status === 'Warning') return 'status-warning';
			return 'status-critical';
		}

		function updateMetrics() {
			const voltages = batteryData.map(d => d.voltage);
			const avg = voltages.reduce((a, b) => a + b, 0) / voltages.length;
			const max = Math.max(...voltages);
			const min = Math.min(...voltages);

			document.getElementById('avgVoltage').textContent = avg.toFixed(2) + ' V';
			document.getElementById('maxVoltage').textContent = max.toFixed(2) + ' V';
			document.getElementById('minVoltage').textContent = min.toFixed(2) + ' V';

			const status = getStatus(avg);
			const elem = document.getElementById('batteryStatus');
			elem.textContent = status;
			elem.className = getStatusClass(status) + ' fs-4';
		}

		// Create chart
		const ctx = document.getElementById('voltageChart').getContext('2d');
		let voltageChart = createChart(chartType);

		function createChart(type) {
			return new Chart(ctx, {
				type,
				data: {
					labels: [],
					datasets: [{
						label: 'Voltage (V)',
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
							min: 45,
							max: 50
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

		function renderTable() {
			const tbody = document.getElementById('batteryBody');
			tbody.innerHTML = '';
			batteryData.forEach(d => {
				const status = getStatus(d.voltage);
				const row = `
					<tr>
						<td>${d.day}</td>
						<td>${d.voltage.toFixed(2)}</td>
						<td class="${getStatusClass(status)}">${status}</td>
						<td>${status === 'Normal' ? 'Stable' : status === 'Warning' ? 'Slight drop' : 'Check immediately'}</td>
					</tr>`;
				tbody.innerHTML += row;
			});
		}

		document.getElementById('batterySearch').addEventListener('keyup', function() {
			const filter = this.value.toLowerCase();
			document.querySelectorAll('#batteryTable tbody tr').forEach(row => {
				row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
			});
		});

		// Initialize
		loadBatteryData(weekPicker.value);
	</script>
</body>

</html>