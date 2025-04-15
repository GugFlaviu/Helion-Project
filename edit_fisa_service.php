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
$apiKey = "acb7be2b776e4def9c31aa84248bbe50";
$url = "https://api.geoapify.com/v1/ipinfo?apiKey=$apiKey";
$response = file_get_contents($url);
$dataGPS = json_decode($response, true);
$lat = $dataGPS['location']['latitude'] ?? null;
$lon = $dataGPS['location']['longitude'] ?? null;

// Preluare înregistrare pentru factura (presupunem că există un câmp invoice_number în tabelul fisa_service)
include "./config/sqlconnect.php";
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID invalid sau lipsă.");
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM fisa_service WHERE id = $id LIMIT 1";
$result = $connect->query($sql);

if (!$result || $result->num_rows === 0) {
    die("Fișa cu id-ul $id nu a fost găsită.");
}

$data = $result->fetch_assoc();

// Pentru câmpurile cu valori multiple (checkbox-uri), presupunem că ele sunt salvate ca string cu ID-uri separate prin virgulă.
$firma_selected = isset($data['firma_ids']) ? explode(',', $data['firma_ids']) : [];
$punct_selected = isset($data['punct_lucru_ids']) ? explode(',', $data['punct_lucru_ids']) : [];
$reprezentant_sel = isset($data['reprezentant_ids']) ? explode(',', $data['reprezentant_ids']) : [];
$tip_sistem_sel = isset($data['tip_sistem_ids']) ? explode(',', $data['tip_sistem_ids']) : [];
$inginer_selected = isset($data['inginer']) ? explode(',', $data['inginer']) : [];

$jurnal = $data['jurnal'] ?? '';
$defect_semnalat = $data['defect_semnalat'] ?? '';
$constatare = $data['constatare'] ?? '';
$operatii = $data['operatii'] ?? '';
$consum = $data['consum'] ?? '';
$recomandari = $data['recomandari'] ?? '';
$data_predarii = $data['data_predarii'] ?? '';
$timp_transport = $data['timp_transport'] ?? '';
$km_parcursi = $data['km_parcursi'] ?? '';
$timp_manopera = $data['timp_manopera'] ?? '';
$observatii = $data['observatii'] ?? '';
$semnatura_reprezentant = $data['semnatura_reprezentant'];
$semnatura_client = $data['semnatura_client'];
?>

