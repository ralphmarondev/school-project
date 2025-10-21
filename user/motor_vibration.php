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
		<!-- Top Motor Vibration Metrics -->
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

		<!-- Vibration Graph -->
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

		<!-- Vibration Details Table -->
		<div class="row">
			<div class="col-12">
				<div class="card shadow-sm p-3">
					<h5 class="text-primary mb-3">Vibration Details</h5>
					<div class="table-responsive">
						<table class="table table-bordered text-center align-middle" id="vibrationTable">
							<thead class="table-light">
								<tr>
									<th>Day</th>
									<th>Average (Hz)</th>
									<th>Min (Hz)</th>
									<th>Max (Hz)</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody id="vibrationBody">
								<!-- Populated by JS -->
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php include "./globals/scripts.php"; ?>

	<script>
		const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
		let chartType = 'bar';

		// Generate sample vibration data for each day
		let vibrationData = days.map(() => ({
			readings: Array.from({
				length: 5
			}, () => (Math.random() * 2 + 2).toFixed(2)) // 2–4 Hz
		}));

		function getDailyAverages() {
			return vibrationData.map(d =>
				d.readings.reduce((a, b) => a + parseFloat(b), 0) / d.readings.length
			);
		}

		function getStatus(avg) {
			if (avg < 2.5) return 'Normal';
			if (avg < 3.5) return 'Warning';
			return 'Critical';
		}

		// Update cards
		function updateSummary() {
			const averages = getDailyAverages();
			const allReadings = vibrationData.flatMap(d => d.readings.map(Number));
			const avgVibration = (allReadings.reduce((a, b) => a + b, 0) / allReadings.length).toFixed(2);
			const maxVibration = Math.max(...allReadings).toFixed(2);

			document.getElementById('avgVibration').textContent = avgVibration + ' Hz';
			document.getElementById('maxVibration').textContent = maxVibration + ' Hz';
			document.getElementById('sampleCount').textContent = allReadings.length;

			const status = getStatus(avgVibration);
			const statusElem = document.getElementById('status');
			statusElem.textContent = status;
			statusElem.className = 'fs-4 ' + (status === 'Normal' ? 'text-success' : status === 'Warning' ? 'text-warning' : 'text-danger');
		}

		// Chart setup
		const ctx = document.getElementById('vibrationChart').getContext('2d');
		let vibrationChart = createChart(chartType);

		function createChart(type) {
			return new Chart(ctx, {
				type: type,
				data: {
					labels: days,
					datasets: [{
						label: 'Average Vibration (Hz)',
						data: getDailyAverages(),
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

		// Toggle chart type
		document.getElementById('toggleChartType').addEventListener('click', () => {
			chartType = chartType === 'bar' ? 'line' : 'bar';
			vibrationChart.destroy();
			vibrationChart = createChart(chartType);
			document.getElementById('toggleChartType').textContent =
				chartType === 'bar' ? 'Switch to Line' : 'Switch to Bar';
		});

		// Update week
		document.getElementById('weekPicker').addEventListener('change', (e) => {
			const week = e.target.value;
			document.getElementById('chartTitle').textContent = `Motor Vibration per Day (Hz) — ${week}`;
			vibrationData = days.map(() => ({
				readings: Array.from({
					length: 5
				}, () => (Math.random() * 2 + 2).toFixed(2))
			}));
			vibrationChart.data.datasets[0].data = getDailyAverages();
			vibrationChart.update();
			updateSummary();
			renderTable();
		});

		// Render table
		function renderTable() {
			const tbody = document.getElementById('vibrationBody');
			tbody.innerHTML = '';
			days.forEach((day, i) => {
				const readings = vibrationData[i].readings.map(Number);
				const avg = (readings.reduce((a, b) => a + b, 0) / readings.length).toFixed(2);
				const min = Math.min(...readings).toFixed(2);
				const max = Math.max(...readings).toFixed(2);
				const status = getStatus(avg);

				tbody.innerHTML += `
                    <tr>
                        <td>${day}</td>
                        <td>${avg}</td>
                        <td>${min}</td>
                        <td>${max}</td>
                        <td><span class="badge bg-${status === 'Normal' ? 'success' : status === 'Warning' ? 'warning text-dark' : 'danger'}">${status}</span></td>
                    </tr>`;
			});
		}

		// Initialize
		updateSummary();
		renderTable();
	</script>
</body>

</html>