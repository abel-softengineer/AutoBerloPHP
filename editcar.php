<?php
include 'header.php';
require_once 'storage.php';



    $jsonIO = new JsonIO('cars.json');
    $carStorage = new Storage($jsonIO);
    $cars = $carStorage->findAll();

$errors = [];

$id = $_GET['id'];
$car = $cars[$id];





if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand'] ?? $car['brand'];
    $model = $_POST['model'] ?? $car['model'];
    $year = $_POST['year'] ?? $car['year'];
    $price = $_POST['price'] ?? $car['price'];
    $seats = $_POST['seats'] ?? $car['seats'];
    $transmission = $_POST['transmission'] ?? $car['transmission'];
    $fuel_type = $_POST['fuel_type'] ?? $car['fuel_type'];
    $image = $_POST['image'] ?? $car['image'];


    if(isset($brand) && empty($brand))
    {
        $errors[] = "Márka nincs kitöltve!";
    }


    if (empty($model)) {
        $errors[] = "Modell nincs kitöltve!";
    }

    if (empty($year)) {
        $errors[] = "Évjárat nincs kitöltve!";
    } elseif (!is_numeric($year)) {
        $errors[] = "Évjárat érvénytelen!";
    }

    if (empty($price)) {
        $errors[] = "Ár nincs kitöltve!";
    } elseif (!is_numeric($price) || $price <= 0) {
        $errors[] = "Ár érvénytelen!";
    }

    if (empty($seats)) {
        $errors[] = "Ülések száma nincs kitöltve!";
    } elseif (!is_numeric($seats) || $seats <= 0) {
        $errors[] = "Ülések száma érvénytelen!";
    }

    if (empty($transmission) || !in_array($transmission,['manual','automatic'])) {
        $errors[] = "Váltó nincs kitöltve!";
    }
    
    if (empty($fuel_type)|| !in_array($fuel_type,['diesel','petrol','electric'])) {
        $errors[] = "Üzemanyag típus nincs kitöltve!";
    }
        
    if (empty($image)) {
        $errors[] = "Kép URL nincs kitöltve!";
    }

        

    if(empty($errors))
    {
        $cars[$id] = [
            'car_id'=>$car['car_id'],
            'id' => $id, 
            'brand' => $brand,
            'model' => $model,
            'year' => (int) $year,
            'price' => (int) $price,
            'seats' => (int) $seats,
            'transmission' => $transmission,
            'fuel_type' => $fuel_type,
            'image' => $image,
        ];

        $carStorage->update($id, $cars[$id]);
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autó Szerkesztése</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Autó Szerkesztése: <?php echo ($car['model']); ?></h1>
    <?php
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo("<li id='error'>$error</li>");
        }
    }
?>
    <form method="POST">
        <label for="brand">Márka:</label>
        <input type="text" novalidate id="brand" name="brand" value="<?php echo($car['brand']); ?>">

        <label for="model">Modell:</label>
        <input type="text" novalidate id="model" name="model" value="<?php echo($car['model']); ?>">

        <label for="year">Évjárat:</label>
        <input type="number" novalidate id="year" name="year" value="<?php echo($car['year']); ?>">

        <label for="price">Ár:</label>
        <input type="number" novalidate id="price" name="price" value="<?php echo($car['price']); ?>">

        <label for="seats">Ülések száma:</label>
        <input type="number" novalidate id="seats" name="seats" value="<?php echo($car['seats']); ?>">

        <label for="fuel_type">Váltó:</label>
        <select novalidate name="transmission">
            <option value="manual">Manuális</option>
            <option value="automatic">Automata</option>
        </select>
        <label for="fuel_type">Üzemanyag típusa:</label>
        <select novalidate name="fuel_type" >
            <option value="electric">Elektromos</option>
            <option value="diesel">Dízel</option>
            <option value="petrol">Benzin</option>
        </select>

        <label for="image">Kép URL:</label>
        <input type="text" novalidate id="image" name="image" value="<?php echo($car['image']); ?>">

        <button type="submit">Mentés</button>
    </form>
    <a href="admin.php" class="btn">Vissza az admin oldalra</a>
    
</body>
</html>
