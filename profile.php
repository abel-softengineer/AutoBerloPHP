<?php 
include 'header.php';
include 'storage.php'; 
include 'data.php'; 



$userEmail = $_SESSION['user']['email'] ?? null;


$jsonIO = new JsonIO('bookings.json');
$storage = new Storage($jsonIO);
$bookings = $storage->findAll();


$userBookings = array_filter($bookings, function ($booking) use ($userEmail) {
    return $booking['email'] === $userEmail;
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <img id="profile-picture" src="pictures/profile.jpg" alt="Profilkép">
            <h2>Bejelentkezve, mint</h2>
            <h1><?php echo $_SESSION['user']['name']; ?></h1>
        </div>
        <div class="grid">
            <h2>Foglalásaim</h2>
            <div class="car-card">
                <?php if (empty($userBookings)) { ?>
                    <p>Nincs foglalásod.</p>
                <?php }else{ ?>
                    <?php foreach ($userBookings as $booking){ ?>
                        <?php 
                        
                        $car = array_filter($cars, fn($c) => $c['car_id'] == $booking['car_id']);
                        $car = reset($car); 
                        ?>
                        <div class="card">
                            <img src="<?php echo $car['image']; ?>" alt="<?php echo $car['model']; ?>">
                            <div class="card-details">
                                <h3><?php echo $car['model']; ?></h3>
                                <p><?php echo $car['seats']; ?> férőhely - <?php echo $car['transmission']; ?></p>
                                <span><?php echo $booking['checkin']; ?> – <?php echo $booking['checkout']; ?></span>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
