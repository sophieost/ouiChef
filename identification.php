<?php

require_once "inc/functions.inc.php";


$info = '';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);


if (!empty($_SESSION['user'])) {

    header("location:" . RACINE_SITE . "profil.php");
}

if (isset($_POST['pseudo'], $_POST['email'], $_POST['mdp'])) {

    if (!empty($_POST)) {
        // debug($_POST);

        $verif = true;

        foreach ($_POST as $value) {


            if (empty($value)) {

                $verif = false;
            }
        }


        if (!$verif) {
            // debug($_POST);


            $info = alert("Veuillez renseigner tout les champs", "transparent");
        } else {

            // debug($_POST);
            $pseudo = isset($_POST['pseudo']) ? $_POST['pseudo'] : null;
            $email = isset($_POST['email']) ? $_POST['email'] : null;
            $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : null;
            $confirmMdp = isset($_POST['confirmMdp']) ? $_POST['confirmMdp'] : null;
        }
    }

    if (strlen($pseudo) < 4) {

        $info .= alert("Le pseudo n'est pas valide.", "transparent");
    }

    if (strlen($email) > 50 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $info .= alert("L'email n'est pas valide.", "transparent");
    }

    if (strlen($mdp) < 5 || strlen($mdp) > 15) {

        $info .= alert("Le mot de passe n'est pas valide.", "transparent");
    }
    if ($mdp !== $confirmMdp) {

        $info .= alert("Le mot de passe et la confirmation doivent être identique.", "transparent");
    }

    if (empty($info)) {

        $emailExist = checkEmailUser($email);


        if ($emailExist) {

            $info = alert("Vous avez déjà un compte", "transparent");
        } else if ($mdp !== $confirmMdp) {

            $info .= alert("Le mot de passe et la confirmation doivent être identiques.", "transparent");
        } else {

            $mdp = password_hash($mdp, PASSWORD_DEFAULT);

            inscriptionUsers($pseudo, $email, $mdp);

            $info = alert('Vous êtes bien inscrit, vous pouvez vous connectez !', 'success');
        }
    }
} elseif (isset($_POST['emailConnect'], $_POST['mdpConnect'])) {



    if (!empty($_POST)) {
        // debug($_POST);

        $verif = true;

        foreach ($_POST as $value) {


            if (empty($value)) {

                $verif = false;
            }
        }
        if (!$verif) {
            // debug($_POST);


            $info = alert("Veuillez renseigner tout les champs", "transparent");
        } else {

            $emailSaisi = isset($_POST['emailConnect']) ? $_POST['emailConnect'] : null;
            $mdp = isset($_POST['mdpConnect']) ? $_POST['mdpConnect'] : null;

            $user = checkUser($emailSaisi, $mdp);
            // debug($user);

            if ($user) {

                if (password_verify($mdp, $user['mdp'])) {

                    $_SESSION['user'] = $user;

                    header("location:" . RACINE_SITE . "profil.php");
                } else {
                    $info = alert("Les identifiants sont incorrects", "transparent");
                }
            }
        }
    }
}





$title = "Identification";

require_once "inc/header.inc.php";
?>
<main>



    <section class="row">


        <div id="inscription" class="col-md-6 col-sm-12 py-5">

            <p class="text-center pt-5">Nouveau sur le site?</p>
            <h2 class="text-center">INSCRIVEZ - VOUS</h2>

            <form action="" method="post" enctype="multipart/form-data" class="mx-5">

                <div class="champs">
                    <input type="text" id="pseudo" name="pseudo" class="text-center">
                    <label for="pseudo">Pseudo</label>
                </div>
                <div class="champs">
                    <input type="email" id="email" name="email" class="text-center">
                    <label for="email">Email</label>
                </div>
                <div class="champs">
                    <input type="password" id="mdp" name="mdp" class="text-center">
                    <label for="mdp">Mot de passe</label>

                </div>
                <div class="champs">
                    <input type="password" id="confirmMdp" name="confirmMdp" class="text-center">
                    <label for="confirmMdp">Confirmez votre mot de passe</label>
                </div>

                <div class="showPassIdCon">
                    <label for="showpassConfirm" class="text-danger"><input type="checkbox" onclick="showPass()" id="showpassConfirm">Afficher/masquer le mot de passe</label>
                </div>
                
                <div class="d-flex justify-content-center my-5">
                    <button type="submit">S'inscrire</button>
                </div>


            </form>
            <?php

            echo $info;

            ?>

        </div>



        <div id="connexion" class="col-md-6 col-sm-12 py-5">

            <p class="text-center pt-5">Déjà un compte?</p>
            <h2 class="text-center">CONNECTEZ - VOUS</h2>

            <form action="" method="post" class="mx-5">

                <div class="champs">
                    <input type="email" id="emailConnect" name="emailConnect" class="text-center">
                    <label for="emailConnect">Email</label>
                </div>
                <div class="champs">
                    <input type="password" id="mdpConnect" name="mdpConnect" class="text-center">
                    <label for="mdpConnect">Mot de passe</label>
                </div>

                <div class="showPassIdCon">
                    <label for="showPassConnection" class="text-danger"><input type="checkbox" onclick="showPassConnexion()" id="showPassConnection">Afficher/masquer le mot de passe</label>
                </div>
                

                <div class="d-flex justify-content-center my-5">
                    <button type="submit">Se connecter</button>
                </div>


            </form>

            <?php

            echo $info;

            ?>
        </div>

    </section>







    <?php
    require_once "inc/footer.inc.php";

    ?>