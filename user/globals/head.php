<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-2.0.8/af-2.7.0/b-3.0.2/b-colvis-3.0.2/b-html5-3.0.2/b-print-3.0.2/cr-2.0.3/date-1.5.2/fc-5.0.1/fh-4.0.1/kt-2.12.1/r-3.0.2/rg-1.5.0/rr-1.5.0/sc-2.4.3/sb-1.7.1/sp-2.3.1/sl-2.0.3/sr-1.4.1/datatables.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            background-color: #fff;
        }

        /* Sidebar */
        .sidebar {
            background-color: rgba(0, 128, 174, 1);
            color: #fff;
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            transition: transform 0.3s ease;
        }

        .pagination,
        .dt-info {
            margin-top: 10px;
            ;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.4);
            color: #fff;
        }

        .sidebar-header {
            padding: 1rem;
            font-weight: bold;
            text-align: center;
            color: #fff;
            background-color: rgba(0, 128, 174, 1);
        }

        /* Main content */
        #main {
            margin-left: 250px;
            margin-top: 5px;
            padding: 1rem;
            transition: margin-left 0.3s ease;
        }

        /* Top navbar */
        .navbar-custom {
            margin-left: 250px;
            height: 56px;
            background-color: rgba(0, 96, 174, 1);
            color: #fff;
            border-bottom: 1px solid #dee2e6;
            z-index: 1030;
            position: fixed;
            top: 0;
            width: calc(100% - 250px);
            transition: margin-left 0.3s ease;
        }

        #toggleSidebar {
            z-index: 1051;
        }

        /* Hide sidebar initially on small screens */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            #main {
                margin-left: 0;
            }

            #main.shift {
                margin-left: 250px;
            }

            .navbar-custom {
                margin-left: 0;
                width: 100%;
            }

            .navbar-custom.shift {
                margin-left: 250px;
                width: calc(100% - 250px);
            }
        }

        /* On large screens, allow toggling too */
        @media (min-width: 992px) {
            .sidebar.hide {
                transform: translateX(-100%);
            }

            #main.shrink {
                margin-left: 0;
            }

            .navbar-custom.shrink {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
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
    </style>
    <style>
        .dt-buttons {
            width: auto;
        }

        .buttons-excel {
            background: rgb(0, 154, 16);
            width: 100px;
            margin: 10px 10px;
        }

        .buttons-print {
            background: rgba(1, 77, 94, 0.52);
            width: 100px;
            margin: 10px 10px;
        }

        .buttons-colvis {

            width: auto;
            height: auto;
            margin: 10px 10px;
        }

        .buttons-pdf {
            background: rgb(202, 8, 8);
            width: 100px;
            margin: 10px 10px;
        }

        .add_case {
            background: rgb(9, 93, 220);
            color: white;
            width: 100px;
            margin: 10px 10px;
        }

        .add_case:hover {
            background: rgb(0, 32, 77);
        }

        .buttons-excel:hover {
            background: rgb(0, 77, 8);
        }

        .buttons-print:hover {
            background: rgb(0, 54, 69);
        }

        .buttons-pdf:hover {
            background: rgb(153, 6, 6);
        }

        .dropdown {
            position: relative;
            display: inline-block;
            z-index: 100;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            left: 100%;
            background-color: #fff;
            border: 1px solid var(--bs-info);
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            padding: 12px 16px;
            z-index: 100;
        }

        .dropdown-content::before {
            content: "";
            position: absolute;
            top: 40%;
            z-index: -10;
            left: -5%;
            transform: translate(-50%, -50%);
            height: 15px;
            width: 15px;
            background: var(--bs-info);
            transform: rotate(45deg);
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-item:hover {
            color: var(--bs-info);
        }
    </style>
    <style>
        .dt-search {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-bottom: 12px;
        }

        div.dt-container .dt-search input {

            outline: none;

        }

        div.dt-container .dt-search input:focus {

            border: 1px solid var(--bs-info);
        }

        div.dtsb-searchBuilder button.dtsb-button {
            background: cadetblue;
            color: #fff;
        }

        div.dtsb-searchBuilder div.dtsb-group div.dtsb-logicContainer button.dtsb-logic {
            color: #000;
        }

        div.dt-length {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control::before,
        table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control::before {
            margin-right: .5em;
            display: inline-block;
            box-sizing: border-box;
            content: "~";
            border-top: 0px solid transparent;
            border-left: 0px solid rgba(0, 0, 0, 0.5);
            border-bottom: 0px solid transparent;
            border-right: 0px solid transparent;
        }

        table.dataTable.dtr-inline.collapsed>tbody>tr.dtr-expanded>td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed>tbody>tr.dtr-expanded>th.dtr-control:before {
            border-top: 0px solid rgba(0, 0, 0, 0.5);
            border-left: 5px solid transparent;
            border-bottom: 0px solid transparent;
            border-right: 5px solid transparent;
        }

        td {
            font-size: 12px;
            /* font-style: italic; */
        }

        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control:before {
            background-color: var(--bs-info);
            border: .15em solid #fff;
            border-radius: 1em;
            box-shadow: 0 0 .2em #444;
            box-sizing: content-box;
            color: #fff;
            content: "~";
            display: block;
            font-family: Courier New, Courier, monospace;
            height: 1em;
            left: 5px;
            line-height: 1em;
            margin-top: -9px;
            position: absolute;
            text-align: center;
            text-indent: 0 !important;
            top: 50%;
            width: 1em;
        }
    </style>

    <script>
        $('div.dtsb-searchBuilder div.dtsb-titleRow').text("Advance Search")
        $('.add_case').css({
            "background": "#3b7ddd",
            "color": "white",

        })
    </script>
</head>