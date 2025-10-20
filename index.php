

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">

    <title>Login</title>

    <link href="https://demo.adminkit.io/css/light.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
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
            background: #fff;
            color: var(--text);
            font-family: 'Poppins', sans-serif;
        }

        /*
        .btn-primary,
        .btn-main {
            background: var(--primary);
            color: #fff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: none;
            transition: var(--transition);
        }

        .btn-primary:hover,
        .btn-main:hover {
            background: var(--primary-light);
            color: #fff;
        }
        */
    </style>
</head>

<body>
    <main class="d-flex w-100 mt-1">

        <div class="container d-flex flex-column">

            <div class="row ">
                <div class="col-11 col-lg-10 text-center mx-auto mt-1">
                    <!-- <img src="../assets/coea_logo.png" class="mt-3 mb-2" alt="" style="height: 120px; width: 120px; margin: auto; display: block;" class="mb-3"> -->

                    <!-- <img src="https://1000logos.net/wp-content/uploads/2019/03/PNP-Logo.png" alt="" class="mt-3" style="height: 100px; width: 200px;"> -->
                    <img src="./assets/emob_logo_f.png" alt="" style="height: 100px; width: 100px;">
                    <h3 class="" style="  ">C-Trike Predictive Maintenance and Dashboard</h3>

                </div>
                <div class="col-sm-10 col-md-5 col-xl-4 mx-auto d-table ">
                    <div class="d-table-cell align-middle">

                        <div class="text-center ">

                            <h3 class="" style="">Welcome Back!</h3>
                            <p class="lead ">
                                Log in to your account to continue
                            </p>
                        </div>

                        <div class="card border shadow ">
                            <div class="card-body">

                                <div class="m-sm-3">
                              
                                       
                                  
                                    <form method="post" class="" action="">
                                        <div class="mb-3">
                                            <label class="form-label " style="font-weight: bold;">Username</label>
                                            <input class="form-control form-control-lg p-3" type="text" name="username" placeholder="Enter username" required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label " style="font-weight: bold;">Password</label>
                                            <input class="form-control form-control-lg p-3" type="password" name="password" placeholder="Enter password" required />
                                        </div>
                                        <div class="d-grid gap-2 mt-3">
                                            <button type="submit" class="btn p-2 btn-primary">Log in</button>
                                            <button type="submit" class="btn p-2 btn-secondary btn-sm">Register</button>
                                            <p style="color: #001efdff; text-align: center; font-size: 0.7em;">Forgot Password</p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>



</body>

</html>