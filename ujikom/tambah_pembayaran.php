```php
<?php

// Koneksi database
$conn = mysqli_connect("localhost", "root", "", "ujikom");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");


// Jika tombol Simpan ditekan
if (isset($_POST['simpan'])) {

    $nis = mysqli_real_escape_string($conn, $_POST['nis']);
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $tanggal_pembayaran = mysqli_real_escape_string($conn, $_POST['tanggal_pembayaran']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Simpan data
    $query = mysqli_query(
        $conn,
        "INSERT INTO tbl_pembayaran
        (nis, jumlah, tanggal_pembayaran, status)
        VALUES
        ('$nis', '$jumlah', '$tanggal_pembayaran', '$status')"
    );

    if ($query) {
        echo "<script>
                alert('Data pembayaran berhasil ditambahkan!');
                window.location='pembayaran.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Data pembayaran gagal ditambahkan: " . mysqli_error($conn) . "');
              </script>";
    }
}


// Ambil data siswa untuk pilihan NIS
$data_siswa = mysqli_query(
    $conn,
    "SELECT nis, nama_siswa FROM siswa ORDER BY nis ASC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Pembayaran</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 230px;
            height: 100vh;
            background: #222;
            color: white;
            padding-top: 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 22px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 13px 20px;
            font-size: 15px;
        }

        .sidebar a:hover {
            background: #444;
        }

        /* CONTENT */
        .content {
            margin-left: 230px;
            padding: 30px;
        }

        .content h1 {
            margin-top: 0;
            color: #222;
        }

        /* CARD */
        .card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            max-width: 700px;
        }

        /* FORM */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #555;
        }

        /* BUTTON */
        .button-group {
            margin-top: 25px;
        }

        .btn-simpan {
            background: #222;
            color: white;
            border: none;
            padding: 11px 18px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-simpan:hover {
            background: #444;
        }

        .btn-kembali {
            display: inline-block;
            background: #777;
            color: white;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 5px;
            margin-left: 5px;
            font-size: 14px;
        }

        .btn-kembali:hover {
            background: #555;
        }

    </style>

</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">

        <h2>UJIKOM</h2>

        <a href="dashboard.php">Dashboard</a>
        <a href="data_siswa.php">Data Siswa</a>
        <a href="data_kelas.php">Data Kelas</a>
        <a href="pembayaran.php">Pembayaran</a>
        <a href="cek_pembayaran.php">Cek Pembayaran</a>
        <a href="logout.php">Logout</a>

    </div>


    <!-- CONTENT -->
    <div class="content">

        <h1>Tambah Pembayaran</h1>

        <div class="card">

            <form method="POST">

                <!-- NIS -->
                <div class="form-group">

                    <label>NIS</label>

                    <select name="nis" required>

                        <option value="">-- Pilih NIS --</option>

                        <?php

                        if ($data_siswa) {

                            while ($siswa = mysqli_fetch_assoc($data_siswa)) {

                        ?>

                                <option value="<?= htmlspecialchars($siswa['nis']); ?>">
                                    <?= htmlspecialchars($siswa['nis']); ?>
                                    -
                                    <?= htmlspecialchars($siswa['nama_siswa']); ?>
                                </option>

                        <?php

                            }

                        }

                        ?>

                    </select>

                </div>


                <!-- JUMLAH -->
                <div class="form-group">

                    <label>Jumlah Pembayaran</label>

                    <input
                        type="number"
                        name="jumlah"
                        placeholder="Masukkan jumlah pembayaran"
                        min="0"
                        required
                    >

                </div>


                <!-- TANGGAL -->
                <div class="form-group">

                    <label>Tanggal Pembayaran</label>

                    <input
                        type="date"
                        name="tanggal_pembayaran"
                        value="<?= date('Y-m-d'); ?>"
                        required
                    >

                </div>


                <!-- STATUS -->
                <div class="form-group">

                    <label>Status</label>

                    <select name="status" required>

                        <option value="">-- Pilih Status --</option>
                        <option value="Belum Lunas">Belum Lunas</option>
                        <option value="Lunas">Lunas</option>

                    </select>

                </div>


                <!-- BUTTON -->
                <div class="button-group">

                    <button
                        type="submit"
                        name="simpan"
                        class="btn-simpan">
                        Simpan
                    </button>

                    <a
                        href="pembayaran.php"
                        class="btn-kembali">
                        Kembali
                    </a>

                </div>

            </form>

        </div>

    </div>

</body>

</html>
 
