<?php

session_start();

$conn = mysqli_connect("localhost", "root", "", "ujikom");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}


/* =========================
   TAMBAH DATA
========================= */

if (isset($_POST['tambah'])) {

    $tahun = $_POST['tahun'];
    $nominal = $_POST['nominal'];

    mysqli_query($conn, "
        INSERT INTO tbl_spp (tahun, nominal)
        VALUES ('$tahun', '$nominal')
    ");

    header("Location: data_spp.php");
    exit;
}


/* =========================
   HAPUS DATA
========================= */

if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    mysqli_query($conn, "
        DELETE FROM tbl_spp
        WHERE id_spp='$id'
    ");

    header("Location: data_spp.php");
    exit;
}


/* =========================
   DATA EDIT
========================= */

$edit_data = null;

if (isset($_GET['edit'])) {

    $id = $_GET['edit'];

    $query_edit = mysqli_query($conn, "
        SELECT * FROM tbl_spp
        WHERE id_spp='$id'
    ");

    $edit_data = mysqli_fetch_assoc($query_edit);
}


/* =========================
   UPDATE DATA
========================= */

if (isset($_POST['update'])) {

    $id = $_POST['id_spp'];
    $tahun = $_POST['tahun'];
    $nominal = $_POST['nominal'];

    mysqli_query($conn, "
        UPDATE tbl_spp SET
            tahun='$tahun',
            nominal='$nominal'
        WHERE id_spp='$id'
    ");

    header("Location: data_spp.php");
    exit;
}


/* =========================
   TAMPIL DATA
========================= */

$data_spp = mysqli_query($conn, "
    SELECT * FROM tbl_spp
    ORDER BY id_spp DESC
");

if (!$data_spp) {
    die("Query data SPP gagal: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data SPP</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #222;
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


        /* HEADER */

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


        /* FORM */

        .form-box {
            background: white;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .form-box h2 {
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }


        /* BUTTON */

        button,
        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-tambah {
            background: #333;
            color: white;
        }

        .btn-batal {
            background: #777;
            color: white;
            margin-left: 5px;
        }

        .btn-edit {
            background: #333;
            color: white;
            padding: 7px 12px;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-hapus {
            background: #8b0000;
            color: white;
            padding: 7px 12px;
            text-decoration: none;
            border-radius: 4px;
            margin-left: 5px;
        }


        /* TABLE */

        .table-box {
            background: white;
            padding: 25px;
            border-radius: 8px;
            overflow-x: auto;
        }

        .table-box h2 {
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #222;
            color: white;
            padding: 12px;
            text-align: left;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background: #f5f5f5;
        }


        /* RESPONSIVE */

        @media (max-width: 600px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .content {
                margin-left: 0;
            }

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

    <a href="data_spp.php" class="active">
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


    <!-- HEADER -->

    <div class="header">

        <h1>Data SPP</h1>

        <p>
            Kelola data SPP siswa
        </p>

    </div>


    <!-- FORM -->

    <div class="form-box">

        <?php if ($edit_data) { ?>

            <h2>Edit Data SPP</h2>

            <form method="POST">

                <input
                    type="hidden"
                    name="id_spp"
                    value="<?= $edit_data['id_spp']; ?>"
                >

                <div class="form-group">

                    <label>Tahun</label>

                    <input
                        type="text"
                        name="tahun"
                        value="<?= htmlspecialchars($edit_data['tahun']); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Nominal</label>

                    <input
                        type="number"
                        name="nominal"
                        value="<?= $edit_data['nominal']; ?>"
                        required
                    >

                </div>


                <button
                    type="submit"
                    name="update"
                    class="btn btn-tambah"
                >
                    Update Data
                </button>


                <a
                    href="data_spp.php"
                    class="btn btn-batal"
                >
                    Batal
                </a>

            </form>

        <?php } else { ?>

            <h2>Tambah Data SPP</h2>

            <form method="POST">

                <div class="form-group">

                    <label>Tahun</label>

                    <input
                        type="text"
                        name="tahun"
                        placeholder="Contoh: 2026"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Nominal</label>

                    <input
                        type="number"
                        name="nominal"
                        placeholder="Contoh: 500000"
                        required
                    >

                </div>


                <button
                    type="submit"
                    name="tambah"
                    class="btn btn-tambah"
                >
                    Tambah Data
                </button>

            </form>

        <?php } ?>

    </div>


    <!-- TABLE -->

    <div class="table-box">

        <h2>Daftar SPP</h2>

        <table>

            <tr>

                <th>No</th>

                <th>Tahun</th>

                <th>Nominal</th>

                <th>Aksi</th>

            </tr>


            <?php

            $no = 1;

            if (mysqli_num_rows($data_spp) > 0) {

                while ($row = mysqli_fetch_assoc($data_spp)) {

            ?>

                <tr>

                    <td>
                        <?= $no++; ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['tahun']); ?>
                    </td>

                    <td>
                        Rp <?= number_format($row['nominal'], 0, ',', '.'); ?>
                    </td>

                    <td>

                        <a
                            href="data_spp.php?edit=<?= $row['id_spp']; ?>"
                            class="btn-edit"
                        >
                            Edit
                        </a>

                        <a
                            href="data_spp.php?hapus=<?= $row['id_spp']; ?>"
                            class="btn-hapus"
                            onclick="return confirm('Yakin ingin menghapus data SPP ini?')"
                        >
                            Hapus
                        </a>

                    </td>

                </tr>

            <?php

                }

            } else {

            ?>

                <tr>

                    <td colspan="4" style="text-align:center;">
                        Belum ada data SPP
                    </td>

                </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>

</html>