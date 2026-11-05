
<?php
session_start();
require_once "db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";

    if ($name === "" || $email === "" || $password === "") {
        $error = "Semua kolom wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (strlen($password) < 8) {
        $error = "Password minimal 8 karakter.";
    } elseif ($password !== $confirmPassword) {
        $error = "Konfirmasi password tidak sama.";
    } else {
        $check = $pdo->prepare(
            "SELECT id FROM users WHERE email = ? LIMIT 1"
        );
        $check->execute([$email]);

        if ($check->fetch()) {
            $error = "Email sudah terdaftar. Gunakan email lain.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                "INSERT INTO users (name, email, password_hash, role)
                 VALUES (?, ?, ?, 'member')"
            );

            $stmt->execute([$name, $email, $hash]);

            $success = "Registrasi berhasil! Silakan login.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - GMJ Library</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f5f6fc;
            padding: 20px;
        }

        .register-box {
            width: 100%;
            max-width: 420px;
            padding: 35px;
            background: white;
            border-radius: 18px;
            box-shadow: 0 5px 25px rgba(0,0,0,.08);
        }

        h2 {
            text-align: center;
            color: #6d35e8;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            color: #888;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 13px;
            margin-bottom: 17px;
            border: 1px solid #ddd;
            border-radius: 9px;
            font-size: 15px;
        }

        button {
            width: 100%;
            padding: 13px;
            background: #7538ed;
            color: white;
            border: none;
            border-radius: 9px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #6025d5;
        }

        .error, .success {
            padding: 11px;
            border-radius: 8px;
            margin-bottom: 16px;
            text-align: center;
        }

        .error {
            color: #dc2626;
            background: #fee2e2;
        }

        .success {
            color: #15803d;
            background: #dcfce7;
        }

        .login {
            margin-top: 20px;
            text-align: center;
        }

        a {
            color: #7538ed;
            text-decoration: none;
        }
    </style>
</head>

<body>
<div class="register-box">

    <h2>GMJ Library</h2>
    <p class="subtitle">Buat akun Member baru</p>

    <?php if ($error): ?>
        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success">
            <?= htmlspecialchars($success) ?>
            <br><br>
            <a href="login.php">Login sekarang</a>
        </div>
    <?php else: ?>

        <form method="POST">
            <label>Nama Lengkap</label>
            <input
                type="text"
                name="name"
                placeholder="Masukkan nama lengkap"
                maxlength="100"
                required
            >

            <label>Email</label>
            <input
                type="email"
                name="email"
                placeholder="Masukkan email"
                required
            >

            <label>Password</label>
            <input
                type="password"
                name="password"
                placeholder="Minimal 8 karakter"
                minlength="8"
                required
            >

            <label>Konfirmasi Password</label>
            <input
                type="password"
                name="confirm_password"
                placeholder="Ulangi password"
                minlength="8"
                required
            >

            <button type="submit">Daftar Sekarang</button>
        </form>

    <?php endif; ?>

    <div class="login">
        Sudah punya akun?
        <a href="login.php">Login</a>
    </div>

</div>
</body>
</html>
