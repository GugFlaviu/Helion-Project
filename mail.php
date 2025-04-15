<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once './config/sqlconnect.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_GET['id'])) {


    $fisa_service_id = $_GET['id'];
    $sql = "
    SELECT 
        fs.id,
        fs.data_predarii,
        GROUP_CONCAT(DISTINCT f.nume_firma SEPARATOR ', ') AS firma_nume,
        GROUP_CONCAT(DISTINCT pl.nume SEPARATOR ', ') AS punct_lucru_nume,
        GROUP_CONCAT(DISTINCT pl.zona SEPARATOR ', ') AS judet,
        GROUP_CONCAT(DISTINCT pl.oras SEPARATOR ', ') AS oras,
        GROUP_CONCAT(DISTINCT pl.adresa SEPARATOR ', ') AS adresa,
        GROUP_CONCAT(DISTINCT r.nume SEPARATOR ', ') AS reprezentant_nume,
        GROUP_CONCAT(DISTINCT r.telefon SEPARATOR ', ') AS reprezentant_telefon,
        GROUP_CONCAT(DISTINCT r.email SEPARATOR ', ') AS reprezentant_email,
        GROUP_CONCAT(DISTINCT r.functie SEPARATOR ', ') AS reprezentant_functie,
        GROUP_CONCAT(DISTINCT ts.nume SEPARATOR ', ') AS tip_sistem_nume,
        fs.jurnal,
        fs.defect_semnalat,
        fs.constatare,
        fs.preluat_service,
        fs.aparat_reparat,
        fs.operatii,
        fs.consum,
        fs.recomandari,
        fs.timp_transport,
        fs.km_parcursi,
        fs.timp_manopera,
        fs.observatii,
        fs.semnatura_client,
        fs.semnatura_reprezentant,
        fs.gps_lat,
        fs.gps_lng,
        GROUP_CONCAT(DISTINCT i.nume SEPARATOR ', ') AS inginer_nume
    FROM fisa_service fs
    LEFT JOIN detalii_firma f ON FIND_IN_SET(f.id, fs.firma_ids)
    LEFT JOIN punct_lucru pl ON FIND_IN_SET(pl.id, fs.punct_lucru_ids)
    LEFT JOIN reprezentant r ON FIND_IN_SET(r.id, fs.reprezentant_ids)
    LEFT JOIN tip_sistem ts ON FIND_IN_SET(ts.id, fs.tip_sistem_ids)
    LEFT JOIN inginer i ON FIND_IN_SET(i.id, fs.inginer)
    WHERE fs.id = ?
    GROUP BY fs.id
    ";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $fisa_service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'default_font' => 'dejavusans'
        ]);
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="ro">

        <head>
            <meta charset="UTF-8">
            <title>Fișă de Intervenție</title>
            <style>
                body {
                    font-family: DejaVu Sans, sans-serif;
                    font-size: 12px;
                    margin: 0;
                    padding: 30px;
                }

                table {
                    border-collapse: collapse;
                    width: 100%;
                }

                th,
                td {
                    border: 1px solid #000;
                    padding: 4px 6px;
                    vertical-align: top;
                }

                .no-border td {
                    border: none;
                }

                .section-title {
                    background-color: #e6e6ff;
                    font-weight: bold;
                }

                .info-table td {
                    padding: 6px;
                }

                .signature {
                    height: 50px;
                }

                .light-blue {
                    background-color: #e6e6ff;
                }

                .nowrap {
                    white-space: nowrap;
                }

                .half-width {
                    width: 50%;
                    float: left;
                }

                .left-align {
                    float: left;
                }

                .header {
                    text-align: center;
                    font-weight: bold;
                }

                .header-logo {
                    width: 100%;
                    margin-bottom: 10px;
                }

                .logo-cell {
                    width: 20%;
                }

                .no-border {
                    border: none;
                }
            </style>
        </head>

        <body>
            <table class="header-logo no-border">
                <tr>
                    <td class="logo-cell"><img src="./img/logo.png" alt="Logo"></td>
                </tr>
            </table>
            <hr>
            <h2 class="header">FISA DE INTERVENȚIE</h2>
            <p class="header">
                Proces verbal de predare-primire<br>
                <strong>Nr. Fișă/P.V:</strong> <?= htmlspecialchars($row['id']) ?><br>
                <strong>Data:</strong> <?= htmlspecialchars($row['data_predarii']) ?>
            </p>
            <hr>
            <table class="info-table">
                <tr class="light-blue">
                    <td width="25%"><strong>Nume Obiectiv</strong></td>
                    <td width="25%"><strong>Adresa Obiectiv</strong></td>
                    <td width="25%"><strong>Firma</strong></td>
                    <td width="25%"><strong>Reprezentant</strong></td>
                </tr>
                <tr>
                    <td><?= htmlspecialchars($row['punct_lucru_nume']) ?></td>
                    <td><?= htmlspecialchars($row['judet']) ?>, <?= htmlspecialchars($row['oras']) ?>,
                        <?= htmlspecialchars($row['adresa']) ?>
                    </td>
                    <td><?= htmlspecialchars($row['firma_nume']) ?></td>
                    <td>
                        <?= htmlspecialchars($row['reprezentant_nume']) ?><br>
                        <?= htmlspecialchars($row['reprezentant_functie']) ?><br>
                        <hr>
                        <?= htmlspecialchars($row['reprezentant_email']) ?><br>
                        <?= htmlspecialchars('0' . $row['reprezentant_telefon']) ?>
                    </td>
                </tr>
            </table>

            <table class="info-table">
                <tr class="light-blue">
                    <td width="20%">Tip manoperă</td>
                    <td width="20%">Aparat reparat</td>
                    <td width="20%">Aparat preluat în service</td>
                    <td width="20%">Defect semnalat</td>
                    <td width="20%">Defect constatat</td>
                </tr>
                <tr>
                    <td><?= htmlspecialchars($row['tip_sistem_nume']) ?></td>
                    <td><?= $row['aparat_reparat'] ? 'Da' : 'Nu' ?></td>
                    <td><?= $row['preluat_service'] ? 'Da' : 'Nu' ?></td>
                    <td><?= htmlspecialchars($row['defect_semnalat']) ?></td>
                    <td><?= htmlspecialchars($row['constatare']) ?></td>
                </tr>
            </table>

            <table class="info-table half-width left-align">
                <tr class="light-blue">
                    <td>Timp manoperă</td>
                    <td>Locație GPS</td>
                </tr>
                <tr>
                    <td><?= htmlspecialchars($row['timp_manopera']) ?></td>
                    <td>
                        <a href="https://www.google.com/maps?q=<?= htmlspecialchars($row['gps_lat']) ?>,<?= htmlspecialchars($row['gps_lng']) ?>"
                            target="_blank">
                            <?= htmlspecialchars($row['gps_lat']) ?>, <?= htmlspecialchars($row['gps_lng']) ?>
                        </a>
                    </td>
                </tr>
            </table>

            <br>
            <hr><br>
            <hr><br><br>

            <table class="info-table half-width left-align">
                <tr class="light-blue">
                    <td>OPERAȚII</td>
                    <td><?= htmlspecialchars($row['operatii']) ?></td>
                </tr>
                <tr>
                    <td class="light-blue">Consum</td>
                    <td><?= htmlspecialchars($row['consum']) ?></td>
                </tr>
                <tr>
                    <td class="light-blue">Recomandări</td>
                    <td><?= htmlspecialchars($row['recomandari']) ?></td>
                </tr>
                <tr>
                    <td class="light-blue">Tehnician Helion</td>
                    <td><?= htmlspecialchars($row['inginer_nume']) ?></td>
                </tr>
                <tr>
                    <td class="light-blue">Observații</td>
                    <td><?= htmlspecialchars($row['observatii']) ?></td>
                </tr>
            </table>

            <br>
            <table>
                <tr>
                    <td width="50%">
                        <table class="info-table">
                            <tr class="light-blue">
                                <td colspan="2">Data primirii:</td>
                            </tr>
                            <tr>
                                <td colspan="2"><?= htmlspecialchars($row['data_predarii']) ?></td>
                            </tr>
                            <tr class="light-blue">
                                <td colspan="2">Primit de:</td>
                            </tr>
                            <tr>
                                <td colspan="2"><?= htmlspecialchars($row['reprezentant_nume']) ?></td>
                            </tr>
                            <tr class="light-blue">
                                <td colspan="2">Semnătură:</td>
                            </tr>
                            <tr>
                                <td class="signature" colspan="2">
                                    <?= $row['semnatura_client'] ? '<img src="' . $row['semnatura_client'] . '" height="50">' : '' ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%">
                        <table class="info-table">
                            <tr class="light-blue">
                                <td>Reprezentant Helion:</td>
                            </tr>
                            <tr>
                                <td><?= htmlspecialchars($row['inginer_nume']) ?></td>
                            </tr>
                            <tr class="light-blue">
                                <td>Semnătură:</td>
                            </tr>
                            <tr>
                                <td class="signature">
                                    <?= $row['semnatura_reprezentant'] ? '<img src="' . $row['semnatura_reprezentant'] . '" height="50">' : '' ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>

        </html>
        <?php
        $html = ob_get_clean();
        $mpdf->WriteHTML($html);

        // Salvează local PDF-ul temporar
        $pdfFilePath = __DIR__ . '/fisa_interventie_' . $row['id'] . '.pdf';
        $mpdf->Output($pdfFilePath, \Mpdf\Output\Destination::FILE);

        // === 3. Trimite email ===
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'gfsoft03@gmail.com';
            $mail->Password = 'your password';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('gugflaviu@gmail.com', 'Flaviu');
            $mail->addAddress('email destinatar', 'Destinatar'); //schimba emailul in functie de destinatar
            $mail->Subject = 'Fișa de intervenție #' . $row['id'];
            $mail->Body = 'Salut! Atașat găsești fișa de intervenție.';
            $mail->isHTML(false);

            $mail->addAttachment($pdfFilePath);
            $mail->send();

            echo 'Email trimis cu succes!';

            unlink($pdfFilePath); // Șterge fișierul PDF
        } catch (Exception $e) {
            echo "Eroare la trimitere: {$mail->ErrorInfo}";
        }
    } else {
        echo "Nu s-au găsit date pentru fișa cu ID-ul $fisa_service_id.";
    }
} else {
    echo "ID-ul fișei de service nu a fost furnizat!";
}
?>