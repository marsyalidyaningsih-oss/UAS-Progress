
<?php
session_start();
require_once "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $pdo->prepare(
        "SELECT id, name, email, password_hash, role
         FROM users WHERE email = ? LIMIT 1"
    );
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password_hash"])) {
        session_regenerate_id(true);

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["name"] = $user["name"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["role"] = $user["role"];

        header("Location: index.html");
        exit;
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GMJ Library</title>
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
        }

        .login-box {
            width: 90%;
            max-width: 400px;
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

        p {
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
            margin-bottom: 18px;
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

        .error {
            color: #dc2626;
            background: #fee2e2;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
        }

        .register {
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

<div class="login-box">
    <h2>GMJ Library</h2>
    <p>Silakan login untuk melanjutkan</p>

    <?php if ($error): ?>
        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Email</label>
        <input type="email" name="email"
               placeholder="Masukkan email"
               required>

        <label>Password</label>
        <input type="password" name="password"
               placeholder="Masukkan password"
               required>

        <button type="submit">Login</button>
    </form>

    <div class="register">
        Belum punya akun?
        <a href="register.php">Daftar sekarang</a>
    </div>
</div>

</body>
</html>
