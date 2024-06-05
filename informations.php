<?php

require_once "inc/functions.inc.php";




$info = '';


if (!empty($_POST)) {
    // debug($_POST);

    $verif = true;

    foreach ($_POST as $value) {

        if (empty(trim($value))) {
            $verif = false;
        }
    }

    if (!$verif) {
        $info = alert("Tous les champs sont requis", "transparent");
    } else {

        // 

        if (!isset($_POST['pseudo'])) {

            $info .= alert("Le pseudo n'est pas valide", "danger");
        }

        if (!isset($_POST['email']) || strlen($_POST['email']) > 50 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

            $info .= alert("L'email n'est pas valide", "transparent");
        }


        if (!isset($_POST['mdp']) || strlen($_POST['mdp']) < 5 || strlen($_POST['mdp']) > 15) {

            $info .= alert("Le mot de passe n'est pas valide", "danger");
        }


        //S'il n y a pas d'erreur sur le formulaire
        if (empty($info)) {

            $id = $_SESSION['user']['id'];

            $pseudo = htmlentities(trim($_POST['pseudo']));
            $email = $_POST['email'];
            $newMdp = $_POST['mdp'];


            $mdp = password_hash($newMdp, PASSWORD_DEFAULT);


            updateUser($id, $pseudo, $email, $mdp);

            $info .= alert("Vos informations ont été modifiés avec succès", "success");
        }
    }
}




$metadescription = "Prenez le contrôle de vos données sur OuiChef. Mettez à jour vos informations personnelles en toute sécurité et accédez à l'historique de vos activités culinaires pour une expérience personnalisée.";


$title = 'Gestion de Compte - Mettez à Jour Vos Informations Personnelles sur OuiChef';

require_once "inc/header.inc.php";

echo $info;

$user = $_SESSION['user'];



?>



<form action="" method="post" enctype="multipart/form-data" class="container bg-white p-5 informations">

    <div class="champs">
        <input type="text" id="pseudo" name="pseudo" class="text-center" value="<?= $user['pseudo'] ?>">
        <label for="pseudo">Pseudo</label>
    </div>
    <div class="champs">
        <input type="email" id="email" name="email" class="text-center" value="<?= $user['email'] ?>">
        <label for="email">Email</label>
    </div>
    <div class="champs">
        <input type="password" id="mdp" name="mdp" class="text-center" value="">
        <label for="mdp">Mot de passe</label>
        <p class="pt-2"><em>Entrez votre mot de passe ou un nouveau mot de passe</em></p>
    </div>

    <button type="submit" class="btn d-flex justify-content-center align-items-center w-25 mx-auto py-2 px-3 border">Modifier</button>



</form>