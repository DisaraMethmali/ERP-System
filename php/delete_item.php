<?php
include 'db_connect.php'; // Include the database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the item
    $stmt = $conn->prepare("DELETE FROM item WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Item deleted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
header("Location: view_items.php");
exit;
?>
