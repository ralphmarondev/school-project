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

		h3 {
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

		table th {
			color: var(--primary);
			font-weight: 600;
		}

		table td {
			vertical-align: middle;
		}

		.search-container {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 1rem;
			flex-wrap: wrap;
			gap: 0.5rem;
		}

		.search-container input {
			max-width: 300px;
			border-radius: var(--border-radius);
			border: 1px solid #ccc;
			padding: 8px 12px;
			transition: var(--transition);
		}

		.search-container input:focus {
			border-color: var(--primary);
			outline: none;
			box-shadow: 0 0 4px rgba(174, 14, 14, 0.2);
		}

		#main {
			transition: margin-left 0.3s ease, width 0.3s ease;
			margin-left: 250px;
			/* width of your sidebar */
			width: calc(100% - 250px);
		}

		.sidebar.collapsed+#main {
			margin-left: 80px;
			/* when sidebar is collapsed */
			width: calc(100% - 80px);
		}

		@media (max-width: 991px) {
			#main {
				margin-left: 0;
				width: 100%;
			}
		}
	</style>
</head>

<body>
	<?php include "./globals/navbar.php"; ?>

	<div id="main" class="container-fluid py-4 mt-5">
		<div class="card shadow-lg border-0">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
					<h3 class="mb-0">Maintenance Logs</h3>
					<div class="search-container">
						<input type="text" id="searchInput" placeholder="Search logs...">
					</div>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered align-middle text-center" id="logsTable">
						<thead class="table-light">
							<tr>
								<th>#</th>
								<th>Date</th>
								<th>Equipment</th>
								<th>Technician</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<!-- Sample rows (replace with PHP loop) -->
							<tr>
								<td>1</td>
								<td>2025-10-20</td>
								<td>Motor Controller</td>
								<td>John Doe</td>
								<td><span class="badge bg-success">Completed</span></td>
								<td><button class="btn btn-info btn-sm text-white view-btn" data-id="1">View</button></td>
							</tr>
							<tr>
								<td>2</td>
								<td>2025-10-18</td>
								<td>Battery Pack</td>
								<td>Jane Smith</td>
								<td><span class="badge bg-warning text-dark">Pending</span></td>
								<td><button class="btn btn-info btn-sm text-white view-btn" data-id="2">View</button></td>
							</tr>
							<tr>
								<td>3</td>
								<td>2025-10-15</td>
								<td>Sensor Module</td>
								<td>Alex Cruz</td>
								<td><span class="badge bg-danger">Failed</span></td>
								<td><button class="btn btn-info btn-sm text-white view-btn" data-id="3">View</button></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- VIEW MODAL -->
	<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content border-0 shadow-lg">
				<div class="modal-header bg-danger text-white">
					<h5 class="modal-title" id="viewModalLabel">Maintenance Log Details</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<table class="table table-borderless">
						<tr>
							<th>Date:</th>
							<td id="logDate">—</td>
						</tr>
						<tr>
							<th>Equipment:</th>
							<td id="logEquipment">—</td>
						</tr>
						<tr>
							<th>Technician:</th>
							<td id="logTechnician">—</td>
						</tr>
						<tr>
							<th>Status:</th>
							<td id="logStatus">—</td>
						</tr>
						<tr>
							<th>Remarks:</th>
							<td id="logRemarks">—</td>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<?php include "./globals/scripts.php"; ?>

	<script>
		// Example data (replace with PHP or AJAX data)
		const logs = {
			1: {
				date: '2025-10-20',
				equipment: 'Motor Controller',
				technician: 'John Doe',
				status: 'Completed',
				remarks: 'Replaced faulty connector and tested successfully.'
			},
			2: {
				date: '2025-10-18',
				equipment: 'Battery Pack',
				technician: 'Jane Smith',
				status: 'Pending',
				remarks: 'Awaiting spare parts for replacement.'
			},
			3: {
				date: '2025-10-15',
				equipment: 'Sensor Module',
				technician: 'Alex Cruz',
				status: 'Failed',
				remarks: 'Sensor module short-circuited due to overvoltage.'
			}
		};

		// View modal logic
		document.querySelectorAll('.view-btn').forEach(btn => {
			btn.addEventListener('click', () => {
				const id = btn.getAttribute('data-id');
				const log = logs[id];

				document.getElementById('logDate').textContent = log.date;
				document.getElementById('logEquipment').textContent = log.equipment;
				document.getElementById('logTechnician').textContent = log.technician;
				document.getElementById('logStatus').textContent = log.status;
				document.getElementById('logRemarks').textContent = log.remarks;

				new bootstrap.Modal(document.getElementById('viewModal')).show();
			});
		});

		// Search filter
		document.getElementById('searchInput').addEventListener('keyup', function() {
			const filter = this.value.toLowerCase();
			const rows = document.querySelectorAll('#logsTable tbody tr');
			rows.forEach(row => {
				const text = row.textContent.toLowerCase();
				row.style.display = text.includes(filter) ? '' : 'none';
			});
		});
	</script>
</body>

</html>