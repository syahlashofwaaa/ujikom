<?php

session_start();

/* =========================
   KONEKSI DATABASE
========================= */

$conn = mysqli_connect("localhost", "root", "", "ujikom");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}


/* =========================
   TAMBAH DATA
========================= */

if (isset($_POST['tambah'])) {

    $nama_kelas = mysqli_real_escape_string(
        $conn,
        $_POST['nama_kelas']
    );

    $query = mysqli_query($conn, "
        INSERT INTO tbl_kelas (nama_kelas)
        VALUES ('$nama_kelas')
    ");

    if ($query) {

        header("Location: data_kelas.php");
        exit;

    } else {

        die(
            "Gagal menambahkan data kelas: "
            . mysqli_error($conn)
        );
    }
}


/* =========================
   HAPUS DATA
========================= */

if (isset($_GET['hapus'])) {

    $id = mysqli_real_escape_string(
        $conn,
        $_GET['hapus']
    );

    $query = mysqli_query($conn, "
        DELETE FROM tbl_kelas
        WHERE id_kelas='$id'
    ");

    if (!$query) {

        die(
            "Gagal menghapus data: "
            . mysqli_error($conn)
        );
    }

    header("Location: data_kelas.php");
    exit;
}


/* =========================
   DATA EDIT
========================= */

$edit_data = null;

if (isset($_GET['edit'])) {

    $id = mysqli_real_escape_string(
        $conn,
        $_GET['edit']
    );

    $query_edit = mysqli_query($conn, "
        SELECT *
        FROM tbl_kelas
        WHERE id_kelas='$id'
    ");

    if (!$query_edit) {

        die(
            "Gagal mengambil data: "
            . mysqli_error($conn)
        );
    }

    $edit_data = mysqli_fetch_assoc($query_edit);
}


/* =========================
   UPDATE DATA
========================= */

if (isset($_POST['update'])) {

    $id = mysqli_real_escape_string(
        $conn,
        $_POST['id_kelas']
    );

    $nama_kelas = mysqli_real_escape_string(
        $conn,
        $_POST['nama_kelas']
    );

    $query = mysqli_query($conn, "
        UPDATE tbl_kelas SET
            nama_kelas='$nama_kelas'
        WHERE id_kelas='$id'
    ");

    if ($query) {

        header("Location: data_kelas.php");
        exit;

    } else {

        die(
            "Gagal mengubah data: "
            . mysqli_error($conn)
        );
    }
}


/* =========================
   TAMPIL DATA
========================= */

$data_kelas = mysqli_query($conn, "
    SELECT *
    FROM tbl_kelas
    ORDER BY id_kelas DESC
");

if (!$data_kelas) {

    die(
        "Query data kelas gagal: "
        . mysqli_error($conn)
    );
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Data Kelas - UJIKOM</title>

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


        /* =========================
           SIDEBAR
        ========================= */

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


        /* =========================
           CONTENT
        ========================= */

        .content {
            margin-left: 230px;
            padding: 30px;
        }


        /* =========================
           HEADER
        ========================= */

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


        /* =========================
           FORM
        ========================= */

        .form-box {
            background: white;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .form-box h2 {
            margin-top: 0;
            margin-bottom: 20px;
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
            border-radius: 5px;
            font-size: 14px;
        }


        /* =========================
           BUTTON
        ========================= */

        .btn {
            display: inline-block;
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-tambah {
            background: #222;
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
            border-radius: 4px;
            text-decoration: none;
        }

        .btn-hapus {
            background: #8b0000;
            color: white;
            padding: 7px 12px;
            border-radius: 4px;
            text-decoration: none;
            margin-left: 5px;
        }


        /* =========================
           TABLE
        ========================= */

        .table-box {
            background: white;
            padding: 25px;
            border-radius: 8px;
            overflow-x: auto;
        }

        .table-box h2 {
            margin-top: 0;
            margin-bottom: 20px;
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


        /* =========================
           RESPONSIVE
        ========================= */

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


<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">

    <h2>UJIKOM</h2>

    <a href="dashboard.php">
        Dashboard
    </a>

    <a href="data_siswa.php">
        Data Siswa
    </a>

    <a href="data_kelas.php" class="active">
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


<!-- =========================
     CONTENT
========================= -->

<div class="content">


    <!-- HEADER -->

    <div class="header">

        <h1>Data Kelas</h1>

        <p>
            Kelola data kelas
        </p>

    </div>


    <!-- =========================
         FORM TAMBAH / EDIT
    ========================= -->

    <div class="form-box">

        <?php if ($edit_data) { ?>

            <h2>Edit Data Kelas</h2>

            <form method="POST">

                <input
                    type="hidden"
                    name="id_kelas"
                    value="<?= $edit_data['id_kelas']; ?>"
                >

                <div class="form-group">

                    <label>Nama Kelas</label>

                    <input
                        type="text"
                        name="nama_kelas"
                        value="<?= htmlspecialchars(
                            $edit_data['nama_kelas']
                        ); ?>"
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
                    href="data_kelas.php"
                    class="btn btn-batal"
                >
                    Batal
                </a>

            </form>

        <?php } else { ?>

            <h2>Tambah Data Kelas</h2>

            <form method="POST">

                <div class="form-group">

                    <label>Nama Kelas</label>

                    <input
                        type="text"
                        name="nama_kelas"
                        placeholder="Contoh: X RPL 1"
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


    <!-- =========================
         TABEL DATA
    ========================= -->

    <div class="table-box">

        <h2>Daftar Kelas</h2>

        <table>

            <tr>

                <th>No</th>

                <th>Nama Kelas</th>

                <th>Aksi</th>

            </tr>


            <?php

            $no = 1;

            if (mysqli_num_rows($data_kelas) > 0) {

                while ($row = mysqli_fetch_assoc($data_kelas)) {

            ?>

                <tr>

                    <td>
                        <?= $no++; ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $row['nama_kelas']
                        ); ?>
                    </td>

                    <td>

                        <a
                            href="data_kelas.php?edit=<?= $row['id_kelas']; ?>"
                            class="btn-edit"
                        >
                            Edit
                        </a>

                        <a
                            href="data_kelas.php?hapus=<?= $row['id_kelas']; ?>"
                            class="btn-hapus"
                            onclick="return confirm('Yakin ingin menghapus data kelas ini?')"
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

                    <td
                        colspan="3"
                        style="text-align:center;"
                    >
                        Belum ada data kelas
                    </td>

                </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>

</html>
