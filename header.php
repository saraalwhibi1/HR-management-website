<?php
session_start();
$current_user = false;

if (isset($_SESSION['user_id'])) {
    $current_user = array();
    $current_user['id'] = $_SESSION['user_id'];
    $current_user['first_name'] = $_SESSION['user_first_name'];
    $current_user['last_name'] = $_SESSION['user_last_name'];
    $current_user['type'] = $_SESSION['user_type'];
    $current_user['homepage'] = $_SESSION['user_homepage'];
}
?>