```php
<?php
session_start();

/* Cek login */
if (!isset($_SESSION['id_petugas'])) {
    header("Location: login.php");
    exit;
}

/* Koneksi database */
$conn = new mysqli("localhost", "root", "", "ujikom");

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

/* Jumlah siswa */
$query_siswa = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_siswa");
$data_siswa = mysqli_fetch_assoc($query_siswa);

/* Jumlah kelas */
$query_kelas = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_kelas");
$data_kelas = mysqli_fetch_assoc($query_kelas);

/* Jumlah SPP */
$query_spp = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_spp");
$data_spp = mysqli_fetch_assoc($query_spp);

/* Jumlah pembayaran */
$query_pembayaran = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_pembayaran");
$data_pembayaran = mysqli_fetch_assoc($query_pembayaran);

/* Total uang pembayaran */
$query_total = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(jumlah_bayar), 0) AS total
     FROM tbl_pembayaran"
);

$data_total = mysqli_fetch_assoc($query_total);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - UJIKOM</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
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
        }

        .sidebar a {
            display: block;
            padding: 14px 20px;
            color: white;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #444;
        }

        .sidebar .logout {
            margin-top: 30px;
            background: #8b0000;
        }

        /* CONTENT */
        .content {
            margin-left: 230px;
            padding: 30px;
        }

        .header {
            background: white;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0 0 8px;
        }

        .header p {
            margin: 0;
            color: #666;
        }

        /* CARD */
        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .card h3 {
            margin: 0 0 15px;
            color: #666;
            font-size: 15px;
        }

        .angka {
            font-size: 30px;
            font-weight: bold;
        }

        /* TOTAL */
        .total {
            margin-top: 25px;
            background: white;
            padding: 25px;
            border-radius: 8px;
        }

        .total h3 {
            margin-top: 0;
            color: #666;
        }

        .nominal {
            font-size: 28px;
            font-weight: bold;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .content {
                margin-left: 0;
            }

            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <h2>UJIKOM</h2>

    <a href="dashboard.php" class="active">
        Dashboard
    </a>

    <a href="data_siswa.php">
        Data Siswa
    </a>

    <a href="data_kelas.php">
        Data Kelas
    </a>

    <a href="data_spp.php">
        Data SPP
    </a>

    <a href="pembayaran.php">
        Pembayaran
    </a>

    <a href="cek_pembayaran.php">
        Cek Pembayaran
    </a>

    <a href="logout.php"
       class="logout"
       onclick="return confirm('Yakin ingin logout?')">
        Logout
    </a>

</div>


<!-- CONTENT -->
<div class="content">

    <div class="header">

        <h1>Dashboard</h1>

        <p>
            Selamat datang,
            <strong>
                <?php echo htmlspecialchars($_SESSION['nama_petugas']); ?>
            </strong>
        </p>

    </div>


    <!-- CARD -->
    <div class="cards">

        <div class="card">

            <h3>Total Siswa</h3>

            <div class="angka">
                <?php echo $data_siswa['total']; ?>
            </div>

        </div>


        <div class="card">

            <h3>Total Kelas<
```
