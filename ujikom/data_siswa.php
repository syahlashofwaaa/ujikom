<?php

session_start();

// ==========================
// KONEKSI DATABASE
// ==========================
$conn = mysqli_connect("localhost", "root", "", "ujikom");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}


// ==========================
// TAMBAH DATA
// ==========================
if (isset($_POST['tambah'])) {

    $nis            = $_POST['nis'];
    $nama_siswa     = $_POST['nama_siswa'];
    $jenis_kelamin  = $_POST['jenis_kelamin'];
    $alamat         = $_POST['alamat'];
    $no_telp        = $_POST['no_telp'];
    $id_kelas       = $_POST['id_kelas'];

    $query = mysqli_query($conn, "
        INSERT INTO tbl_siswa
        (nis, nama_siswa, jenis_kelamin, alamat, no_telp, id_kelas)
        VALUES
        ('$nis', '$nama_siswa', '$jenis_kelamin', '$alamat', '$no_telp', '$id_kelas')
    ");

    if ($query) {
        header("Location: data_siswa.php");
        exit;
    } else {
        echo "Gagal menambahkan data: " . mysqli_error($conn);
    }
}


// ==========================
// HAPUS DATA
// ==========================
if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    mysqli_query($conn, "
        DELETE FROM tbl_siswa
        WHERE id_siswa='$id'
    ");

    header("Location: data_siswa.php");
    exit;
}


// ==========================
// DATA UNTUK EDIT
// ==========================
$edit_data = null;

if (isset($_GET['edit'])) {

    $id = $_GET['edit'];

    $query_edit = mysqli_query($conn, "
        SELECT * FROM tbl_siswa
        WHERE id_siswa='$id'
    ");

    $edit_data = mysqli_fetch_assoc($query_edit);
}


// ==========================
// UPDATE DATA
// ==========================
if (isset($_POST['update'])) {

    $id             = $_POST['id_siswa'];
    $nis            = $_POST['nis'];
    $nama_siswa     = $_POST['nama_siswa'];
    $jenis_kelamin  = $_POST['jenis_kelamin'];
    $alamat         = $_POST['alamat'];
    $no_telp        = $_POST['no_telp'];
    $id_kelas       = $_POST['id_kelas'];

    $query = mysqli_query($conn, "
        UPDATE tbl_siswa SET
            nis='$nis',
            nama_siswa='$nama_siswa',
            jenis_kelamin='$jenis_kelamin',
            alamat='$alamat',
            no_telp='$no_telp',
            id_kelas='$id_kelas'
        WHERE id_siswa='$id'
    ");

    if ($query) {
        header("Location: data_siswa.php");
        exit;
    } else {
        echo "Gagal mengubah data: " . mysqli_error($conn);
    }
}


// ==========================
// AMBIL DATA KELAS
// ==========================
$data_kelas = mysqli_query($conn, "
    SELECT * FROM tbl_kelas
    ORDER BY nama_kelas ASC
");


// ==========================
// AMBIL DATA SISWA
// ==========================
$data_siswa = mysqli_query($conn, "
    SELECT * FROM tbl_siswa
   ORDER BY nisn DESC
");

if (!$data_siswa) {
    die("Query data siswa gagal: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Siswa</title>

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

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group textarea {
            height: 80px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }


        /* =========================
           BUTTON
        ========================= */

        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }

        .btn-tambah {
            background: #333;
            color: white;
        }

        .btn-update {
            background: #333;
            color: white;
        }

        .btn-batal {
            background: #777;
            color: white;
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
            min-width: 900px;
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
           AKSI
        ========================= */

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


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 900px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .content {
                margin-left: 0;
            }

            .form-row {
                grid-template-columns: 1fr;
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

    <a href="data_siswa.php" class="active">
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


<!-- =========================
     CONTENT
========================= -->

<div class="content">


    <!-- HEADER -->

    <div class="header">

        <h1>Data Siswa</h1>

        <p>
            Kelola data siswa
        </p>

    </div>


    <!-- =========================
         FORM TAMBAH / EDIT
    ========================= -->

    <div class="form-box">

        <?php if ($edit_data) { ?>

            <h2>Edit Data Siswa</h2>

            <form method="POST">

                <input
                    type="hidden"
                    name="id_siswa"
                    value="<?= $edit_data['id_siswa']; ?>"
                >

                <div class="form-row">

                    <div class="form-group">

                        <label>NIS</label>

                        <input
                            type="text"
                            name="nis"
                            value="<?= htmlspecialchars($edit_data['nis']); ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>Nama Siswa</label>

                        <input
                            type="text"
                            name="nama_siswa"
                            value="<?= htmlspecialchars($edit_data['nama_siswa']); ?>"
                            required
                        >

                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group">

                        <label>Jenis Kelamin</label>

                        <select name="jenis_kelamin" required>

                            <option value="">
                                -- Pilih Jenis Kelamin --
                            </option>

                            <option value="Laki-laki"
                                <?= ($edit_data['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>
                                Laki-laki
                            </option>

                            <option value="Perempuan"
                                <?= ($edit_data['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>
                                Perempuan
                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>No. Telepon</label>

                        <input
                            type="text"
                            name="no_telp"
                            value="<?= htmlspecialchars($edit_data['no_telp']); ?>"
                        >

                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group">

                        <label>Kelas</label>

                        <select name="id_kelas" required>

                            <option value="">
                                -- Pilih Kelas --
                            </option>

                            <?php

                            mysqli_data_seek($data_kelas, 0);

                            while ($kelas = mysqli_fetch_assoc($data_kelas)) {

                            ?>

                                <option
                                    value="<?= $kelas['id_kelas']; ?>"
                                    <?= ($edit_data['id_kelas'] == $kelas['id_kelas']) ? 'selected' : ''; ?>
                                >
                                    <?= htmlspecialchars($kelas['nama_kelas']); ?>
                                </option>

                            <?php } ?>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>Alamat</label>

                        <textarea name="alamat"><?= htmlspecialchars($edit_data['alamat']); ?></textarea>

                    </div>

                </div>


                <button
                    type="submit"
                    name="update"
                    class="btn btn-update"
                >
                    Update Data
                </button>


                <a
                    href="data_siswa.php"
                    class="btn btn-batal"
                >
                    Batal
                </a>

            </form>


        <?php } else { ?>


            <h2>Tambah Data Siswa</h2>

            <form method="POST">


                <div class="form-row">

                    <div class="form-group">

                        <label>NIS</label>

                        <input
                            type="text"
                            name="nis"
                            placeholder="Masukkan NIS"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>Nama Siswa</label>

                        <input
                            type="text"
                            name="nama_siswa"
                            placeholder="Masukkan nama siswa"
                            required
                        >

                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group">

                        <label>Jenis Kelamin</label>

                        <select name="jenis_kelamin" required>

                            <option value="">
                                -- Pilih Jenis Kelamin --
                            </option>

                            <option value="Laki-laki">
                                Laki-laki
                            </option>

                            <option value="Perempuan">
                                Perempuan
                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>No. Telepon</label>

                        <input
                            type="text"
                            name="no_telp"
                            placeholder="Masukkan nomor telepon"
                        >

                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group">

                        <label>Kelas</label>

                        <select name="id_kelas" required>

                            <option value="">
                                -- Pilih Kelas --
                            </option>

                            <?php

                            while ($kelas = mysqli_fetch_assoc($data_kelas)) {

                            ?>

                                <option value="<?= $kelas['id_kelas']; ?>">

                                    <?= htmlspecialchars($kelas['nama_kelas']); ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>Alamat</label>

                        <textarea
                            name="alamat"
                            placeholder="Masukkan alamat siswa"
                        ></textarea>

                    </div>

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
         TABEL DATA SISWA
    ========================= -->

    <div class="table-box">

        <h2>Daftar Siswa</h2>

        <table>

            <tr>

                <th>No</th>

                <th>NIS</th>

                <th>Nama Siswa</th>

                <th>Jenis Kelamin</th>

                <th>Kelas</th>

                <th>No. Telepon</th>

                <th>Alamat</th>

                <th>Aksi</th>

            </tr>


            <?php

            $no = 1;

            if (mysqli_num_rows($data_siswa) > 0) {

                while ($row = mysqli_fetch_assoc($data_siswa)) {

            ?>

                    <tr>

                        <td>
                            <?= $no++; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['nis']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['nama_siswa']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['jenis_kelamin']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['nama_kelas'] ?? '-'); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['no_telp']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['alamat']); ?>
                        </td>

                        <td>

                            <a
                                href="data_siswa.php?edit=<?= $row['id_siswa']; ?>"
                                class="btn-edit"
                            >
                                Edit
                            </a>

                            <a
                                href="data_siswa.php?hapus=<?= $row['id_siswa']; ?>"
                                class="btn-hapus"
                                onclick="return confirm('Yakin ingin menghapus data siswa ini?')"
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
                        colspan="8"
                        style="text-align:center;"
                    >
                        Belum ada data siswa
                    </td>

                </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>

</html>
