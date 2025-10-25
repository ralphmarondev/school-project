<?php include "./globals/head.php"; ?>

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: rgb(174, 14, 14);
            --primary-light: rgb(220, 60, 60);
            --accent: #ff904c;
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
            transition: margin-left 0.3s ease, width 0.3s ease;
            margin-left: 250px;
            width: calc(100% - 250px);
        }

        @media (max-width: 991px) {
            #main {
                margin-left: 0;
                width: 100%;
            }
        }

        .card {
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            background: #fff;
            transition: 0.3s;
        }

        h5,
        h6 {
            color: var(--primary);
            font-weight: bold;
        }

        .card-fixed {
            height: 130px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .chart-card {
            height: 280px;
        }

        .text-muted {
            font-size: 0.85rem;
        }
    </style>
</head>

<body>
    <?php include "./globals/navbar.php"; ?>

    <div id="main" class="container-fluid py-4 mt-5">

        <!-- Greeting -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm p-3 text-center">
                    <h3 id="greeting" class="text-primary mb-1"></h3>
                    <p class="text-muted mb-0">Here’s a quick overview of your vehicle’s current health.</p>
                </div>
            </div>
        </div>

        <!-- Today's Metrics -->
        <div class="row mb-4" id="topMetrics"></div>

        <!-- Mini Trend Charts -->
        <div class="row mb-4" id="chartsContainer"></div>

    </div>

    <?php include "./globals/scripts.php"; ?>

    <script>
        // 🧠 Display user greeting using email from localStorage
        document.addEventListener("DOMContentLoaded", () => {
            const email = localStorage.getItem("email") || "User";

            // Optionally, display only the part before '@'
            const namePart = email.includes("@") ? email.split("@")[0] : email;
            console.log(namePart);

            const hour = new Date().getHours();
            let greetingText = "";

            if (hour < 12) {
                greetingText = `Good Morning, ${email}!`;
            } else if (hour < 18) {
                greetingText = `Good Afternoon, ${email}!`;
            } else {
                greetingText = `Good Evening, ${email}!`;
            }

            document.getElementById("greeting").textContent = greetingText;

            // Fetch telemetry data after greeting is displayed
            fetch("./telemetry_data.php")
                .then(res => res.json())
                .then(data => {
                    renderTopMetrics(data);
                    renderCharts(data);
                });
        });

        function renderTopMetrics(data) {
            if (!data.labels || !data.labels.length) return;

            const lastIndex = data.labels.length - 1;
            const metrics = [{
                    label: 'Battery',
                    value: data.battery[lastIndex] !== null ? `${data.battery[lastIndex]} V` : '--',
                    desc: 'Current voltage'
                },
                {
                    label: 'Motor Status',
                    value: 'Normal',
                    desc: 'No vibration anomalies'
                },
                {
                    label: 'Mileage',
                    value: data.speed[lastIndex] !== null && data.time[lastIndex] !== null ?
                        `${Math.round(data.speed[lastIndex] * data.time[lastIndex])} km` : '--',
                    desc: 'Today’s distance'
                },
                {
                    label: 'Temperature',
                    value: data.temperature[lastIndex] !== null ? `${data.temperature[lastIndex]}°C` : '--',
                    desc: 'Current sensor temp'
                },
                {
                    label: 'Tire',
                    value: 'OK',
                    desc: 'Pressure stable'
                }
            ];

            const container = document.getElementById("topMetrics");
            container.innerHTML = metrics
                .map(
                    (m) => `
                <div class="col-md-2 col-6 mb-2">
                    <div class="card text-center p-3 shadow-sm card-fixed">
                        <h6>${m.label}</h6>
                        <span class="fs-4">${m.value}</span>
                        <small class="text-muted">${m.desc}</small>
                    </div>
                </div>
            `
                )
                .join("");
        }

        function renderCharts(data) {
            const charts = [{
                    id: 'speedChart',
                    label: 'Speed Trend (Last 7 Days)',
                    values: data.speed,
                    color: 'rgb(0,128,0)',
                    min: 0,
                    max: Math.max(...data.speed) + 10
                },
                {
                    id: 'batteryChart',
                    label: 'Battery Voltage (Last 7 Days)',
                    values: data.battery,
                    color: 'rgb(174,14,14)',
                    min: 30,
                    max: 72
                }
            ];

            const container = document.getElementById("chartsContainer");
            container.innerHTML = charts
                .map(
                    (c) => `
                <div class="col-lg-6 col-12 mb-3">
                    <div class="card shadow-sm p-3 chart-card">
                        <h5 class="text-primary mb-3">${c.label}</h5>
                        <canvas id="${c.id}"></canvas>
                    </div>
                </div>
            `
                )
                .join("");

            charts.forEach((c) => {
                const validData = c.values.map((v) => (v !== null ? v : null));
                new Chart(document.getElementById(c.id).getContext("2d"), {
                    type: "line",
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: c.label,
                            data: validData,
                            borderColor: c.color,
                            backgroundColor: c.color.replace("rgb", "rgba").replace(")", ",0.2)"),
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                min: c.min,
                                max: c.max
                            }
                        },
                        spanGaps: false
                    }
                });
            });
        }
    </script>
</body>

</html>