<?php
include 'data.php'; 
include 'header.php'; 
require_once 'storage.php';



$jsonIO = new JsonIO('bookings.json');
$storage = new Storage($jsonIO);
$filteredCars = $cars;


$carsDone = $cars;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $minPrice = $_GET['min_price'] ?? null;
    $maxPrice = $_GET['max_price'] ?? null;
    $seats = $_GET['seats'] ?? null;
    $transmission = $_GET['transmission'] ?? null;
    $startDate = $_GET['start_date'] ?? null;
    $endDate = $_GET['end_date'] ?? null;

    $filteredCars = [];

    foreach($carsDone as $car)
    {
        if(filterCars($car,$minPrice,$maxPrice,$seats,$transmission,$startDate,$endDate))
        {
            $filteredCars[] = $car;
        }

    }



}


function filterCars($car, $minPrice, $maxPrice, $seats, $transmission, $startDate, $endDate) {
    $isValid = true;

    if (isset($minPrice) && (! empty($minPrice))) {
       

        if($car['price'] < $minPrice)
        {
        
        $isValid = false;
        }
    }
    
   
    if (isset($maxPrice) && (! empty($maxPrice))) {
        if($car['price'] > $maxPrice)
        {
        $isValid = false;
        }
    }
    
    
    if (isset($seats) && (! empty($seats)) && $car['seats'] < $seats) {
        
        
        $isValid = false;
        
    }
    
    
    if (isset($transmission) && $transmission !== null) {
        if ($car['transmission'] != $transmission && $transmission !== 'none') {
            $isValid = false;
        }
    }

    if( isset($startDate) && isset($endDate))
    {
        if(!filterCarsHelper($car, $startDate, $endDate))
        {
            $isValid = false;
        }
    }
    
    return $isValid;
}



function filterCarsHelper($car, $startDate, $endDate) {

    $isAvailable = true;
    $jsonIO2 = new JsonIO('bookings.json');
    $storage2 = new Storage($jsonIO2);
    
    $existingBookings = $storage2->findAll(['car_id' => $car['car_id']]);

    foreach ($existingBookings as $booking) {    
        if (!($startDate > $booking['checkout'] || $endDate < $booking['checkin'])) {
            $isAvailable = false;
        }
    }

    return $isAvailable;
}




?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iKarRental - Autók listája</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<main>
    <h2>Kölcsönözz autókat könnyedén!</h2>
    <form method="GET" class="filter-form">
        <label for="min_price">Minimum napidíj:</label>
        <input type="number" name="min_price" novalidate id="min_price">

        <label for="max_price">Maximum napidíj:</label>
        <input type="number" name="max_price" novalidate id="max_price">

        <label for="seats">Férőhelyek száma:</label>
        <input type="number" name="seats" novalidate id="seats">

        <label for="transmission">Váltó típusa:</label>
        <select name="transmission" id="transmission" novalidate >
            <option value="none">Nincs szűrve</option>
            <option value="automatic">Automata</option>
            <option value="manual">Manuális</option>
        </select>

        <input type="date" name="start_date" novalidate id="start_date"> -tól

        <input type="date" name="end_date" novalidate id="end_date"> -ig

        <button type="submit">Szűrés</button>
    </form>

    <div class="grid">
        <?php foreach ($filteredCars as $car){ ?>
            <div class="car-card">
                <a href="car.php?id=<?php echo $car['car_id']; ?>">
                    <img src="<?php echo $car['image']; ?>" alt="<?php echo $car['model']; ?>">
                    <h3><?php echo $car['model']; ?></h3>
                </a>
                <p>Napidíj: <?php echo $car['price']; ?> Ft</p>
                <p>Váltó: <?php echo $car['transmission']; ?> -- Ülőhelyek száma: <?php echo $car['seats']; ?></p>
                <?php if (isset($_SESSION['user'])){ ?>
                    <form method="POST" action="bookingplus.php?id=<?php echo $car['car_id']; ?>">
                    <input type="hidden" name="car_id" novalidate value="<?php echo $car['car_id']; ?>">
                    <button type="submit">Foglalás</button>
</form>


<?php }else{ ?>
    <form method="POST" action="register.php">
    <input type="hidden" name="car_id" novalidate value="<?php echo $car['car_id']; ?>">
    <button type="submit">Foglalás</button>
<?php } ?>
            </div>
        <?php } ?>
    </div>
</main>
</body>
</html>
