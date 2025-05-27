<?php
session_start(); 
?>
<header>
    <h1>iKarRental</h1>
    <div class="header-buttons">
        <?php if (isset($_SESSION['user'])): ?>
            <span>Üdv, <?php echo $_SESSION['user']['name']; ?>!</span>
            <a href="index.php"><button class="btn">Főmenü</button></a>

            <?php if(!$_SESSION['user']['is_admin']) { ?>
                <a href="profile.php"><button class="btn">Profil</button></a>
            <?php } else { ?>
                <a href="admin.php"><button class="btn">Admin Panel</button></a>
            <?php } ?>
            <a href="logout.php"><button class="btn">Kijelentkezés</button></a>
        <?php else: ?>
            <a href="main.php"><button class="btn">Vissza</button></a>
            <a href="login.php"><button class="btn">Bejelentkezés</button></a>
            <a href="register.php"><button class="btn">Regisztráció</button></a>
        <?php endif; ?>
    </div>
</header>
