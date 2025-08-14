<?php
header('Content-Type: application/json');

$response = ["status" => "error", "message" => "Something went wrong."];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST["name"]);
    $email    = trim($_POST["email"]);
    $comments = trim($_POST["comments"]);

    $conn = new mysqli("localhost", "root", "", "david_db");

    if ($conn->connect_error) {
        $response["message"] = "Database connection failed.";
        echo json_encode($response);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO contact_form (name, email, message) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sss", $name, $email, $comments);
        if ($stmt->execute()) {
            $response = ["status" => "success", "message" => "Your message has been saved."];
        } else {
            $response["message"] = "Failed to save your message.";
        }
        $stmt->close();
    } else {
        $response["message"] = "Database error.";
    }
    $conn->close();
}

echo json_encode($response);
?>
