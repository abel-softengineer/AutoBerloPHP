<?php
include 'data.php';
include 'header.php';

$id = $_GET['id'] ?? null;


$car = null;

foreach($cars as $car2)
{
    if($car2['car_id'] == $id)
    {
        $car = $car2;
    }
}

?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $car['model']; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1><?php echo $car['model']; ?></h1>
    <img src="<?php echo $car['image']; ?>" alt="<?php echo $car['model']; ?>">
    <p>Típus: <?php echo $car['brand']; ?></p>
    <p>Férőhelyek: <?php echo $car['seats']; ?></p>
    <p>Évjárat: <?php echo $car['year']; ?></p>
    <p>Ár: <?php echo $car['price']; ?> Ft </p>
    <p>Kuplung típusa: <?php echo $car['transmission']; ?></p>
    <p>Üzemanyag típusa: <?php echo $car['fuel_type'];  ?></p>

</body>
</html>