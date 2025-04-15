<?php

include "./config/sqlconnect.php";

$invoice_number = 371232;

function getUserIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    return $_SERVER['REMOTE_ADDR'];
}

$ip = getUserIP();


$apiKey = "acb7be2b776e4def9c31aa84248bbe50"; // Replace with your actual key
$url = "https://api.geoapify.com/v1/ipinfo?apiKey=$apiKey";

$response = file_get_contents($url);
$data = json_decode($response, true);
$lat = $data['location']['latitude'] ?? null;
$lon = $data['location']['longitude'] ?? null;
//echo "Location saved: ($lat, $lon)";

//$googleMapsUrl = "https://www.google.com/maps?q=$lat,$lon";
//echo "Location saved: <a href='$googleMapsUrl' target='_blank'>($lat, $lon)</a>";





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

    include "./templates/navbar.php";
    ?>
    <form id="myForm" class="container w-50 align-items-start" method="POST" action="save_form.php">

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
                        echo "<input class='form-check-input' type='checkbox' name='firma[]' value='{$row['id']}'>
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
                        echo "<input class='form-check-input' type='checkbox' name='punct_lucru[]' value='{$row['id']}'>
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
                        echo "<input class='form-check-input' type='checkbox' name='reprezentant[]' value='{$row['id']}'>
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
                        echo "<input class='form-check-input' type='checkbox' name='tip_sistem[]' value='{$row['id']}'>
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

                        <select class="form-select w-50" id="jurnal" name="jurnal" required>
                            <option>Alege</option>
                            <option>Da</option>
                            <option>Nu</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="poza" class="form-label">Ataseaza poza</label>
                        <input class="form-control mb-2" type="file" id="formFile">
                        <button class="btn btn-primary" id="stergePoza">Sterge poza</button>
                    </div>

                </div>

            </div>


            <br>
        </div>
        <hr>
        <br>
        <div class="container d-flex justify-content-start gap-3">
            <div class="w-33 h-25  ">
                <h3>Defect semnalat:</h3>
                <div class="overflow-auto  " style="height: 150px;">

                    <textarea class="form-control" aria-label="With textarea" name="defect_semnalat"
                        placeholder="Scrie defectul semnalat aici..." required></textarea>

                </div>
            </div>
            <div class="w-33 h-25  ">
                <h3>Constatare
                    :</h3>
                <div class="overflow-auto  " style="height: 150px;">
                    <textarea class="form-control" aria-label="With textarea" name="constatare" required></textarea>
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
                    <select class="form-select w-50" id="jurnal-service" name="preluat_service" required>
                        <option>Alege</option>
                        <option>Nu</option>
                        <option>Da</option>

                    </select>
                </div>
                <div class="input-group ">
                    <span class="input-group-text w-50">Aparat reparat</span>
                    <select class="form-select w-50" id="jurnal-reparat" name="aparat_reparat" required>
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
                    <textarea class="form-control" aria-label="With textarea" required name="operatii"></textarea>
                </div>
            </div>
            <div class="w-33 h-25  ">
                <h3>Consum:</h3>
                <div class="overflow-auto  " style="height: 150px;">
                    <textarea class="form-control" aria-label="With textarea" required name="consum"></textarea>
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
                    <textarea class="form-control" aria-label="With textarea" required name="recomandari"></textarea>
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
                        echo "<input class='form-check-input' type='checkbox' name='inginer[]' value='{$row['id']}'> 
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
                <canvas id="semnatura-reprezentant" width="300" height="200"
                    style="border:1px solid #ccc;"></canvas><br>
                <input type="hidden" name="semnatura_reprezentant" id="semnatura_reprezentant_input">
                <button type="button" onclick="clearPadReprezentant()">Șterge</button>

            </div>

        </div>
        <hr>
        <br>
        <div class="container d-flex justify-content-start gap-3">
            <div class="w-50 h-25 ">
                <h3>Data Predarii:</h3>
                <div class="overflow-auto">
                    <input type="date" class="form-control" name="data_predarii" required>
                    <div class="input-group flex-nowrap mt-3 m-0 ">
                        <p class="input-group-text w-100 m-0  " style="color:gray;">
                            Timp transport (spre și de la obiectiv)
                        </p>
                    </div>

                    <div class="input-group mb-3 m-0 ">
                        <input type="text" class="form-control m-0  " placeholder="Ex:20" name="timp_transport"
                            required>
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
                        <input type="text" class="form-control m-0  " placeholder="Ex:110" name="km_parcursi" required>
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
                        <input type="text" class="form-control m-0  " placeholder="Ex:10" name="timp_manopera" required>
                        <span class="input-group-text w-50 m-0  ">min</span>
                    </div>
                </div>
            </div>


        </div>
        <div class="container d-flex justify-content-start gap-3">
            <div class="w-100 h-25 ">
                <h3>Semnatura:</h3>
                <div class="container">
                    <canvas id="semnatura-client" width="500" height="200" style="border:1px solid #ccc;"></canvas><br>
                    <input type="hidden" name="semnatura_client" id="semnatura_client_input">
                    <div class="container">


                    </div>
                    <button type="button" onclick="clearPad()">Șterge</button>
                    <p>
                        Prin semnarea prezentei Fise de Service Clientul, prin reprezentantul sau, accepta lucrarile,
                        confirma executarea acestora si accepta
                        receptia lor intocmai.

                    </p>

                </div>

            </div>


        </div>
        <div class="container d-flex justify-content-start gap-3">
            <div class="w-100 h-25 ">
                <h3>Observatii din partea persoanei de contact
                    :</h3>
                <div class="overflow-auto  " style="height: 150px;">
                    <textarea class="form-control" aria-label="With textarea"
                        placeholder="Exemplu: Inginerul Helion a intervenit prompt" required
                        name="observatii"></textarea>
                </div>

            </div>


        </div>
        <div class="container d-flex justify-content-start gap-3">
            <div class="w-100 h-25 ">
                <h3>Actiuni:</h3>
                <div class="text-left my-4">
                    <button class="btn btn-success mb-2" type="submit">Finalizează și trimite fișa</button><br>
                    <button class="btn btn-danger">Resetează</button>
                </div>

            </div>


        </div>
        <input type="hidden" name="gps_lat" value="<?php echo $lat; ?>">
        <input type="hidden" name="gps_lng" value="<?php echo $lon; ?>">




    </form>

    <script src=" https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js">
    </script>
    <script>
        document.getElementById("stergePoza").addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("poza").value = "";
        });
        //semnatura pentru reprezentant
        const canvas_reprezentant = document.getElementById('semnatura-reprezentant');
        const signaturePad_Reprezentant = new SignaturePad(canvas_reprezentant, {
            penColor: 'darkblue'
        });

        function clearPadReprezentant() {
            signaturePad_Reprezentant.clear();
        }
        //semnatura client
        const canvas_client = document.getElementById('semnatura-client');
        const signaturePad_Client = new SignaturePad(canvas_client, {
            penColor: 'darkblue'
        });

        function clearPad() {
            signaturePad_Client.clear();
        }
        const form = document.getElementById('myForm');
        const canvasClient = document.getElementById('semnatura-client');
        const canvasReprezentant = document.getElementById('semnatura-reprezentant');



        form.addEventListener('submit', function (e) {
            if (!signaturePad_Client.isEmpty()) {
                const dataURL_Client = signaturePad_Client.toDataURL();
                document.getElementById('semnatura_client_input').value = dataURL_Client;
            }

            if (!signaturePad_Reprezentant.isEmpty()) {
                const dataURL_Reprezentant = signaturePad_Reprezentant.toDataURL();
                document.getElementById('semnatura_reprezentant_input').value = dataURL_Reprezentant;
            }
        });
    </script>

</body>

<footer class="bg-light text-center py-3" style="border-top: 1px #ccc solid;">

    <small>
        Soluție oferită de
        <a href="https://deforce.eu/" target="_blank">DeForce Tehnologic SRL</a>
    </small>
</footer>

</html>