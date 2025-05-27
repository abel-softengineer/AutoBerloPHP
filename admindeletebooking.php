<?php
include 'header.php';
require_once 'storage.php';

$bookingId = $_POST['booking_id'] ?? null;

if ($bookingId) {
    

    $jsonIO = new JsonIO('bookings.json');
    $storage = new Storage($jsonIO);
    $bookings = $storage->findAll();

    foreach ($bookings as $index => $booking) {
        if ($booking['id'] === $bookingId) {
            $storage->delete($booking['id']);
        }
    }


    header("Location: admin.php");
    exit; 
}
?>