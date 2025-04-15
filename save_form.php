<?php
include "./config/sqlconnect.php";
$missing_fields = [];

// Verificăm câmpurile
if (!isset($_POST['firma']) || !is_array($_POST['firma'])) {
    $missing_fields[] = "Firma";
}
if (!isset($_POST['punct_lucru']) || !is_array($_POST['punct_lucru'])) {
    $missing_fields[] = "Punct Lucru";
}
if (!isset($_POST['reprezentant']) || !is_array($_POST['reprezentant'])) {
    $missing_fields[] = "Reprezentant";
}
if (!isset($_POST['tip_sistem']) || !is_array($_POST['tip_sistem'])) {
    $missing_fields[] = "Tip Sistem";
}
if (!isset($_POST['jurnal'])) {
    $missing_fields[] = "Jurnal";
}
if (!isset($_POST['defect_semnalat'])) {
    $missing_fields[] = "Defect Semnalat";
}
if (!isset($_POST['constatare'])) {
    $missing_fields[] = "Constatare";
}
if (!isset($_POST['preluat_service'])) {
    $missing_fields[] = "Preluat Service";
}
if (!isset($_POST['aparat_reparat'])) {
    $missing_fields[] = "Aparat Reparat";
}
if (!isset($_POST['operatii'])) {
    $missing_fields[] = "Operatii";
}
if (!isset($_POST['consum'])) {
    $missing_fields[] = "Consum";
}
if (!isset($_POST['recomandari'])) {
    $missing_fields[] = "Recomandari";
}
if (!isset($_POST['inginer'])) {
    $missing_fields[] = "Inginer";
}
if (!isset($_POST['semnatura_reprezentant'])) {
    $missing_fields[] = "Semnatura Reprezentant";
}
if (!isset($_POST['data_predarii'])) {
    $missing_fields[] = "Data Predarii";
}
if (!isset($_POST['timp_transport'])) {
    $missing_fields[] = "Timp Transport";
}
if (!isset($_POST['km_parcursi'])) {
    $missing_fields[] = "KM Parcursi";
}
if (!isset($_POST['timp_manopera'])) {
    $missing_fields[] = "Timp Manopera";
}
if (!isset($_POST['semnatura_client'])) {
    $missing_fields[] = "Semnatura Client";
}
if (!isset($_POST['observatii'])) {
    $missing_fields[] = "Observatii";
}

if (count($missing_fields) > 0) {

    $missing_fields_str = implode(", ", $missing_fields);
    echo "<script>alert('Te rugăm să completezi următoarele câmpuri: $missing_fields_str');</script>";
} else {
    if (
        isset($_POST['firma']) && is_array($_POST['firma']) &&
        isset($_POST['punct_lucru']) && is_array($_POST['punct_lucru']) &&
        isset($_POST['reprezentant']) && is_array($_POST['reprezentant']) &&
        isset($_POST['tip_sistem']) && is_array($_POST['tip_sistem']) &&
        isset($_POST['jurnal']) && isset($_POST['defect_semnalat'])
        && isset($_POST['constatare'])
        && isset($_POST['preluat_service'])
        && isset($_POST['aparat_reparat'])
        && isset($_POST['operatii'])
        && isset($_POST['consum'])
        && isset($_POST['recomandari'])
        && isset($_POST['inginer']) && is_array($_POST['inginer'])
        && isset($_POST['semnatura_reprezentant'])
        && isset($_POST['data_predarii'])
        && isset($_POST['timp_transport'])
        && isset($_POST['km_parcursi'])
        && isset($_POST['timp_manopera'])
        && isset($_POST['semnatura_client'])
        && isset($_POST['observatii'])
        && isset($_POST['gps_lat'])
        && isset($_POST['gps_lng'])
    ) {
        // Extragem id-urile selectate
        $firma_ids = implode(',', $_POST['firma']);
        $punct_lucru_ids = implode(',', $_POST['punct_lucru']);
        $reprezentant_ids = implode(',', $_POST['reprezentant']);
        $tip_sistem_ids = implode(',', $_POST['tip_sistem']);
        $inginer_ids = implode(',', $_POST['inginer']);

        $jurnal = $_POST['jurnal'];
        $defect_semnalat = $_POST['defect_semnalat'];
        $constatare = $_POST['constatare'];
        $preluat_service = $_POST['preluat_service'];
        $aparat_reparat = $_POST['aparat_reparat'];
        $operatii = $_POST['operatii'];
        $consum = $_POST['consum'];
        $recomandari = $_POST['recomandari'];
        $data_predarii = $_POST['data_predarii'];
        $timp_transport = $_POST['timp_transport'];
        $km_parcursi = $_POST['km_parcursi'];
        $timp_manopera = $_POST['timp_manopera'];
        $observatii = $_POST['observatii'];
        $semnatura_client = $_POST['semnatura_client'];
        $semnatura_reprezentant = $_POST['semnatura_reprezentant'];
        $lat = $_POST['gps_lat'] ?? null;
        $lon = $_POST['gps_lng'] ?? null;
        // Pregătim interogarea
        $stmt = $connect->prepare("
            INSERT INTO fisa_service (
                firma_ids, punct_lucru_ids, reprezentant_ids, tip_sistem_ids,
                jurnal, defect_semnalat, constatare, preluat_service,
                aparat_reparat, operatii, consum, recomandari,
                inginer, data_predarii, timp_transport, km_parcursi,
                timp_manopera, observatii, semnatura_client, semnatura_reprezentant, gps_lat,gps_lng
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)
        ");

        if (!$stmt) {
            die("Eroare pregătire statement: " . $connect->error);
        }

        $stmt->bind_param(
            "ssssssssssssssssssssss",
            $firma_ids,
            $punct_lucru_ids,
            $reprezentant_ids,
            $tip_sistem_ids,
            $jurnal,
            $defect_semnalat,
            $constatare,
            $preluat_service,
            $aparat_reparat,
            $operatii,
            $consum,
            $recomandari,
            $inginer_ids,
            $data_predarii,
            $timp_transport,
            $km_parcursi,
            $timp_manopera,
            $observatii,
            $semnatura_client,
            $semnatura_reprezentant,
            $lat,
            $lon
        );

        if ($stmt->execute()) {
            echo "<script>alert('Fișa a fost salvată cu succes!');</script>";
            header("Location: home.php");
            exit();
        } else {
            echo "Eroare la salvare: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "<script>alert('Te rog să completezi toate câmpurile!');</script>";
    }
}

?>