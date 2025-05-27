<?php 
include 'header.php';
require_once 'storage.php';
include 'data.php';

$errors = [];

$id = $_GET['id'] ?? null;
$carId = $id;

$status = false;

$car = null;
foreach ($cars as $carData) {
    if ($carData['car_id'] == $carId) { 
        $car = $carData;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foglalás</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1><?php echo $car['model']; ?></h1>
    <img src="<?php echo $car['image']; ?>" alt="<?php echo $car['model']; ?>">
    <p>Típus: <?php echo $car['brand']; ?></p>
    <p>Férőhelyek: <?php echo $car['seats']; ?></p>
    <p>Évjárat: <?php echo $car['year']; ?></p>
    <p>Ár: <?php echo $car['price']; ?></p>
    <p>Kuplung típusa: <?php echo $car['transmission']; ?></p>
    

    <h2>Foglalása</h2>
    <form action="bookingconfirmation.php" method="get">
    <input type="hidden" name="id" novalidate value="<?php echo $carId; ?>">
    <input type="date" id="checkin" name="checkin" novalidate> -tól
    <input type="date" id="checkout" name="checkout" novalidate> -ig
    <button type="submit">Foglalás</button>
</form>

</body>
</html>

<?php 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $checkintime = $_POST['checkin'] ?? null;
    $checkouttime = $_POST['checkout'] ?? null;

    if (!$checkintime || !$checkouttime) {
        $errors[] = "Kérlek válassz dátumot mindkét mezőhöz!";
    }

    if ($checkintime && $checkouttime) {
        if (strtotime($checkintime) > strtotime($checkouttime)) {
            $errors[] = "Nem jó időpont, az dátum később van mint a második!";
        }
    }

    if (empty($errors)) {
        
        $jsonIO = new JsonIO('bookings.json');
        $storage = new Storage($jsonIO);

        $existingBookings = $storage->findAll();
        $isAvailable = true;

        foreach ($existingBookings as $booking) {
            if ($booking['car_id'] === $carId) { 
                if (!($checkintime > $booking['checkout'] || $checkouttime < $booking['checkin'])) {
                    $isAvailable = false;
                   
                }
            }
        }

        if ($isAvailable) {
            $booking = [
                'car_id' => $carId,
                'checkin' => $checkintime,
                'checkout' => $checkouttime,
                'email' => $_SESSION['user']['email']
            ];

            $storage->add($booking);
            $status = true;
            echo "<p id='green'>Foglalás sikeres!</p>";
        } else {
            $errors[] = "Foglalt az idopont, válassz másikat!";
        }
    }

    foreach ($errors as $error) {
        ?>
        <p id='error'><?php $error ?> </p>";
   <?php }
}

?>
