<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM customer WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Respond with success
        echo json_encode(['status' => 'success']);
    } else {
        // Respond with error
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
