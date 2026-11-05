
<?php
session_start();

header("Content-Type: application/json; charset=UTF-8");

if (isset($_SESSION["user_id"])) {
    echo json_encode([
        "success" => true,
        "logged_in" => true,
        "user" => [
            "id" => $_SESSION["user_id"],
            "name" => $_SESSION["name"],
            "email" => $_SESSION["email"],
            "role" => $_SESSION["role"]
        ]
    ]);
} else {
    echo json_encode([
        "success" => true,
        "logged_in" => false,
        "user" => null
    ]);
}
