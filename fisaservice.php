<?php
$invoice_number = 371232;
include "./config/sqlconnect.php";
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
    <title>DeForge ERP Fisa Service</title>
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
    include "./config/sqlconnect.php";
    include "./templates/navbar.php";

    $sql = "SELECT * FROM detalii_firma";
    $result = $connect->query($sql);
    ?>
    <section class="container w-50 align-items-start">

        <div class="container title-container">
            <h1 class="text-title-container">Fise Helion Security</h1>
            <p class="text-title-container">Proces verbal de predare-primire
            </p>
            <p class="text-title-container">Nr. Fisa/P.V: S - <strong><?php echo $invoice_number; ?></strong>
            </p>
            <h3>Service fara Contract</h3>
        </div>
        <div class="container d-flex justify-content-start gap-3">
            <div class="w-33 h-25">
                <h5 class="test">Detalii firma:</h5>
                <input type="text" class="form-control mb-2" placeholder="Cauta firma ...">

                <div class="overflow-auto scroll-box">

                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<input class='form-check-input' type='checkbox'>
                    <div class='form-check-label'>
                        <strong>$row[nume_firma]</strong><br>
                        <p>CUI: $row[cui]</p>
                    </div>";
                    }

                    ?>

                </div>
            </div>
            <div class="w-33 gap-2">
                <h5 class="test">Detalii firma:</h5>
                <input type="text" class="form-control mb-2" placeholder="Cauta firma ...">
            </div>
            <div class="w-33 gap-2">
                <h5 class="test">Detalii firma:</h5>
                <input type="text" class="form-control mb-2" placeholder="Cauta firma ...">
            </div>
        </div>

    </section>



</body>

</html>