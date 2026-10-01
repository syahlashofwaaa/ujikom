<?php
session_start();

$conn = new mysqli("localhost", "root", "", "ujikom");

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$error = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM tbl_petugas
              WHERE username = '$username'
              AND password = '$password'";

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {

        $data = $result->fetch_assoc();

        $_SESSION['id_petugas'] = $data['id_petugas'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama_petugas'] = $data['nama_petugas'];
        $_SESSION['level'] = $data['level'];

        header("Location: index.php");
        exit;

    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pembayaran</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            width: 350px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 11px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 11px;
            border: none;
            border-radius: 5px;
            background: #333;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #555;
        }

        .error {
            background: #f8d7da;
            color: #842029;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="login-box">

    <h2>Login Petugas</h2>

    <?php if ($error != "") { ?>
        <div class="error">
            <?php echo $error; ?>
        </div>
    <?php } ?>

    <form method="POST">

        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan username" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan password" required>

        <button type="submit" name="login">Login</button>

    </form>

</div>

</body>
</html>

