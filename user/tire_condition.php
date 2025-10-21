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
		<!-- Top Tire Condition Cards -->
		<div class="row mb-4">
			<div class="col-md-3 mb-2">
				<div class="card text-center p-3 shadow-sm d-flex flex-column justify-content-center">
					<h6>Average Pressure</h6>
					<span id="avgPressure" class="fs-4 text-primary">-- PSI</span>
				</div>
			</div>
			<div class="col-md-3 mb-2">
				<div class="card text-center p-3 shadow-sm d-flex flex-column justify-content-center">
					<h6>Average Tread Depth</h6>
					<span id="avgTread" class="fs-4 text-primary">-- mm</span>
				</div>
			</div>
			<div class="col-md-3 mb-2">
				<div class="card text-center p-3 shadow-sm d-flex flex-column justify-content-center">
					<h6>Status</h6>
					<span id="status" class="fs-4 text-success">Normal</span>
				</div>
			</div>
			<div class="col-md-3 mb-2">
				<div class="card text-center p-3 shadow-sm d-flex flex-column justify-content-center">
					<h6>Total Checks</h6>
					<span id="checkCount" class="fs-4 text-primary">--</span>
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

		<!-- Tire Condition Chart -->
		<div class="row mb-4">
			<div class="col-12">
				<div class="card shadow-sm p-3">
					<div class="chart-header">
						<h5 id="chartTitle" class="text-primary mb-0">Tire Pressure per Day (PSI)</h5>
						<button class="btn btn-outline-primary btn-sm" id="toggleChartType">Switch to Line</button>
					</div>
					<canvas id="tireChart"></canvas>
				</div>
			</div>
		</div>

		<!-- Tire Details Table -->
		<div class="row">
			<div class="col-12">
				<div class="card shadow-sm p-3">
					<div class="d-flex justify-content-between align-items-center mb-3">
						<h5 class="text-primary mb-0">Tire Condition Details</h5>
						<input type="text" id="tireSearch" placeholder="Search..." class="form-control" style="max-width: 300px;">
					</div>
					<div class="table-responsive">
						<table class="table table-bordered text-center align-middle" id="tireTable">
							<thead class="table-light">
								<tr>
									<th>Day</th>
									<th>Pressure (PSI)</th>
									<th>Tread Depth (mm)</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody id="tireBody"></tbody>
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
		let tireData = [];

		// Fetch data from telemetry_data.php
		async function loadTelemetryData(week) {
			try {
				const response = await fetch(`./telemetry_data.php?week=${week}`);
				const data = await response.json();

				// Map telemetry data to tire pressure-like data
				tireData = data.labels.map((day, i) => ({
					day: day,
					pressure: (data.vibration[i] * 10 + 28).toFixed(1), // Derived PSI-like value
					tread: (Math.max(2, 5 - i * 0.3)).toFixed(1) // Simulated tread wear
				}));

				updateSummary();
				renderTable();
				updateChart();
			} catch (error) {
				console.error("Failed to load telemetry data:", error);
			}
		}

		function getStatus(p, t) {
			if (p >= 32 && t >= 4) return 'Normal';
			if ((p >= 28 && p < 32) || (t >= 2 && t < 4)) return 'Warning';
			return 'Replace Soon';
		}

		function updateSummary() {
			if (!tireData.length) return;
			const avgPressure = (tireData.reduce((a, b) => a + parseFloat(b.pressure), 0) / tireData.length).toFixed(1);
			const avgTread = (tireData.reduce((a, b) => a + parseFloat(b.tread), 0) / tireData.length).toFixed(1);

			document.getElementById('avgPressure').textContent = `${avgPressure} PSI`;
			document.getElementById('avgTread').textContent = `${avgTread} mm`;
			document.getElementById('checkCount').textContent = tireData.length;

			const status = getStatus(avgPressure, avgTread);
			const statusElem = document.getElementById('status');
			statusElem.textContent = status;
			statusElem.className = 'fs-4 ' + (status === 'Normal' ?
				'text-success' :
				status === 'Warning' ?
				'text-warning' :
				'text-danger');
		}

		// Chart setup
		const ctx = document.getElementById('tireChart').getContext('2d');
		let tireChart = new Chart(ctx, {
			type: chartType,
			data: {
				labels: days,
				datasets: [{
					label: 'Pressure (PSI)',
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
						beginAtZero: true
					}
				}
			}
		});

		function updateChart() {
			tireChart.data.labels = tireData.map(d => d.day);
			tireChart.data.datasets[0].data = tireData.map(d => d.pressure);
			tireChart.update();
		}

		// Toggle chart type
		document.getElementById('toggleChartType').addEventListener('click', () => {
			chartType = chartType === 'bar' ? 'line' : 'bar';
			tireChart.destroy();
			tireChart = new Chart(ctx, {
				type: chartType,
				data: {
					labels: tireData.map(d => d.day),
					datasets: [{
						label: 'Pressure (PSI)',
						data: tireData.map(d => d.pressure),
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
			document.getElementById('toggleChartType').textContent =
				chartType === 'bar' ? 'Switch to Line' : 'Switch to Bar';
		});

		// Render table
		function renderTable() {
			const tbody = document.getElementById('tireBody');
			tbody.innerHTML = '';
			tireData.forEach(({
				day,
				pressure,
				tread
			}) => {
				const status = getStatus(pressure, tread);
				tbody.innerHTML += `
                <tr>
                    <td>${day}</td>
                    <td>${pressure}</td>
                    <td>${tread}</td>
                    <td><span class="badge bg-${status === 'Normal'
                        ? 'success'
                        : status === 'Warning'
                            ? 'warning text-dark'
                            : 'danger'}">${status}</span></td>
                </tr>`;
			});
		}

		// Search filter
		document.getElementById('tireSearch').addEventListener('keyup', function() {
			const filter = this.value.toLowerCase();
			document.querySelectorAll('#tireTable tbody tr').forEach(row => {
				row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
			});
		});

		// Week change
		document.getElementById('weekPicker').addEventListener('change', (e) => {
			const week = e.target.value;
			document.getElementById('chartTitle').textContent = `Tire Pressure per Day (PSI) — ${week}`;
			loadTelemetryData(week);
		});

		// Initialize
		loadTelemetryData(document.getElementById('weekPicker').value);
	</script>
</body>

</html>