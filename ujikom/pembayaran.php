php
<?php

// Koneksi database
$conn = mysqli_connect("localhost", "root", "", "ujikom");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

// Ambil data pembayaran
$query = mysqli_query(
    $conn,
    "SELECT * FROM tbl_pembayaran ORDER BY id_pembayaran DESC"
);

// Cek apakah query berhasil
if (!$query) {
    die("Query pembayaran gagal: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pembayaran</title>

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

.sidebar .logout:hover {
    background: #a00000;
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

        .card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        /* BUTTON TAMBAH */
        .btn-tambah {
            display: inline-block;
            background: #222;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .btn-tambah:hover {
            background: #444;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        table th {
            background: #222;
            color: white;
            padding: 12px;
            text-align: center;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        table tr:hover {
            background: #f2f2f2;
        }

        /* BUTTON EDIT */
        .btn-edit {
            display: inline-block;
            background: #555;
            color: white;
            padding: 6px 10px;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 5px;
        }

        /* BUTTON HAPUS */
        .btn-hapus {
            display: inline-block;
            background: #222;
            color: white;
            padding: 6px 10px;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-edit:hover,
        .btn-hapus:hover {
            opacity: 0.8;
        }
    </style>

</head>

<body>
<!-- SIDEBAR -->
<div class="sidebar">

    <h2>UJIKOM</h2>

    <a href="dashboard.php">
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

    <a href="pembayaran.php" class="active">
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

        <h1>Data Pembayaran</h1>

        <div class="card">

            <a href="tambah_pembayaran.php" class="btn-tambah">
                + Tambah Pembayaran
            </a>

            <table>

                <tr>
                    <th>No</th>
                    <th>ID Pembayaran</th>
                    <th>NIS</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>

                <?php

                $no = 1;

                // Cek apakah ada data
                if (mysqli_num_rows($query) > 0) {

                    while ($data = mysqli_fetch_assoc($query)) {

                ?>

                        <tr>

                            <td>
                                <?= $no++; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($data['id_pembayaran']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($data['nis']); ?>
                            </td>

                            <td>
                                Rp <?= number_format($data['jumlah'], 0, ',', '.'); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($data['tanggal_pembayaran']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($data['status']); ?>
                            </td>

                            <td>

                                <a href="edit_pembayaran.php?id=<?= $data['id_pembayaran']; ?>"
                                   class="btn-edit">
                                    Edit
                                </a>

                                <a href="hapus_pembayaran.php?id=<?= $data['id_pembayaran']; ?>"
                                   class="btn-hapus"
                                   onclick="return confirm('Yakin ingin menghapus data pembayaran ini?');">
                                    Hapus
                                </a>

                            </td>

                        </tr>

                <?php

                    }

                } else {

                ?>

                    <tr>
                        <td colspan="7">
                            Belum ada data pembayaran.
                        </td>
                    </tr>

                <?php

                }

                ?>

            </table>

        </div>

    </div>

</body>

</html>
```





