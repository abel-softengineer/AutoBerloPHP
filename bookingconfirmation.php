<?php
include 'header.php';
require_once 'storage.php';
include 'data.php';

$errors = [];

$carId = $_GET['id'] ?? null;
$checkin = $_GET['checkin'] ?? null;
$checkout = $_GET['checkout'] ?? null;
$email = $_SESSION['user']['email'] ?? null; 

$car = null;
foreach ($cars as $carData) {
    if ($carData['car_id'] == $carId) { 
        $car = $carData;
    }
}

$status = false;

if ($checkin && $checkout && $carId) {
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
        
        $daysBooked = (strtotime($checkout) - strtotime($checkin)) / (60 * 60 * 24);
        $totalPrice = $daysBooked * $car['price'];

        
        $booking = [
            'car_id' => $carId,
            'checkin' => $checkin,
            'checkout' => $checkout,
            'email' => $email
        ];

        $storage->add($booking); 
        $status = true;
    } else {
        $errors[] = "Foglalt az időpont, válassz másikat!";
    }
}

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foglalás eredménye</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>Foglalás eredménye</h1>

<?php if ($status): ?>
    <p style="color: green;">Foglalás sikeres!</p>
    <p><strong>Autó: <?php echo $car['brand']; ?> <?php echo $car['model']; ?></strong></p>
    <p>Foglalás dátuma: <?php echo $checkin; ?> - <?php echo $checkout; ?></p>
    <p>Összeg: <?php echo $totalPrice; ?> Ft</p>
    <a href="profile.php" class="btn">Profilomhoz</a>
<?php else: ?>
    <p id='error'>A foglalás nem sikerült!</p>
    <p><?php echo $errors[0]; ?></p>
    <a href="index.php" class="btn">Vissza a főoldalra</a>
<?php endif; ?>



</body>
</html>
