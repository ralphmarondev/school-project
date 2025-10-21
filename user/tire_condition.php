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
			height: 260px !important;
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
		<!-- Tire Stats Cards -->
		<div class="row mb-4">
			<div class="col-md-3 mb-2">
				<div class="card text-center p-3">
					<h6>Average Pressure</h6>
					<span id="avgPressure" class="fs-4 text-primary">-- PSI</span>
				</div>
			</div>
			<div class="col-md-3 mb-2">
				<div class="card text-center p-3">
					<h6>Average Tread Depth</h6>
					<span id="avgTread" class="fs-4 text-primary">-- mm</span>
				</div>
			</div>
			<div class="col-md-3 mb-2">
				<div class="card text-center p-3">
					<h6>Status</h6>
					<span id="status" class="fs-4 text-success">Normal</span>
				</div>
			</div>
			<div class="col-md-3 mb-2">
				<div class="card text-center p-3">
					<h6>Total Checks</h6>
					<span id="checkCount" class="fs-4 text-primary">--</span>
				</div>
			</div>
		</div>

		<!-- Week Selector -->
		<div class="row mb-3">
			<div class="col-md-3">
				<label for="weekPicker" class="form-label fw-bold text-primary">Select Week:</label>
				<input type="week" id="weekPicker" class="form-control" value="2025-W43">
			</div>
		</div>

		<!-- Tire Pressure Chart -->
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

		<!-- Tire Condition Table -->
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
		let chartType = 'bar';
		let tireChart;
		let tireData = [];

		// --- Helper Functions ---
		function getStatus(p, t) {
			if (p >= 32 && t >= 4) return 'Normal';
			if ((p >= 28 && p < 32) || (t >= 2 && t < 4)) return 'Warning';
			return 'Replace Soon';
		}

		function getColorClass(status) {
			if (status === 'Normal') return 'text-success';
			if (status === 'Warning') return 'text-warning';
			return 'text-danger';
		}

		function getDateRangeFromWeek(weekString) {
			const [year, weekNum] = weekString.split('-W').map(Number);
			const monday = new Date(year, 0, (weekNum - 1) * 7 + 1);
			while (monday.getDay() !== 1) monday.setDate(monday.getDate() - 1);
			const sunday = new Date(monday);
			sunday.setDate(monday.getDate() + 6);
			return {
				start: monday.toISOString().split('T')[0],
				end: sunday.toISOString().split('T')[0]
			};
		}

		// --- Fetch Tire Data ---
		async function loadTireData(weekValue) {
			const {
				start,
				end
			} = getDateRangeFromWeek(weekValue);
			document.getElementById('chartTitle').textContent = `Tire Pressure per Day (PSI) — ${start} to ${end}`;

			try {
				const res = await fetch(`telemetry_data.php?start=${start}&end=${end}`);
				const data = await res.json();

				// Map fetched tire pressure data
				tireData = data.labels.map((day, i) => ({
					day: day,
					pressure: parseFloat(data.tire[i] ?? 30),
					tread: (Math.max(2, 5 - i * 0.3)).toFixed(1) // simulated tread wear
				}));

				updateDashboard();
				renderTable();
				updateChart();
			} catch (err) {
				console.error("Error loading tire data:", err);
			}
		}

		// --- Update Summary Cards ---
		function updateDashboard() {
			if (!tireData.length) return;
			const avgPressure = (tireData.reduce((a, b) => a + parseFloat(b.pressure), 0) / tireData.length).toFixed(1);
			const avgTread = (tireData.reduce((a, b) => a + parseFloat(b.tread), 0) / tireData.length).toFixed(1);

			document.getElementById('avgPressure').textContent = `${avgPressure} PSI`;
			document.getElementById('avgTread').textContent = `${avgTread} mm`;
			document.getElementById('checkCount').textContent = tireData.length;

			const status = getStatus(avgPressure, avgTread);
			const statusElem = document.getElementById('status');
			statusElem.textContent = status;
			statusElem.className = 'fs-4 ' + getColorClass(status);
		}

		// --- Render Chart ---
		function updateChart() {
			const ctx = document.getElementById('tireChart').getContext('2d');
			if (tireChart) tireChart.destroy();

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
		}

		// --- Render Table ---
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
						<td class="${getColorClass(status)} fw-bold">${status}</td>
					</tr>`;
			});
		}

		// --- Search Filter ---
		document.getElementById('tireSearch').addEventListener('keyup', function() {
			const filter = this.value.toLowerCase();
			document.querySelectorAll('#tireTable tbody tr').forEach(row => {
				row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
			});
		});

		// --- Toggle Chart Type ---
		document.getElementById('toggleChartType').addEventListener('click', () => {
			chartType = chartType === 'bar' ? 'line' : 'bar';
			document.getElementById('toggleChartType').textContent =
				chartType === 'bar' ? 'Switch to Line' : 'Switch to Bar';
			updateChart();
		});

		// --- Week Change ---
		document.getElementById('weekPicker').addEventListener('change', (e) => {
			loadTireData(e.target.value);
		});

		// --- Initial Load ---
		loadTireData(document.getElementById('weekPicker').value);
	</script>
</body>

</html>