<!DOCTYPE html>
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
    <?php include "./templates/navbar.php"; ?>
    <form id="myForm" class="container w-50 align-items-start" method="POST" action="save_form.php"
        enctype="multipart/form-data">
        <div class="container title-container ">
            <h1 class="text-title-container">FISA HELION SECURITY</h1>
            <p class="text-title-container">Proces verbal de predare-primire</p>
            <p class="text-title-container">Nr. Fisa/P.V: S - <strong><?php echo $invoice_number; ?></strong></p>
            <h3>Service fara Contract</h3>
        </div>
        <br>
        <div class="container d-flex justify-content-start gap-3">
            <!-- Coloană: Detalii firma -->
            <div class="w-33 h-25">
                <h3>Detalii firma:</h3>
                <input type="text" class="form-control mb-2" placeholder="Cauta firma ...">
                <div class="overflow-auto" style="height: 250px;">
                    <?php
                    $sql = "SELECT * FROM detalii_firma";
                    $result = $connect->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        // Preselectăm dacă id-ul firmei este în lista preluată
                        $checked = in_array($row['id'], $firma_selected) ? 'checked' : '';
                        echo "<div class='mb-1'>";
                        echo "<input class='form-check-input' type='checkbox' name='firma[]' value='{$row['id']}' $checked> ";
                        echo "<label class='form-check-label'><strong>{$row['nume_firma']}</strong><br>CUI: {$row['cui']}</label>";
                        echo "</div><hr>";
                    }
                    ?>
                </div>
            </div>
            <!-- Coloană: Punct de lucru -->
            <div class="w-33 gap-2 pb-4">
                <h3>Punct de lucru:</h3>
                <input type="text" class="form-control mb-2" placeholder="Cauta punct de lucru ...">
                <div class="overflow-auto" style="height: 250px;">
                    <?php
                    $sql = "SELECT * FROM punct_lucru";
                    $result = $connect->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $checked = in_array($row['id'], $punct_selected) ? 'checked' : '';
                        echo "<div class='mb-1'>";
                        echo "<input class='form-check-input' type='checkbox' name='punct_lucru[]' value='{$row['id']}' $checked> ";
                        echo "<label class='form-check-label'><strong>{$row['nume']}</strong><br>ZONA: {$row['zona']}<br>Oras: {$row['oras']}<br>Adresa: {$row['adresa']}</label>";
                        echo "</div><hr>";
                    }
                    ?>
                </div>
            </div>
            <!-- Coloană: Reprezentat -->
            <div class="w-33 gap-2">
                <h3>Reprezentat:</h3>
                <div style="height: 250px;">
                    <?php
                    $sql = "SELECT * FROM reprezentant";
                    $result = $connect->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $checked = in_array($row['id'], $reprezentant_sel) ? 'checked' : '';
                        echo "<div class='mb-1'>";
                        echo "<input class='form-check-input' type='checkbox' name='reprezentant[]' value='{$row['id']}' $checked> ";
                        echo "<label class='form-check-label'><strong>{$row['nume']}</strong><br>Telefon: 0{$row['telefon']}<br>Email: {$row['email']}<br>Functie: {$row['functie']}</label>";
                        echo "</div><hr>";
                    }
                    ?>
                    <!-- Opțiunea "Alta Persoana" (poți verifica și aici dacă a fost selectată – se poate salva ca o valoare specială) -->
                    <div class="mb-1">
                        <input class='form-check-input' type='checkbox' name='reprezentant[]' value='alta_persoana'>
                        <label class='form-check-label'><strong>Alta Persoana</strong></label>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
        <hr>
        <br>
        <div class="container d-flex justify-content-start gap-3">
            <!-- Coloană: Tip Sistem -->
            <div class="w-33 h-25">
                <h3>Tip Sistem</h3>
                <div class="overflow-auto" style="height: 250px;">
                    <?php
                    $sql = "SELECT * FROM tip_sistem";
                    $result = $connect->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $checked = in_array($row['id'], $tip_sistem_sel) ? 'checked' : '';
                        echo "<div class='mb-1'>";
                        echo "<input class='form-check-input' type='checkbox' name='tip_sistem[]' value='{$row['id']}' $checked> ";
                        echo "<label class='form-check-label'><strong>{$row['nume']}</strong></label>";
                        echo "</div><hr>";
                    }
                    ?>
                </div>
            </div>
            <div class="container">
                <div class="row align-items-start">
                    <!-- Exemplu select pentru jurnal -->
                    <div class="col-md-6 mb-3">
                        <h3>Punctul detine jurnal?</h3>
                        <select class="form-select w-50" id="jurnal" name="jurnal" required>
                            <option value="" <?php echo ($jurnal == "") ? "selected" : ""; ?>>Alege</option>
                            <option value="Da" <?php echo ($jurnal == "Da") ? "selected" : ""; ?>>Da</option>
                            <option value="Nu" <?php echo ($jurnal == "Nu") ? "selected" : ""; ?>>Nu</option>
                        </select>
                    </div>
                    <!-- Câmpul pentru atașare poză -->
                    <div class="col-md-6 mb-3">
                        <label for="poza" class="form-label">Ataseaza poza</label>
                        <input class="form-control mb-2" type="file" id="poza" name="poza">
                        <button class="btn btn-primary" id="stergePoza">Sterge poza</button>
                    </div>
                </div>
            </div>
            <br>
        </div>
        <hr>
        <br>
        <div class="container d-flex justify-content-start gap-3">
            <!-- Textarea: Defect semnalat -->
            <div class="w-33 h-25">
                <h3>Defect semnalat:</h3>
                <div class="overflow-auto" style="height: 150px;">
                    <textarea class="form-control" aria-label="With textarea" name="defect_semnalat"
                        placeholder="Scrie defectul semnalat aici..."
                        required><?php echo htmlspecialchars($defect_semnalat); ?></textarea>
                </div>
            </div>
            <!-- Textarea: Constatare -->
            <div class="w-33 h-25">
                <h3>Constatare:</h3>
                <div class="overflow-auto" style="height: 150px;">
                    <textarea class="form-control" aria-label="With textarea" name="constatare"
                        required><?php echo htmlspecialchars($constatare); ?></textarea>
                </div>
            </div>
        </div>
        <hr>
        <br>
        <div class="container d-flex justify-content-start gap-3">
            <!-- Coloana: Info Service -->
            <div class="w-33 h-25">
                <h3>Info Service:</h3>
                <div class="input-group">
                    <span class="input-group-text w-50">Preluat in service</span>
                    <select class="form-select w-50" id="jurnal-service" name="preluat_service" required>
                        <option value="" <?php echo ($data['preluat_service'] ?? "" == "") ? "selected" : ""; ?>>Alege
                        </option>
                        <option value="Nu" <?php echo (isset($data['preluat_service']) && $data['preluat_service'] == "Nu") ? "selected" : ""; ?>>Nu</option>
                        <option value="Da" <?php echo (isset($data['preluat_service']) && $data['preluat_service'] == "Da") ? "selected" : ""; ?>>Da</option>
                    </select>
                </div>
                <div class="input-group my-2">
                    <span class="input-group-text w-50">Aparat reparat</span>
                    <select class="form-select w-50" id="jurnal-reparat" name="aparat_reparat" required>
                        <option value="" <?php echo ($data['aparat_reparat'] ?? "" == "") ? "selected" : ""; ?>>Alege
                        </option>
                        <option value="Nu" <?php echo (isset($data['aparat_reparat']) && $data['aparat_reparat'] == "Nu") ? "selected" : ""; ?>>Nu</option>
                        <option value="Da" <?php echo (isset($data['aparat_reparat']) && $data['aparat_reparat'] == "Da") ? "selected" : ""; ?>>Da</option>
                    </select>
                </div>
                <div class="input-group">
                    <span class="input-group-text w-50" style="color:grey">Pret estimativ</span>
                    <span class="input-group-text w-50" style="color:grey">Ex: 150 lei</span>
                </div>
            </div>
            <div class="w-33 h-25">
                <h3>OPERAȚII:</h3>
                <div class="overflow-auto" style="height: 150px;">
                    <textarea class="form-control" aria-label="With textarea" name="operatii"
                        required><?php echo htmlspecialchars($operatii); ?></textarea>
                </div>
            </div>
            <div class="w-33 h-25">
                <h3>Consum:</h3>
                <div class="overflow-auto" style="height: 150px;">
                    <textarea class="form-control" aria-label="With textarea" name="consum"
                        required><?php echo htmlspecialchars($consum); ?></textarea>
                </div>
            </div>
        </div>
        <hr>
        <br>
        <div class="container d-flex justify-content-start gap-3">
            <div class="w-33 h-25">
                <h3>Recomandari</h3>
                <div class="overflow-auto" style="height: 150px;">
                    <textarea class="form-control" aria-label="With textarea" name="recomandari"
                        required><?php echo htmlspecialchars($recomandari); ?></textarea>
                </div>
            </div>
            <div class="w-33 gap-2 pb-4">
                <h3>Inginer helion :</h3>
                <input type="text" class="form-control mb-2" placeholder="Cauta inginer ...">
                <div class="overflow-auto" style="height: 250px;">
                    <?php
                    $sql = "SELECT * FROM inginer";
                    $result = $connect->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $checked = in_array($row['id'], $inginer_selected) ? 'checked' : '';
                        echo "<div class='mb-1'>";
                        echo "<input class='form-check-input' type='checkbox' name='inginer[]' value='{$row['id']}' $checked> ";
                        echo "<label class='form-check-label'><strong>{$row['nume']}</strong><br>Functie: {$row['functie']}</label>";
                        echo "</div><hr>";
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
            <div class="w-50 h-25">
                <h3>Data Predarii:</h3>
                <div class="overflow-auto">
                    <input type="date" class="form-control" name="data_predarii" required
                        value="<?php echo htmlspecialchars($data_predarii); ?>">
                    <div class="input-group flex-nowrap mt-3 m-0">
                        <p class="input-group-text w-100 m-0" style="color:gray;">Timp transport (spre și de la
                            obiectiv)</p>
                    </div>
                    <div class="input-group mb-3 m-0">
                        <input type="text" class="form-control m-0" placeholder="Ex:20" name="timp_transport" required
                            value="<?php echo htmlspecialchars($timp_transport); ?>">
                        <span class="input-group-text w-50 m-0">min</span>
                    </div>
                </div>
                <div class="overflow-auto">
                    <div class="input-group flex-nowrap mt-3 m-0">
                        <p class="input-group-text w-100 m-0" style="color:gray;">Kilometri parcursi spre și de la
                            obiectiv</p>
                    </div>
                    <div class="input-group mb-3 m-0">
                        <input type="text" class="form-control m-0" placeholder="Ex:110" name="km_parcursi" required
                            value="<?php echo htmlspecialchars($km_parcursi); ?>">
                        <span class="input-group-text w-50 m-0">km</span>
                    </div>
                </div>
                <div class="overflow-auto">
                    <div class="input-group flex-nowrap mt-3 m-0">
                        <p class="input-group-text w-100 m-0" style="color:gray;">Timp manopera</p>
                    </div>
                    <div class="input-group mb-3 m-0">
                        <input type="text" class="form-control m-0" placeholder="Ex:10" name="timp_manopera" required
                            value="<?php echo htmlspecialchars($timp_manopera); ?>">
                        <span class="input-group-text w-50 m-0">min</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container d-flex justify-content-start gap-3">
            <div class="w-100 h-25">
                <h3>Semnatura:</h3>
                <div class="container">
                    <canvas id="semnatura-client" width="500" height="200" style="border:1px solid #ccc;"></canvas><br>
                    <input type="hidden" name="semnatura_client" id="semnatura_client_input">
                    <button type="button" onclick="clearPad()">Șterge</button>
                    <p>
                        Prin semnarea prezentei Fise de Service, Clientul, prin reprezentantul sau, acceptă lucrările,
                        confirmă executarea acestora și acceptă recepția lor intocmai.
                    </p>
                </div>
            </div>
        </div>
        <div class="container d-flex justify-content-start gap-3">
            <!-- Textarea: Observații -->
            <div class="w-100 h-25">
                <h3>Observatii din partea persoanei de contact:</h3>
                <div class="overflow-auto" style="height: 150px;">
                    <textarea class="form-control" aria-label="With textarea"
                        placeholder="Exemplu: Inginerul Helion a intervenit prompt" required
                        name="observatii"><?php echo htmlspecialchars($observatii); ?></textarea>
                </div>
            </div>
        </div>
        <div class="container d-flex justify-content-start gap-3">
            <div class="w-100 h-25">
                <h3>Actiuni:</h3>
                <div class="text-left my-4">
                    <button class="btn btn-success mb-2" type="submit">Finalizează și trimite fișa</button><br>
                    <button type="reset" class="btn btn-danger">Resetează</button>
                </div>
            </div>
        </div>
        <!-- Transmiterea coordonatelor GPS -->
        <input type="hidden" name="gps_lat" value="<?php echo htmlspecialchars($lat); ?>">
        <input type="hidden" name="gps_lng" value="<?php echo htmlspecialchars($lon); ?>">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        document.getElementById("stergePoza").addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("poza").value = "";
        });
        // Semnătură pentru reprezentant
        const canvasReprezentant = document.getElementById('semnatura-reprezentant');
        const signaturePadReprezentant = new SignaturePad(canvasReprezentant, { penColor: 'darkblue' });
        signaturePadReprezentant.fromDataURL("<?php echo $semnatura_reprezentant; ?>");
        function clearPadReprezentant() {
            signaturePadReprezentant.clear();
        }
        // Semnătură client
        const canvasClient = document.getElementById('semnatura-client');
        const signaturePadClient = new SignaturePad(canvasClient, { penColor: 'darkblue' });
        signaturePadClient.fromDataURL("<?php echo $semnatura_client; ?>");
        function clearPad() {
            signaturePadClient.clear();
        }
        // La submit, salvează semnăturile în câmpurile hidden
        const form = document.getElementById('myForm');
        form.addEventListener('submit', function (e) {
            if (!signaturePadClient.isEmpty()) {
                document.getElementById('semnatura_client_input').value = signaturePadClient.toDataURL();
            }
            if (!signaturePadReprezentant.isEmpty()) {
                document.getElementById('semnatura_reprezentant_input').value = signaturePadReprezentant.toDataURL();
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
</body>
<footer class="bg-light text-center py-3" style="border-top: 1px #ccc solid;">
    <small>Soluție oferită de <a href="https://deforce.eu/" target="_blank">DeForce Tehnologic SRL</a></small>
</footer>

</html>