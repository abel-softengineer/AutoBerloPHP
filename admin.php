<?php
include 'header.php';
include 'data.php';

$errors = [];
$filteredCars = $cars;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carId = $_POST['car_id'] ?? '';
    $brand = $_POST['brand'] ?? '';
    $model = $_POST['model'] ?? '';
    $year = $_POST['year'] ?? '';
    $price = $_POST['price'] ?? '';
    $seats = $_POST['seats'] ?? '';
    $transmission = $_POST['transmission'] ?? '';
    $fuel_type = $_POST['fuel_type'] ?? '';
    $image = $_POST['image'] ?? '';

    if (isset($carId) && empty($carId)) {
        $errors[] = "Nincs megadva az auto idje!";
    }


    if (isset($brand) && empty($brand)) {
        $errors[] = "A márka megadása kötelező!";
    }

    if (isset($model) && empty($model)) {
        $errors[] = "A modell megadása kötelező!";
    }

    if (isset($year) && empty($year)) {
        $errors[] = "Az évjárat megadása kötelező!";
    }

    if (isset($price) && empty($price)) {
        $errors[] = "Az ár megadása kötelező!";
    }

    if (isset($seats) && empty($seats)) {
        $errors[] = "A férőhelyek száma megadása kötelező!";
    }

    if (isset($transmission) && empty($transmission)) {
        $errors[] = "A sebességváltó típus megadása kötelező!";
    } elseif (isset($transmission) && !in_array($transmission, ['manual', 'automatic'])) {
        $errors[] = "Érvénytelen sebességváltó típus!";
    }


    if (isset($fuel_type) && empty($fuel_type)) {
        $errors[] = "Az üzemanyag típus megadása kötelező!";
    } elseif (isset($fuel_type) && !in_array($fuel_type, ['petrol', 'electric', 'diesel'])) {
        $errors[] = "Érvénytelen üzemanyag típus!";
    }


    if (isset($image) && empty($image)) {
        $errors[] = "A kép elérési útvonalának megadása kötelező!";
    }
    if (empty($errors)) {



        $existingCar = $carStorage->findById($carId);
        if ($existingCar) {
            $errors[] = "Ez az ID már létezik, válassz másikat!";
        } else {



            $newCar = [
                'car_id' => $carId,
                'brand' => $brand,
                'model' => $model,
                'year' => (int) $year,
                'price' => (int) $price,
                'seats' => (int) $seats,
                'transmission' => $transmission,
                'fuel_type' => $fuel_type,
                'image' => $image,
            ];
            $carStorage->add($newCar);
            $success = "Az autó sikeresen létrehozva!";

        }
    }
}
?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Új autó</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>Új autó hozzáadása</h1>

    <?php if (!empty($errors)) { ?>

        <ul>
            <?php foreach ($error as $errors) { ?>
                <li><?php echo $error; ?></li>
            <?php } ?>
        </ul>

    <?php } ?>

    <form method="POST">
        <input type="text" novalidate name="car_id" placeholder="Autó IDje" novalidate>
        <input type="text" novalidate name="brand" placeholder="Márka">
        <input type="text" novalidate name="model" placeholder="Modell">
        <input type="number" novalidate name="year" placeholder="Évjárat">
        <input type="number" novalidate name="price" placeholder="Ár (Ft)">
        <input type="number" novalidate name="seats" placeholder="Férőhelyek száma">
        <label for="transmission">Sebességváltó típus:</label>
        <select novalidate name="transmission">
            <option value="manual">Manuális</option>
            <option value="automatic">Automata</option>
        </select>
        <label for="fuel_type">Üzemanyag típusa:</label>
        <select novalidate name="fuel_type">
            <option value="electric">Elektromos</option>
            <option value="diesel">Dízel</option>
            <option value="petrol">Benzin</option>
        </select>
        <input type="text" novalidate name="image" placeholder="Kép elérési útvonala">
        <button type="submit">Hozzáadás</button>
    </form>

    <h2>Meglévő autók kezelése</h2>

    <div class="grid">
        <?php foreach ($filteredCars as $index => $car) { ?>
            <div class="car-card">
                <a href="car.php?id=<?php echo $index; ?>">
                    <img src="<?php echo $car['image']; ?>" alt="<?php echo $car['model']; ?>">
                    <h3><?php echo $car['model']; ?></h3>
                </a>
                <p>Napidíj: <?php echo $car['price']; ?> Ft</p>
                <p>Férőhelyek: <?php echo $car['seats']; ?> - Váltó: <?php echo $car['transmission']; ?></p>

                <form method="POST" action="deletecar.php">
                    <input type="hidden" novalidate name="car_id" value="<?php echo $car['id']; ?>">
                    <button type="submit">Törlés</button>
                </form>

                <form method="POST" action="editcar.php?id=<?php echo $car['id'] ?>">
                    <input type="hidden" name="car_id" novalidate value="<?php echo $car['id'] ?>">
                    <button type="submit">Szerkesztés</button>
                </form>
            </div>
        <?php } ?>
    </div>

    <?php
    $jsonIO = new JsonIO('bookings.json');
    $storage = new Storage($jsonIO);
    $bookings = $storage->findAll();
    ?>

    <h2>Foglalások kezelése</h2>

    <div class="grid">
        <?php foreach ($bookings as $booking) { ?>
            <div class="car-card">

                <p><strong>Car ID:</strong> <?php echo $booking['car_id']; ?></p>
                <p><strong>Check-in:</strong> <?php echo $booking['checkin']; ?></p>
                <p><strong>Check-out:</strong> <?php echo $booking['checkout']; ?></p>
                <p><strong>Email:</strong> <?php echo $booking['email']; ?></p>


                <form method="POST" action="admindeletebooking.php">
                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                    <button type="submit">Foglalás törlése</button>
                </form>
            </div>
        <?php } ?>
    </div>










</body>

</html>