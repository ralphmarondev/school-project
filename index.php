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
            --accent: #ff904c;
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
            background: var(--background);
            color: var(--text);
            overflow-x: hidden;
        }

        header {
            width: 100%;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }

        header img {
            height: 50px;
        }

        header button {
            background: var(--primary);
            border: none;
            color: #fff;
            font-size: 1rem;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        header button:hover {
            background: var(--primary-light);
        }

        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
            padding: 0 1rem;
            background: linear-gradient(180deg, #fff 0%, #fff7f7 100%);
        }

        main img {
            width: 120px;
            margin-bottom: 1rem;
        }

        h1 {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        p {
            font-size: 1.1rem;
            color: #444;
            max-width: 600px;
            margin-bottom: 2rem;
        }

        .cta-btn {
            background: var(--primary);
            color: #fff;
            font-size: 1.1rem;
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .cta-btn:hover {
            background: var(--primary-light);
        }

        footer {
            text-align: center;
            padding: 1rem;
            font-size: 0.9rem;
            color: #777;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 1.8rem;
            }

            p {
                font-size: 1rem;
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
        <h1>C-Trike Predictive Maintenance</h1>
        <p>Monitor your electric trike's health, performance, and maintenance predictions — all in one intelligent dashboard.</p>
        <button class="cta-btn" onclick="window.location.href='login/'">Get Started</button>
    </main>

    <footer>
        &copy; <?php echo date('Y'); ?> C-Trike Predictive Maintenance System. All rights reserved.
    </footer>

</body>

</html>