<?php
require_once "functions.inc.php";


// déconnexion ($_SESSION)
logOut();

if (isset($_SESSION['user'])){
$user_id = $_SESSION['user']['id'];
$menu_id = getLastMenuIdByUserId($user_id);
}

?>

<!-- header.inc.php  -->
<!doctype html>
<html lang="fr">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Projet de soutenance : Oui Chef!">
    <meta name="author" content="Sophie Ostermeyer">
    <!-- Bootstrap CSS v5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font family -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Icons Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- mon style -->
    <link rel="stylesheet" href="<?= RACINE_SITE ?>assets/css/style.css">

    <title><?= $title ?></title>
</head>

<body>
    <header>

        <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
            <div class="container">
                <a class="logo1" href="<?= RACINE_SITE ?>index.php"><img src="<?= RACINE_SITE ?>/assets/img/logo.png" alt="logo Oui Chef"></a>
                <button class="navbar-toggler mb-5" type="button" data-bs-toggle="collapse" data-bs-target="#navbarOuiChef" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse align-items-end justify-content-end" id="navbarOuiChef">

                    <ul class="navbar-nav mb-2 mb-lg-0 ml-auto align-items-end me-5">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="<?= RACINE_SITE ?>menus.php">Mes Menus</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= RACINE_SITE ?>liste.php">Ma liste de courses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= RACINE_SITE ?>recettes.php">Les recettes</a>
                        </li>
                    </ul>
                    
                    <ul class="navbar-nav mb-2 mb-lg-0 ml-auto me-5 align-items-end">
                        <?php if (empty($_SESSION['user'])) { ?>
                            <li class="nav-item" ><a class="nav-link" href="<?= RACINE_SITE ?>identification.php"><i class="bi bi-person-circle"></i></a></li>

                        <?php } else { ?>
                            <li class="nav-item"><a class="nav-link" href="<?= RACINE_SITE ?>profil.php"><i class="bi bi-person-circle"></i></a></li>

                            <?php if ($_SESSION['user']['role'] == 'admin') { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= RACINE_SITE ?>admin/dashboard.php">Backoffice</a>
                                </li>

                            <?php } 
                            ?>

                            <li class="nav-item">
                                <a class="nav-link" href="?action=deconnexion"><i class="bi bi-box-arrow-right"></i></a>
                            </li>

                        <?php } ?>
                    </ul>

                </div>
            </div>
        </nav>
    </header>