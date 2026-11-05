
<?php
header("Content-Type: application/json; charset=UTF-8");

require_once "../db.php";

try {
    $query = $pdo->query("
        SELECT
            id,
            title,
            author,
            category,
            publication_year AS year,
            pages,
            image,
            synopsis,
            pdf_file
        FROM books
        ORDER BY id ASC
    ");

    $books = $query->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "total" => count($books),
        "data" => $books
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Gagal mengambil data buku"
    ]);
}
