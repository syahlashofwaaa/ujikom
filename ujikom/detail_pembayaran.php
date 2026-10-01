<?php

$conn = new mysqli("localhost", "root", "", "ujikom");

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

/* =========================
   CEK ID PEMBAYARAN
   ========================= */

if (!isset($_GET['id'])) {
    die("ID pembayaran tidak ditemukan.");
}

$id = intval($_GET['id']);


/* =========================
   AMBIL DATA PEMBAYARAN
   ========================= */

$stmt = $conn->prepare("
    SELECT *
    FROM pembayaran
    WHERE id_pembayaran = ?
    LIMIT 1
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Data pembayaran tidak ditemukan.");
}

$data = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Pembayaran</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        .container {
            width: 600px;
            max-width: 90%;
            margin: 50px auto;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        .detail {
            display: flex;
            justify-content: space-between;
            gap: 20px;

            padding: 13px 0;

            border-bottom: 1px solid #eee;
        }

        .detail:last-child {
            border-bottom: none;
        }

        .label {
            color: #777;
        }

        .value {
            font-weight: bold;
            text-align: right;
        }

        .status {
            display: inline-block;
            padding: 6px 13px;
            border-radius: 20px;
            font-size: 13px;
        }

        .lunas {
            background: #ddd;
            color: #222;
        }

        .belum {
            background: #eee;
            color: #555;
        }

        .tombol {
            display: flex;
            justify-content: center;
            gap: 10px;

            margin-top: 25px;
        }

        .btn {
            padding: 10px 18px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .kembali {
            background: #333;
            color: white;
        }

        .cetak {
            background: #ddd;
            color: #222;
        }

        .btn:hover {
            opacity: 0.8;
        }

        @media print {

            body {
                background: white;
            }

            .card {
                box-shadow: none;
            }

            .tombol {
                display: none;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <div class="card">

        <h2>Detail Pembayaran</h2>


        <div class="detail">

            <span class="label">
                ID Pembayaran
            </span>

            <span class="value">
                <?= htmlspecialchars($data['id_pembayaran']); ?>
            </span>

        </div>


        <div class="detail">

            <span class="label">
                Nama Pelanggan
            </span>

            <span class="value">
                <?= htmlspecialchars($data['nama_pelanggan']); ?>
            </span>

        </div>


        <div class="detail">

            <span class="label">
                Tanggal Pembayaran
            </span>

            <span class="value">
                <?= htmlspecialchars($data['tanggal_pembayaran']); ?>
            </span>

        </div>


        <div class="detail">

            <span class="label">
                Jumlah Pembayaran
            </span>

            <span class="value">

                Rp <?= number_format(
                    $data['jumlah'],
                    0,
                    ',',
                    '.'
                ); ?>

            </span>

        </div>


        <div class="detail">

            <span class="label">
                Metode Pembayaran
            </span>

            <span class="value">
                <?= htmlspecialchars($data['metode_pembayaran']); ?>
            </span>

        </div>


        <div class="detail">

            <span class="label">
                Status Pembayaran
            </span>

            <span class="value">

                <?php

                if (
                    strtolower($data['status']) == 'lunas'
                ) {

                ?>

                    <span class="status lunas">
                        Lunas
                    </span>

                <?php

                } else {

                ?>

                    <span class="status belum">
                        Belum Lunas
                    </span>

                <?php

                }

                ?>

            </span>

        </div>


        <div class="tombol">

            <a
                href="pembayaran.php"
                class="btn kembali"
            >
                ← Kembali
            </a>

            <a
                href="#"
                onclick="window.print()"
                class="btn cetak"
            >
                Cetak
            </a>

        </div>

    </div>

</div>

</body>

</html>

<?php

$stmt->close();
$conn->close();

?>