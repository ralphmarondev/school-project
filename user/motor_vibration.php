<?php include "./globals/head.php"; ?>

<head>
	<style>
		:root {
			--primary: rgb(174, 14, 14);
			--primary-light: rgb(220, 60, 60);
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

		.card {
			border-radius: var(--border-radius);
			box-shadow: var(--box-shadow);
			background: #fff;
		}

		h5 {
			color: var(--primary);
			font-weight: bold;
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
	</style>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
	<?php include "./globals/navbar.php"; ?>

	<div id="main" class="container-fluid py-4 mt-5">
		<!-- Top Motor Metrics -->
		<div class="row mb-4">
			<div class="col-md-3 mb-2">
				<div class="card text-center p-3 shadow-sm">
					<h6>Average Vibration</h6>
					<span id="avgVibration" class="fs-4">-- Hz</span>
				</div>
			</div>
			<div class="col-md-3 mb-2">
				<div class="card text-center p-3 shadow-sm">
					<h6>Max Vibration</h6>
					<span id="maxVibration" class="fs-4">-- Hz</span>
				</div>
			</div>
			<div class="col-md-3 mb-2">
				<div class="card text-center p-3 shadow-sm">
					<h6>Status</h6>
					<span id="status" class="fs-4 text-success">Normal</span>
				</div>
			</div>
			<div class="col-md-3 mb-2">
				<div class="card text-center p-3 shadow-sm">
					<h6>Total Samples</h6>
					<span id="sampleCount" class="fs-4">--</span>
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

		<!-- Vibration Chart -->
		<div class="row mb-4">
			<div class="col-12">
				<div class="card shadow-sm p-3">
					<div class="chart-header">
						<h5 id="chartTitle" class="text-primary mb-0">Motor Vibration per Day (Hz)</h5>
						<button class="btn btn-outline-primary btn-sm" id="toggleChartType">Switch to Line</button>
					</div>
					<canvas id="vibrationChart"></canvas>
				</div>
			</div>
		</div>

		<!-- Data Table -->
		<div class="row">
			<div class="col-12">
				<div class="card shadow-sm p-3">
					<h5 class="text-primary mb-3">Vibration Details</h5>
					<div class="table-responsive">
						<table class="table table-bordered text-center align-middle" id="vibrationTable">
							<thead class="table-light">
								<tr>
									<th>Day</th>
									<th>Vibration (Hz)</th>
									<th>Status</th>
									<th>Remarks</th>
								</tr>
							</thead>
							<tbody id="vibrationBody"></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php include "./globals/scripts.php"; ?>

	<script>
		const ctx = document.getElementById('vibrationChart').getContext('2d');
		let chartType = 'bar';
		let vibrationData = [];
		let days = [];

		function getStatus(value) {
			if (value < 2.5) return 'Normal';
			if (value < 3.5) return 'Warning';
			return 'Critical';
		}

		function getColorClass(status) {
			if (status === 'Normal') return 'text-success';
			if (status === 'Warning') return 'text-warning';
			return 'text-danger';
		}

		function createChart(type, labels, data) {
			return new Chart(ctx, {
				type: type,
				data: {
					labels: labels,
					datasets: [{
						label: 'Vibration (Hz)',
						data: data,
						backgroundColor: 'rgba(14,14,174,0.6)',
						borderColor: 'rgb(14,14,174)',
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

		let vibrationChart = null;

		function updateDashboard(data) {
			days = data.labels;
			vibrationData = data.vibration;

			const avg = vibrationData.reduce((a, b) => a + b, 0) / vibrationData.length;
			const max = Math.max(...vibrationData);
			const count = vibrationData.length;
			const status = getStatus(avg);

			document.getElementById('avgVibration').textContent = avg.toFixed(2) + ' Hz';
			document.getElementById('maxVibration').textContent = max.toFixed(2) + ' Hz';
			document.getElementById('sampleCount').textContent = count;
			document.getElementById('status').textContent = status;
			document.getElementById('status').className = 'fs-4 ' + getColorClass(status);

			renderTable();
			updateChart();
		}

		function updateChart() {
			if (vibrationChart) vibrationChart.destroy();
			vibrationChart = createChart(chartType, days, vibrationData);
		}

		function renderTable() {
			const tbody = document.getElementById('vibrationBody');
			tbody.innerHTML = '';
			days.forEach((day, i) => {
				const val = vibrationData[i];
				const status = getStatus(val);
				tbody.innerHTML += `
					<tr>
						<td>${day}</td>
						<td>${val}</td>
						<td class="${getColorClass(status)} fw-bold">${status}</td>
						<td>${status === 'Normal' ? 'Stable' : status === 'Warning' ? 'Slight anomaly' : 'Check motor'}</td>
					</tr>
				`;
			});
		}

		document.getElementById('toggleChartType').addEventListener('click', () => {
			chartType = chartType === 'bar' ? 'line' : 'bar';
			updateChart();
			document.getElementById('toggleChartType').textContent = chartType === 'bar' ? 'Switch to Line' : 'Switch to Bar';
		});

		document.getElementById('weekPicker').addEventListener('change', e => {
			const week = e.target.value;
			document.getElementById('chartTitle').textContent = `Motor Vibration per Day (Hz) — ${week}`;
			loadTelemetryData();
		});

		function loadTelemetryData() {
			fetch('telemetry_data.php')
				.then(res => res.json())
				.then(updateDashboard)
				.catch(err => console.error('Error loading data:', err));
		}

		loadTelemetryData();
	</script>
</body>

</html>