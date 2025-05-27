<?php
require_once 'storage.php';
include 'data.php';
session_start(); 

header('Content-Type: application/json');

$errors = [];
$response = ['success' => false];

$carId = $_POST['id'] ?? null;
$checkin = $_POST['checkin'] ?? null;
$checkout = $_POST['checkout'] ?? null;
$email = $_SESSION['user']['email'] ?? null;

if ( empty($checkin) || empty($checkout)) {
    $response['error'] = "Minden mezőt ki kell tölteni!";
    echo json_encode($response);
    exit;
}

if (strtotime($checkin) > strtotime($checkout)) {
    $response['error'] = "Az első dátum nem lehet későbbi, mint a második!";
    echo json_encode($response);
    exit;
}

$jsonIO = new JsonIO('bookings.json');
$storage = new Storage($jsonIO);

$existingBookings = $storage->findAll();
$isAvailable = true;


foreach ($existingBookings as $booking) {
    if ($booking['car_id'] === $carId) {
        if (!($checkin > $booking['checkout'] || $checkout < $booking['checkin'])) {
            $isAvailable = false;
        }
    }
}

if ($isAvailable) {
    $days = (strtotime($checkout) - strtotime($checkin)) / (60 * 60 * 24);
                                                            //strtotime masodpercben adja meg, ezert kell az osztas
    $car = null;

    foreach($cars as $car2)
    {
        if($car2['car_id'] == $carId)
        {
            $car = $car2;
        }
    }


        
        $totalPrice = $days * $car['price'];

        
        $storage->add([
            'car_id' => $carId,
            'checkin' => $checkin,
            'checkout' => $checkout,
            'email' => $email
        ]);

        $response['success'] = true;
        $response['carId'] = $carId;
        $response['checkin'] = $checkin;
        $response['checkout'] = $checkout;
        $response['totalPrice'] = $totalPrice;
    } else {
    $response['error'] = "Az időpont már foglalt.";
}

echo json_encode($response);
