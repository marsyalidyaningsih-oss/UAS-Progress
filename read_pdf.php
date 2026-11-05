
<?php
session_start();

// 1. Pastikan pengguna sudah login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// 2. Hubungkan ke database
require_once __DIR__ . "/db.php";

// 3. Ambil ID buku dari URL
if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    http_response_code(400);
    exit("ID buku tidak valid.");
}

$bookId = $_GET["id"];

// 4. Ambil nama file PDF dari database
$stmt = $pdo->prepare(
    "SELECT title, pdf_file FROM books WHERE id = :id LIMIT 1"
);
$stmt->execute(["id" => $bookId]);

$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    http_response_code(404);
    exit("Buku tidak ditemukan.");
}

// 5. Periksa ketersediaan PDF
if (empty($book["pdf_file"])) {
    http_response_code(404);
    exit("PDF buku ini belum tersedia.");
}

// 6. Pastikan file berada di folder pdf
$fileName = basename($book["pdf_file"]);
$filePath = __DIR__ . "/pdf/" . $fileName;

$realPdfDir = realpath(__DIR__ . "/pdf");
$realFilePath = realpath($filePath);

if (
    $realPdfDir === false ||
    $realFilePath === false ||
    !is_file($realFilePath) ||
    dirname($realFilePath) !== $realPdfDir
) {
    http_response_code(404);
    exit("File PDF tidak ditemukan.");
}

// 7. Tampilkan PDF langsung di browser
header("Content-Type: application/pdf");
header('Content-Disposition: inline; filename="' . basename($fileName) . '"');
header("Content-Length: " . filesize($realFilePath));
header("X-Content-Type-Options: nosniff");

readfile($realFilePath);
exit;
?>
