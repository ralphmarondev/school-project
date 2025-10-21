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
		<!-- Top Tire Condition Cards -->
		<div class="row mb-4">
			<div class="col-md-3 mb-2">
				<div class="card text-center p-3 shadow-sm">
					<h6>Average Pressure</h6>
					<span id="avgPressure" class="fs-4">-- PSI</span>
				</div>
			</div>
			<div class="col-md-3 mb-2">
				<div class="card text-center p-3 shadow-sm">
					<h6>Average Tread Depth</h6>
					<span id="avgTread" class="fs-4">-- mm</span>
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
					<h6>Total Checks</h6>
					<span id="checkCount" class="fs-4">--</span>
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
					<h5 class="text-primary mb-3">Tire Condition Details</h5>
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
							<tbody id="tireBody">
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

		// Generate sample data
		let tireData = days.map(() => ({
			pressure: (Math.random() * 6 + 28).toFixed(1), // 28–34 PSI
			tread: (Math.random() * 3 + 2).toFixed(1) // 2–5 mm
		}));

		function getStatus(p, t) {
			if (p >= 32 && t >= 4) return 'Normal';
			if ((p >= 28 && p < 32) || (t >= 2 && t < 4)) return 'Warning';
			return 'Replace Soon';
		}

		// Update summary cards
		function updateSummary() {
			const avgPressure = (tireData.reduce((a, b) => a + parseFloat(b.pressure), 0) / tireData.length).toFixed(1);
			const avgTread = (tireData.reduce((a, b) => a + parseFloat(b.tread), 0) / tireData.length).toFixed(1);
			document.getElementById('avgPressure').textContent = avgPressure + ' PSI';
			document.getElementById('avgTread').textContent = avgTread + ' mm';
			document.getElementById('checkCount').textContent = tireData.length;

			const status = getStatus(avgPressure, avgTread);
			const statusElem = document.getElementById('status');
			statusElem.textContent = status;
			statusElem.className = 'fs-4 ' + (status === 'Normal' ? 'text-success' : status === 'Warning' ? 'text-warning' : 'text-danger');
		}

		// Chart setup
		const ctx = document.getElementById('tireChart').getContext('2d');
		let tireChart = createChart(chartType);

		function createChart(type) {
			return new Chart(ctx, {
				type: type,
				data: {
					labels: days,
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
		}

		// Toggle chart type
		document.getElementById('toggleChartType').addEventListener('click', () => {
			chartType = chartType === 'bar' ? 'line' : 'bar';
			tireChart.destroy();
			tireChart = createChart(chartType);
			document.getElementById('toggleChartType').textContent =
				chartType === 'bar' ? 'Switch to Line' : 'Switch to Bar';
		});

		// Week change
		document.getElementById('weekPicker').addEventListener('change', (e) => {
			const week = e.target.value;
			document.getElementById('chartTitle').textContent = `Tire Pressure per Day (PSI) — ${week}`;
			tireData = days.map(() => ({
				pressure: (Math.random() * 6 + 28).toFixed(1),
				tread: (Math.random() * 3 + 2).toFixed(1)
			}));
			tireChart.data.datasets[0].data = tireData.map(d => d.pressure);
			tireChart.update();
			updateSummary();
			renderTable();
		});

		// Render table
		function renderTable() {
			const tbody = document.getElementById('tireBody');
			tbody.innerHTML = '';
			days.forEach((day, i) => {
				const {
					pressure,
					tread
				} = tireData[i];
				const status = getStatus(pressure, tread);
				tbody.innerHTML += `
                    <tr>
                        <td>${day}</td>
                        <td>${pressure}</td>
                        <td>${tread}</td>
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