<?php


?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DeForge ERP</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/homestylesheet.css">
</head>

<body>
    <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <?php
    include "templates/navbar.php";
    include "./config/sqlconnect.php";
    ?>
    <section class="container w-50">

        <div class="container title-container">
            <h1 class="text-title-container">Fise Helion Security</h1>
            <h5 class="text-title-container">Perioada : 2025-04-09</h5>
        </div>
        <div class="row g-2 mb-4 year-buttons">

            <div class="col-6 col-md-2">
                <div class="dropdown w-100">
                    <button class="btn btn-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                        Selectează
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Opțiune 1</a></li>
                        <li><a class="dropdown-item" href="#">Opțiune 2</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-6 col-md-2"><button class="btn btn-primary w-100">2025</button></div>
            <div class="col-6 col-md-2"><button class="btn btn-primary w-100">2024</button></div>
            <div class="col-6 col-md-2"><button class="btn btn-primary w-100">2023</button></div>
            <div class="col-6 col-md-2"><button class="btn btn-primary w-100">2022</button></div>
            <div class="col-6 col-md-2"><button class="btn btn-secondary w-100">Tot</button></div>
            <br>
            <div class="d-flex flex-wrap justify-content-between mb-3" style="border: 1px solid #ccc;">
                <div style="margin: 10px;">
                    Show
                    <select class=" form-select d-inline w-auto mx-1" style="width: auto;">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    entries
                </div>

                <div style="margin: 10px;">
                    Search:
                    <input type="text" class="form-control d-inline w-auto ms-2" style="width: 160px;">
                </div>
            </div>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
</body>

</html>