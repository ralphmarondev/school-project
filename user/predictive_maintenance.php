<?php include "./globals/head.php"; ?>

<head>
	<meta charset="UTF-8">
	<title>Predictive Maintenance</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<style>
		:root {
			--primary: rgb(174, 14, 14);
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

		#main {
			margin-left: 250px;
			transition: 0.3s;
			width: calc(100% - 250px);
		}

		.card {
			border-radius: var(--border-radius);
			box-shadow: var(--box-shadow);
			background: #fff;
			margin-bottom: 1rem;
		}

		h5 {
			color: var(--primary);
			font-weight: bold;
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
</head>

<body>
	<?php include "./globals/navbar.php"; ?>

	<div id="main" class="container-fluid py-4 mt-5">
		<div class="row mb-4">
			<div class="col-12">
				<h3 class="text-primary">Predictive Maintenance</h3>
				<p>Monitor alerts and send notifications to responsible personnel.</p>
			</div>
		</div>

		<!-- Alerts Table -->
		<div class="row">
			<div class="col-12">
				<div class="card p-3 shadow-sm">
					<div class="d-flex justify-content-between align-items-center mb-3">
						<h5 class="mb-0">Maintenance Alerts</h5>
						<input type="text" id="alertsSearch" placeholder="Search alerts..." class="form-control w-auto">
					</div>
					<div class="table-responsive">
						<table class="table table-bordered text-center align-middle" id="alertsTable">
							<thead class="table-light">
								<tr>
									<th>Date</th>
									<th>Component</th>
									<th>Severity</th>
									<th>Alert Message</th>
									<th>Send SMS</th>
								</tr>
							</thead>
							<tbody id="alertsBody">
								<!-- Alerts will be loaded dynamically -->
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<h1 class="text-primary">TO BE IMPLEMENTED LATER!</h1>
	</div>

	<?php include "./globals/scripts.php"; ?>

	<script>
		// Example alert data (replace with your API/database)
		const alerts = [{
				date: '2025-10-22',
				component: 'Battery',
				severity: 'Warning',
				message: 'Voltage dropping below safe threshold.'
			},
			{
				date: '2025-10-22',
				component: 'Motor',
				severity: 'Critical',
				message: 'High vibration detected. Inspect immediately!'
			},
			{
				date: '2025-10-21',
				component: 'Tire',
				severity: 'Normal',
				message: 'Pressure stable.'
			}
		];

		function renderAlerts() {
			const tbody = document.getElementById('alertsBody');
			tbody.innerHTML = '';
			alerts.forEach((alert, i) => {
				const severityClass = alert.severity === 'Normal' ? 'status-normal' : (alert.severity === 'Warning' ? 'status-warning' : 'status-critical');
				tbody.innerHTML += `
                    <tr>
                        <td>${alert.date}</td>
                        <td>${alert.component}</td>
                        <td class="${severityClass}">${alert.severity}</td>
                        <td>${alert.message}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="sendSMS(${i})">Send SMS</button>
                        </td>
                    </tr>
                `;
			});
		}

		function sendSMS(index) {
			const alert = alerts[index];
			// Example: replace with actual API call to SMS gateway
			alert('SMS sent: ' + alert.component + ' — ' + alert.message);

			// Optional: mark alert as sent
			document.querySelectorAll('#alertsBody tr')[index].style.backgroundColor = '#e0ffe0';
		}

		// Search filter
		document.getElementById('alertsSearch').addEventListener('keyup', function() {
			const filter = this.value.toLowerCase();
			document.querySelectorAll('#alertsTable tbody tr').forEach(row => {
				row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
			});
		});

		// Initial render
		renderAlerts();
	</script>
</body>

</html>