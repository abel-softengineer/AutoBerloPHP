<?php
include 'header.php';
include 'data.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carId = $_POST['car_id'] ?? null;

    if ($carId) {

        $carStorage->delete($carId);
    }
}

header("Location: main.php");
exit;
?>
