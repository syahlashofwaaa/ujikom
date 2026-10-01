```php
<?php

$conn = new mysqli("localhost", "root", "", "ujikom");

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$data = null;
$error = "";

if (isset($_POST['cek'])) {

    $id_pembayaran = trim($_POST['id_pembayaran']);

    if ($id_pembayaran == "") {

        $error = "Silakan masukkan ID pembayaran.";

    } else {

        $stmt = $conn->prepare(
            "SELECT * FROM pembayaran
             WHERE id_pembayaran = ?
             LIMIT 1"
        );

        $stmt->bind_param("i", $id_pembayaran);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $data = $result->fetch_assoc();

        } else {

            $error = "Data pembayaran tidak ditemukan.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cek Pembayaran</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 450px;
            background: white;

            padding: 30px;

            border-radius: 10px;

            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            color: #777;

            font-size: 14px;

            margin-bottom: 25px;
        }

        label {
            display: block;

            margin-bottom: 7px;

            font-size: 14px;
        }

        input {
            width: 100%;

            padding: 11px;

            border: 1px solid #ccc;

            border-radius: 5px;

            outline: none;

            margin-bottom: 15px;
        }

        input:focus {
            border-color: #555;
        }

        button {
            width: 100%;

            padding: 11px;

            background: #333;

            color: white;

            border: none;

            border-radius: 5px;

            cursor: pointer;

            font-size: 14px;
        }

        button:hover {
            background: #555;
        }

        .error {
            background: #eee;

            color: #333;

            padding: 10px;

            border-radius: 5px;

            text-align: center;

            margin-bottom: 18px;

            font-size: 13px;
        }

        .hasil {
            margin-top: 25px;

            border-top: 1px solid #ddd;

            padding-top: 20px;
        }

        .hasil h3 {
            margin-bottom: 15px;
        }

        .row {
            display: flex;

            justify-content: space-between;

            gap: 15px;

            padding: 10px 0;

            border-bottom: 1px solid #eee;

            font-size: 14px;
        }

        .label {
            color: #777;
        }

        .value {
            text-align: right;

            font-weight: bold;
        }

        .status {
            display: inline-block;

            padding: 5px 12px;

            border-radius: 15px;

            font-size: 12px;
        }

        .lunas {
            background: #ddd;

            color: #222;
        }

        .belum {
            background: #eee;

            color: #555;
        }

        .kembali {
            display: block;

            text-align: center;

            margin-top: 20px;

            color: #333;

            text-decoration: none;

            font-size: 14px;
        }

        .kembali:hover {
            text-decoration: underline;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>Cek Pembayaran</h2>

    <p class="subtitle">
        Masukkan ID pembayaran untuk melihat status pembayaran
    </p>


    <?php if ($error != "") { ?>

        <div class="error">
            <?= htmlspecialchars($error); ?>
        </div>

    <?php } ?>


    <form method="POST">

        <label>
            ID Pembayaran
        </label>

        <input
            type="number"
            name="id_pembayaran"
            placeholder="Masukkan ID pembayaran"
            required
        >

        <button
            type="submit"
            name="cek"
        >
            Cek Pembayaran
        </button>

    </form>


    <?php if ($data) { ?>

        <div class="hasil">

            <h3>
                Hasil Pembayaran
            </h3>


            <div class="row">

                <span class="label">
                    ID Pembayaran
                </span>

                <span class="value">
                    <?= htmlspecialchars(
                        $data['id_pembayaran']
                    ); ?>
                </span>

            </div>


            <div class="row">

                <span class="label">
                    Nama Pelanggan
                </span>

                <span class="value">
                    <?= htmlspecialchars(
                        $data['nama_pelanggan']
                    ); ?>
                </span>

            </div>


            <div class="row">

                <span class="label">
                    Tanggal
                </span>

                <span class="value">
                    <?= htmlspecialchars(
                        $data['tanggal_pembayaran']
                    ); ?>
                </span>

            </div>


            <div class="row">

                <span class="label">
                    Jumlah
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


            <div class="row">

                <span class="label">
                    Metode
                </span>

                <span class="value">

                    <?= htmlspecialchars(
                        $data['metode_pembayaran']
                    ); ?>

                </span>

            </div>


            <div class="row">

                <span class="label">
                    Status
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

        </div>

    <?php } ?>


    <a href="index.php" class="kembali">
        ← Kembali
    </a>

</div>

</body>

</html>

<?php
$conn->close();
?>
```

