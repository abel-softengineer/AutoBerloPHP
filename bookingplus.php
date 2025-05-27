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
<form id="booking">
    <input type="hidden" name="id" value="<?php echo $carId; ?>">
    <input type="date" id="checkin" name="checkin" novalidate> -tól
    <input type="date" id="checkout" name="checkout" novalidate> -ig
    <button type="submit">Foglalás</button>
</form>

<div id="picture" class="hidden">
    <div class="content">
        <span id="close">&times;</span>
        <p id="message"></p>
    </div>
</div>

<script src="script/ajax.js"></script>
</body>
</html>
