<?php
include "inc/header.php";
include_once('inc/config.php');

if (isset($_GET['action']) && isset($_GET['request_id'])) {
    $rId = $_GET['request_id'];

    global $pdo;

    $stmt = $pdo->prepare("UPDATE request SET status = ? WHERE id = ?;");
    $stmt->bindValue(1, 'Approved');
    $stmt->bindValue(2, $rId);
    $result = $stmt->execute();

    if ($result) {
        $_SESSION['success_msg'] = "The request have been approved successfully.";
        return true;
    } else {
        $_SESSION['error_msg'] = "Error.";
        return false;
    }
}
?>