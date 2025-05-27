

<?php 

include 'header.php';
require_once 'storage.php';


    $jsonIO = new JsonIO('users.json');
    $userStorage = new Storage($jsonIO);


$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(isset($_POST['fullname']))
    {
        $fullname = trim($_POST['fullname']);
    }else
    {
        $fullname = "";
    }
    if(isset($_POST['email']))
    {
        $email = trim($_POST['email']);
    }else
    {
        $email = "";
    }
    if(isset($_POST['password']))
    {
        $password = $_POST['password'];
    }else
    {
        $password = "";
    }
    if(isset($_POST['confirm_password']))
    {
        $confirm_password = $_POST['confirm_password'];
    } else
    {
        $confirm_password = "";
    }


    if (empty($fullname)) {
        $errors[] = "A teljes név megadása kötelező.";
    } else if(!str_contains($fullname, ' '))
    {
        $errors[] = "Nincs szóköz a névben!";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Érvényes e-mail címet kell megadni.";
    }
    if (empty($password)) {
        $errors[] = "A jelszó megadása kötelező.";
    } elseif (strlen($password) < 8) {
        $errors[] = "A jelszónak legalább 8 karakter hosszúnak kell lennie.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "A jelszavak nem egyeznek.";
    }

    $users = $userStorage->findAll(); 
    $emailExists = false;
    foreach ($users as $user) {
        
        if ($user['email'] === $email) {
            $emailExists = true;

        }
    }

    if($emailExists)
    {
        $errors [] = "Ezzel az email címmel már regisztráltak!";
    }

    if(empty($errors))
    {
        $user = [
            'name' => $fullname,
            'email' => $email,
            'password' => $password ,
            'is_admin' => false
        ];

        $userStorage->add($user);
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

<h1>Regisztráció</h1>
<?php if (!empty($errors)): ?>
    <div class="error-messages">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>


<form method="POST" action="">
    <label for="fullname">Teljes név:</label>
    <input type="text" name="fullname" id="fullname">

    <label for="email">E-mail cím:</label>
    <input type="email" name="email" id="email">

    <label for="password">Jelszó:</label>
    <input type="password" name="password" novalidate id="password">

    <label for="confirm_password">Jelszó megerősítése:</label>
    <input type="password" name="confirm_password" novalidate id="confirm_password">

    <button type="submit">Regisztráció</button>
</form>
</main>




</body>
</html>


