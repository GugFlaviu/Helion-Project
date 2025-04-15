<?php
include "./config/sqlconnect.php";
$sort = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'desc';

$allowedSort = ['id', 'tehnician'];
$allowedOrder = ['asc', 'desc'];

if (!in_array($sort, $allowedSort))
    $sort = 'id';
if (!in_array($order, $allowedOrder))
    $order = 'desc';

$sortColumnMap = [
    'id' => 'fs.id',
    'tehnician' => 'fs.inginer',
    'sistem' => 'fs.tip_sistem_ids',
    'firma' => 'fs.firma_ids',
    'locatie' => 'fs.punct_lucru_ids',
    'data_semnare' => 'fs.data_predarii'

];
function sortLink($column, $label)
{
    $currentSort = $_GET['sort'] ?? 'id';
    $currentOrder = $_GET['order'] ?? 'desc';

    $newOrder = ($currentSort === $column && $currentOrder === 'asc') ? 'desc' : 'asc';

    $arrow = '';
    if ($currentSort === $column) {
        $arrow = $currentOrder === 'asc' ? ' <span style="font-size:12px;">↑</span>' : ' <span style="font-size:12px;">↓</span>';
    }

    $url = strtok($_SERVER["REQUEST_URI"], '?');
    $link = "$url?sort=$column&order=$newOrder";

    return "<a href='$link' style='text-decoration: none; color: inherit; font-weight: bold;'>$label$arrow</a>";
}
$orderBy = " ORDER BY " . $sortColumnMap[$sort] . " $order ";
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
    GROUP_CONCAT(DISTINCT ts.nume SEPARATOR ', ') AS tip_sistem_nume,
    fs.inginer
FROM fisa_service fs
LEFT JOIN detalii_firma f ON FIND_IN_SET(f.id, fs.firma_ids)
LEFT JOIN punct_lucru pl ON FIND_IN_SET(pl.id, fs.punct_lucru_ids)
LEFT JOIN reprezentant r ON FIND_IN_SET(r.id, fs.reprezentant_ids)
LEFT JOIN tip_sistem ts ON FIND_IN_SET(ts.id, fs.tip_sistem_ids)
GROUP BY fs.id
$orderBy
";
$result = $connect->query($sql);


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
    <style>
        .tip-cell {
            background-color: #137547;
            color: white;
        }

        .bold {
            font-weight: bold;
        }

        th button {
            all: unset;
            cursor: pointer;
            color: #0d6efd;
            font-weight: bold;
        }

        th button:hover {
            text-decoration: underline;
        }
    </style>
</head>


<body>
    <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <?php
    include "templates/navbar.php";

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
            <div class="container mt-5">
                <h4 class="mb-3">Tabel Service</h4>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th><?= sortLink('id', 'ID') ?></th>
                                <th><?= sortLink('tehnician', 'Tehnician') ?></th>
                                <th>Tip</th>
                                <th><?= sortLink('sistem', 'Sistem') ?></th>
                                <th><?= sortLink('firma', 'Firma') ?></th>
                                <th><?= sortLink('locatie', 'Locatie') ?></th>
                                <th><?= sortLink('data_semnare', 'Data semnare') ?></th>

                                <th>Actiune</th>
                            </tr>
                        </thead>

                        <tbody><?php
                        while ($row = $result->fetch_assoc()) {

                            $ingineri = [];
                            $inginer_ids = explode(',', $row['inginer']);
                            foreach ($inginer_ids as $id) {
                                $id = intval($id);
                                $resIng = $connect->query("SELECT nume FROM inginer WHERE id = $id");
                                if ($resIng && $resIng->num_rows > 0) {
                                    $ingineri[] = $resIng->fetch_assoc()['nume'];
                                }
                            }
                            $ingineri_html = implode(',<br>', $ingineri);

                            echo "<tr>
                                <td><a href='#'>{$row['id']}</a></td>
                                <td>$ingineri_html</td>
                                <td style='background-color: #137547; color: white;'>[SEC]<br>service_no_contract</td>
                                <td>{$row['tip_sistem_nume']}</td>
                                <td>{$row['firma_nume']}</td>
                                <td>
                                    <span class='bold'>{$row['punct_lucru_nume']}</span><br>
                                    {$row['judet']}, {$row['oras']},<br>
                                    {$row['adresa']}
                                </td>
                                <td>{$row['data_predarii']}</td>
                                <td>
                                <select style='background-color:rgb(139, 139, 139);' class='form-select form-select-sm' onchange='handleAction(this.value, {$row['id']})'>
                                    <option selected disabled>Acțiuni</option>
                                    <option value='view'>View</option>
                                    <option value='edit'>Edit</option>
                                    <option value='mail'>Mail</option>
                                </select>
                                </td>
                            </tr>";
                        } ?>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
    <script>
        function handleAction(action, id) {
            if (action === "view") {
                window.location.href = `fisa_service_pdf.php?id=${id}`;
            }
            else if (action === "edit") {
                window.location.href = `edit_fisa_service.php?id=${id}`;
            }
            else if (action === "mail") {
                window.location.href = `mail.php?id=${id}`;
            }
        }

    </script>

</body>

</html>