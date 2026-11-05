
<?php
require_once "db.php";

$name = "Admin GMJ";
$email = "admin@gmjlibrary.com";
$password = "admin12345";
$role = "admin";

try {
    // Cek apakah akun admin sudah ada
    $check = $pdo->prepare(
        "SELECT id FROM users WHERE email = ?"
    );
    $check->execute([$email]);

    if ($check->fetch()) {
        exit("Akun admin sudah tersedia.");
    }

    // Simpan akun admin
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare(
        "INSERT INTO users (name, email, password_hash, role)
         VALUES (?, ?, ?, ?)"
    );

    $stmt->execute([
        $name,
        $email,
        $hash,
        $role
    ]);

    echo "Akun admin berhasil dibuat!";

} catch (PDOException $e) {
    echo "Gagal membuat admin: " . $e->getMessage();
}
