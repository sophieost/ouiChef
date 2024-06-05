<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "inc/functions.inc.php";

if (!isset($_SESSION['user'])) {
    header("location: identification.php");
    exit();
}

$nb_jours = 0; // Initialisation de la variable avec une valeur par défaut
$nb_pers = 0;

if (!empty($_GET['action']) && $_GET['action'] == 'add' && !empty($_GET['id_menu'])) {

    $menu_id = $_GET['id_menu'];


    $infosMenu = getMenuInfoById($menu_id);

    $nb_jours = $infosMenu['nb_jours'];
    $nb_pers = $infosMenu['nb_pers'];


    $recettes = getRecipeNamesForMenu($menu_id);

    $entrees = array_filter($recettes, function ($recette) {
        return $recette['typePlat'] === 'entree';
    });

    $plats = array_filter($recettes, function ($recette) {
        return $recette['typePlat'] === 'plat';
    });

    $desserts = array_filter($recettes, function ($recette) {
        return $recette['typePlat'] === 'dessert';
    });
}

if (empty($entrees) && empty($plats) && empty($desserts)) {
    $info =  "Il n'existe aucune recette avec les critères sélectionnés.";
}



$title =  "Menus Sur Mesure - Découvrez Votre Plan Alimentaire Personnalisé";

$metadescription = "Créez un menu adapté à vos goûts et besoins nutritionnels. Sélectionnez vos critères et laissez OuiChef composer pour vous des menus quotidiens personnalisés et équilibrés.";


require_once "inc/header.inc.php";
?>
<main id="menus">

    <div class="d-flex justify-content-between menuHeader p-5">
        <h2 class="ms-md-5">Mon menu</h2>
        <button class="btn me-md-5"><a href="<?= RACINE_SITE ?>liste.php">Voir ma liste de courses</a></button>
    </div>



    <section id="menus" class="container">

        <div class="row my-5">
            <?php for ($jour = 1; $jour <= $nb_jours; $jour++) : ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                    <div class="card rounded-4">
                        <h2 class="card-title rounded-4 p-3 titreMenu">Jour <?= $jour ?> </h2>
                        <div class="card-body">

                            <?php if (!empty($entrees)) : ?>
                                <h4>Entrée</h4>
                                <?php $entree = current($entrees); // Récupère l'entrée courante 
                                ?>
                                <a href="#" data-id="<?= $entree['id'] ?>" data-menu-id="<?= $menu_id ?>" class="recipe-link">
                                    <p class="card-text"><?= htmlspecialchars_decode($entree['name']) ?></p>
                                </a>
                                <?php next($entrees); // Passe à l'entrée suivante 
                                ?>
                            <?php endif; ?>

                            <?php if (!empty($plats)) : ?>
                                <h4>Plat</h4>
                                <?php $plat = current($plats); // Récupère le plat courant 
                                ?>
                                <a href="#" data-id="<?= $plat['id'] ?>" data-menu-id="<?= $menu_id ?>" class="recipe-link">
                                    <p class="card-text"><?= htmlspecialchars_decode($plat['name']) ?></p>
                                </a>
                                <?php next($plats); // Passe au plat suivant 
                                ?>
                            <?php endif; ?>

                            <?php if (!empty($desserts)) : ?>
                                <h4>Dessert</h4>
                                <?php $dessert = current($desserts); // Récupère le dessert courant 
                                ?>
                                <a href="#" data-id="<?= $dessert['id'] ?>" data-menu-id="<?= $menu_id ?>" class="recipe-link">
                                    <p class="card-text"><?= htmlspecialchars_decode($dessert['name']) ?></p>
                                </a>
                                <?php next($desserts); // Passe au dessert suivant 
                                ?>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>



        <div id="recipe-content" class="pb-5">

        </div>

    </section>

</main>




<script>

    
    //   APPARITION DES RECETTES SUR LA PAGE MENUS  //


    document.addEventListener('DOMContentLoaded', function() {
    let recipeLinks = document.querySelectorAll('.recipe-link');

    recipeLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            let recipeId = this.getAttribute('data-id');
            let menuId = this.getAttribute('data-menu-id');

            fetch('showRecipe.php?id=' + recipeId + '&menu_id=' + menuId)
                .then(response => response.text())
                .then(data => {
                    let recipeContent = document.getElementById('recipe-content');
                    recipeContent.innerHTML = data;

                    let navbarHeightVh = 10;

                    let navbarHeightPx = window.innerHeight * (navbarHeightVh / 100);

                    let position = recipeContent.getBoundingClientRect().top + window.pageYOffset - navbarHeightPx;
                    window.scrollTo({
                        top: position,
                        behavior: 'smooth'
                    });
                })
                .catch(error => console.error('Error:', error));
        });
    });

    document.body.addEventListener('submit', function(e) {
        if (e.target.matches('.ingredientsRecipe')) {
            e.preventDefault();

            // Créer un objet FormData à partir du formulaire
            let formData = new FormData(e.target);

            // Créer une requête fetch pour envoyer les données du formulaire
            fetch('showRecipe.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.text();
            })
            .then(data => {

                let messageSuccess = document.getElementById('message-success');
                messageSuccess.style.display = 'block';

                setTimeout(function() {
                    messageSuccess.style.display = 'none';
                }, 5000);
            })
            .catch(error => console.error('Error:', error));
        }
    });
});


</script>

<?php

require_once "inc/footer.inc.php";

?>