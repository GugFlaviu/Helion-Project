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


    ?>
    <section class="container w-50 align-items-start">

        <div class="container title-container ">
            <h1 class="text-title-container">FISA HELION SECURITY</h1>
            <p class="text-title-container">Proces verbal de predare-primire
            </p>
            <p class="text-title-container">Nr. Fisa/P.V: S - <strong><?php echo $invoice_number; ?></strong>
            </p>
            <h3>Service fara Contract</h3>
        </div>
        <br>
        <div class="container d-flex justify-content-start gap-3">
            <div class="w-33 h-25  ">
                <h3>Detalii firma:</h3>
                <input type="text" class="form-control mb-2" placeholder="Cauta firma ...">

                <div class="overflow-auto  " style="height: 250px;">

                    <?php
                    $sql = "SELECT * FROM detalii_firma";
                    $result = $connect->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<input class='form-check-input' type='checkbox'>
                    <div class='form-check-label '>
                        <strong>$row[nume_firma]</strong><br>
                        <p>CUI: $row[cui]</p>
                    </div><hr>";
                    }

                    ?>

                </div>
            </div>
            <div class="w-33 gap-2 pb-4 ">
                <h3>Punct de lucru:</h3>
                <input type="text" class="form-control mb-2" placeholder="Cauta punct de lucru ...">
                <div class="overflow-auto  " style="height: 250px;">

                    <?php
                    $sql = "SELECT * FROM punct_lucru";
                    $result = $connect->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<input class='form-check-input' type='checkbox'>
                    <div class='form-check-label '>
                        <strong>$row[nume]</strong><br>
                        <p>ZONA: $row[zona]</p>
                        <p>Oras: $row[oras]</p>
                        <p>Adresa: $row[adresa]</p>
                    </div><hr>";
                    }

                    ?>

                </div>
            </div>
            <div class="w-33 gap-2">
                <h3>Reprezentat:</h3>
                <div class="" style="height: 250px;">

                    <?php
                    $sql = "SELECT * FROM reprezentant";
                    $result = $connect->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<input class='form-check-input' type='checkbox'>
                                <div class='form-check-label '>
                                    <strong>$row[nume]</strong><br>
                                    <p>Telefon: 0$row[telefon]</p>
                                    <p>Email: $row[email]</p>
                                    <p>Functie: $row[functie]</p>
                                </div><hr>";
                    }

                    ?>
                    <input class='form-check-input' type='checkbox'>
                    <div class='form-check-label '>
                        <strong>Alta Persoana</strong><br>

                    </div>
                    <hr>
                </div>
            </div>

        </div>
        <hr>
        <br>
        <div class="container d-flex justify-content-start gap-3">
            <div class="w-33 h-25  ">
                <h3>Tip Sistem</h3>


                <div class="overflow-auto  " style="height: 250px;">

                    <?php
                    $sql = "SELECT * FROM tip_sistem";
                    $result = $connect->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<input class='form-check-input' type='checkbox'>
                    <div class='form-check-label '>
                        <strong>$row[nume]</strong><br>
                      
                    </div><hr>";
                    }

                    ?>

                </div>
            </div>
            <div class="container">
                <div class="row align-items-start">
                    <div class="col-md-6 mb-3">
                        <h3>Punctul detine jurnal?</h3>

                        <select class="form-select w-50" id="jurnal">
                            <option>Alege</option>
                            <option>Da</option>
                            <option>Nu</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="formFile" class="form-label">Ataseaza poza</label>
                        <input class="form-control mb-2" type="file" id="formFile">
                        <button class="btn btn-primary" id="clearFile">Sterge poza</button>
                    </div>

                </div>

            </div>


            <br>
        </div>
        <hr>
        <br>
        <div class="container d-flex justify-content-start gap-3">
            <div class="w-33 h-25  ">
                <h3>Defect semnalat
                    :</h3>


                <div class="overflow-auto  " style="height: 150px;">

                    <textarea class="form-control" aria-label="With textarea"></textarea>

                </div>
            </div>
            <div class="w-33 h-25  ">
                <h3>Contestatie
                    :</h3>


                <div class="overflow-auto  " style="height: 150px;">

                    <textarea class="form-control" aria-label="With textarea"></textarea>

                </div>
            </div>


        </div>
        <hr>
        <br>
        <div class="container d-flex justify-content-start gap-3">
            <div class="w-33 h-25  ">
                <h3>Info Service
                    :</h3>
                <div class="input-group ">
                    <span class="input-group-text w-50">Preluat in service</span>
                    <select class="form-select w-50" id="jurnal">
                        <option>Alege</option>
                        <option>Nu</option>
                        <option>Da</option>

                    </select>
                </div>
                <div class="input-group ">
                    <span class="input-group-text w-50">Aparat reparat</span>
                    <select class="form-select w-50" id="jurnal">
                        <option>Alege</option>
                        <option>Nu</option>
                        <option>Da</option>

                    </select>
                </div>
                <div class="input-group">
                    <span class="input-group-text w-50" style="color:grey">Pret estimativ</span>
                    <span class="input-group-text w-50" style="color:grey">Ex: 150 lei</span>
                </div>
            </div>
            <div class="w-33 h-25  ">
                <h3>OPERAȚII:</h3>
                <div class="overflow-auto  " style="height: 150px;">
                    <textarea class="form-control" aria-label="With textarea"></textarea>
                </div>
            </div>
            <div class="w-33 h-25  ">
                <h3>Consum:</h3>
                <div class="overflow-auto  " style="height: 150px;">
                    <textarea class="form-control" aria-label="With textarea"></textarea>
                </div>
            </div>

        </div>
        <hr>
        <br>
        <div class="container d-flex justify-content-start gap-3">
            <div class="w-33 h-25  ">
                <h3>Recomandari
                </h3>
                <div class="overflow-auto  " style="height: 150px;">
                    <textarea class="form-control" aria-label="With textarea"></textarea>
                </div>


            </div>
            <div class="w-33 gap-2 pb-4 ">
                <h3>Inginer helion :</h3>
                <input type="text" class="form-control mb-2" placeholder="Cauta punct de lucru ...">
                <div class="overflow-auto  " style="height: 250px;">

                    <?php
                    $sql = "SELECT * FROM inginer";
                    $result = $connect->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<input class='form-check-input' type='checkbox'>
                    <div class='form-check-label '>
                        <strong>$row[nume]</strong><br>
                        <p>Functie: $row[functie]</p>
                       
                    </div><hr>";
                    }

                    ?>

                </div>
            </div>
            <div class="w-33 gap-2">
                <h3>Reprezentat:</h3>
                <div class="" style="height: 250px;">


                    <input class='form-check-input' type='checkbox'>
                    <div class='form-check-label '>
                        <strong>Alta Persoana</strong><br>

                    </div>
                    <hr>
                </div>
            </div>

        </div>
        <hr>
        <br>
        <div class="container d-flex justify-content-start gap-3">
            <div class="w-50 h-25 ">
                <h3>Data Predarii:</h3>
                <div class="overflow-auto">
                    <input type="date" class="form-control">
                    <div class="input-group flex-nowrap mt-3 m-0 ">
                        <p class="input-group-text w-100 m-0  " style="color:gray;">
                            Timp transport (spre și de la obiectiv)
                        </p>
                    </div>

                    <div class="input-group mb-3 m-0 ">
                        <input type="text" class="form-control m-0  " placeholder="Ex:20">
                        <span class="input-group-text w-50 m-0  ">min</span>
                    </div>
                </div>
                <div class="overflow-auto">

                    <div class="input-group flex-nowrap mt-3 m-0 ">
                        <p class="input-group-text w-100 m-0  " style="color:gray;">
                            Kilometri parcursi spre si de la obiectiv
                        </p>
                    </div>

                    <div class="input-group mb-3 m-0 ">
                        <input type="text" class="form-control m-0  " placeholder="Ex:110">
                        <span class="input-group-text w-50 m-0  ">km</span>
                    </div>
                </div>
                <div class="overflow-auto">

                    <div class="input-group flex-nowrap mt-3 m-0 ">
                        <p class="input-group-text w-100 m-0  " style="color:gray;">
                            Timp manopera
                        </p>
                    </div>

                    <div class="input-group mb-3 m-0 ">
                        <input type="text" class="form-control m-0  " placeholder="Ex:10">
                        <span class="input-group-text w-50 m-0  ">min</span>
                    </div>
                </div>
            </div>


        </div>



        </div>

    </section>

    <script>
        document.getElementById("clearFile").addEventListener("click", function (e) {
            e.preventDefault(); // Prevent form submission if inside a form
            document.getElementById("formFile").value = "";
        });
    </script>
    <footer style="height:100px;"></footer>
</body>

</html>