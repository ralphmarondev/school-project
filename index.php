<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C-Trike Predictive Maintenance</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: rgb(174, 14, 14);
            --primary-light: rgb(220, 60, 60);
            --text: #222;
            --background: #fff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background);
            color: var(--text);
        }

        /* Header */
        header {
            width: 100%;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            border-bottom: 2px solid rgba(0, 0, 0, 0.05);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }

        header img {
            height: 60px;
        }

        header button {
            background: var(--primary);
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
            padding: 0.6rem 1.4rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        header button:hover {
            background: var(--primary-light);
        }

        /* Main Section */
        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding: 6rem 1rem 2rem;
            background: linear-gradient(180deg, #fff 0%, #fff5f5 100%);
        }

        main img {
            width: 110px;
            margin-bottom: 1rem;
        }

        h1 {
            font-size: 2.3rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        p {
            font-size: 1.05rem;
            color: #444;
            max-width: 600px;
            margin-bottom: 2rem;
        }

        .cta-btn {
            background: var(--primary);
            color: #fff;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.9rem 2.4rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s ease;
            box-shadow: 0 4px 10px rgba(174, 14, 14, 0.25);
        }

        .cta-btn:hover {
            background: var(--primary-light);
            box-shadow: 0 6px 12px rgba(174, 14, 14, 0.35);
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 1rem;
            font-size: 0.9rem;
            color: #777;
            background: #fff;
            border-top: 1px solid #eee;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 1.8rem;
            }

            p {
                font-size: 1rem;
            }

            header img {
                height: 45px;
            }
        }
    </style>
</head>

<body>

    <header>
        <img src="./assets/emob_logo_f.png" alt="C-Trike Logo">
        <button onclick="window.location.href='login/'">Login / Sign Up</button>
    </header>

    <main>
        <img src="./assets/emob_logo_f.png" alt="C-Trike Logo">
        <h1>C-Trike Predictive Maintenance System</h1>
        <p>Empowering electric mobility with intelligent performance tracking and predictive maintenance solutions.</p>
        <button class="cta-btn" onclick="window.location.href='login/'">Get Started</button>
    </main>

    <footer>
        &copy; <?php echo date('Y'); ?> C-Trike Predictive Maintenance System. All rights reserved.
    </footer>

</body>

</html>