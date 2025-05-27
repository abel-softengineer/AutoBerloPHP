<?php
include 'header.php';
require_once 'storage.php';

$errors = [];

$jsonIO = new JsonIO('users.json');
$userStorage = new Storage($jsonIO);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

if(isset($_POST['email']))
{
    if(empty($_POST['email']))
    {
        $errors[] = "Nincs megadva email cim!";
    }
}
if(isset($_POST['password']))
{
    if(empty($_POST['password']))
    {
        $errors[] = "Nincs megadva jelszo!";
    }
}
if(empty($errors))
{
    if(isset($_POST['email']) && isset($_POST['password']))
    {
        if($_POST['email'] == "admin@ikarrental.hu" && $_POST['password'] == "admin")
        {

            $_SESSION['user'] = [
                'name' => "admin",
                'email' => "admin@ikarrental.hu",
                'password' => "admin",
                'is_admin' => true
            ];

            echo "Admin succesful login!";

        } else{
            
            $user = $userStorage->findOne(['email' => $_POST['email']]);

            if ($user && ($_POST['password'] == $user['password'])) {
                
                $_SESSION['user'] = [
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'is_admin' => false
                ];
                echo "Sikeres bejelentkezés, " . htmlspecialchars($user['name']) . "!";
            } else {
                
                $errors[] = "Hibás e-mail cím vagy jelszó.";
            }
        } 
    }
}
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>  
<form method="POST" action="">
    <label for="email">E-mail cím:</label>
    <input type="email" name="email" id="email"  novalidate>

    <label for="password">Jelszó:</label>
    <input type="password" name="password" id="password" novalidate>

    <button type="submit">Bejelentkezek</button>

    <?php if (!empty($errors)){ ?>
            <div class="error-messages">
                <ul>
                    <?php foreach ($errors as $error){ ?>
                        <li><?php echo ($error); ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php }; ?>
</form>
</main>
</body>
</html>