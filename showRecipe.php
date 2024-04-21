<?php

require_once "inc/functions.inc.php";



if (!empty($_GET)) {

    $id = intval($_GET['id']); // Convertit l'ID en entier

    $recipes = showRecipe($id);

    if ($_GET['id'] != $recipes['id']) {

        header("location:" . RACINE_SITE . "index.php");
    } else {

        $instructions = stringToArray($recipes['instructions']);
    }
}

$info = '';



$title = "Recette";
require_once "inc/header.inc.php";



?>


<section id="showRecipe">

    <div class="container d-flex justify-content-between align-items-center my-5">
        <h2><?= $recipes['name'] ?></h2>

        <ul class="list-unstyled mt-3 d-flex jsutify-content-around infosCard">

            <?php if (isset($recipes['price'])) : ?>
                <li class="px-3">

                    <?php
                    switch ($recipes['price']) {
                        case 'bonMarche':
                            echo 'Bon marché <i class="bi bi-currency-euro fs-4"></i>';
                            break;
                        case 'raisonnable':
                            echo 'Raisonnable <i class="bi bi-currency-euro fs-4"></i><i class="bi bi-currency-euro fs-4"></i>';
                            break;
                        case 'cher':
                            echo 'Cher <i class="bi bi-currency-euro fs-4"></i><i class="bi bi-currency-euro fs-4"></i><i class="bi bi-currency-euro fs-4"></i>';
                            break;
                    }
                    ?>
                </li>
            <?php endif; ?>

            <?php if (isset($recipes['time'])) : ?>
                <li class="px-3">
                    <?= ucfirst($recipes['time']) ?>
                    <?php
                    switch ($recipes['time']) {
                        case 'rapide':
                            echo '<i class="bi bi-clock fs-4"></i>';
                            break;
                        case 'moyen':
                            echo '<i class="bi bi-clock fs-4"></i><i class="bi bi-clock fs-4"></i>';
                            break;
                        case 'long':
                            echo '<i class="bi bi-clock fs-4"></i><i class="bi bi-clock fs-4"></i><i class="bi bi-clock fs-4"></i>';
                            break;
                    }
                    ?>
                </li>
            <?php endif; ?>

            <li>
                <span>Ajouter aux favoris</span>
                <a href="recette.php?action=add&id=<?= $recipes['id'] ?>" class="linkFav"><i class="bi bi-heart fs-4 iconFav text-dark"></i></a>
            </li>
        </ul>

    </div>

    <div>
        <div>
        <img src="<?= RACINE_SITE . "assets/img/" . $recipes['image'] ?>" class="card-img h-50" alt="image de . <?= $recipes['name'] ?>">
        </div>

        <div class="ingredients">
            <h3>INGREDIENTS</h3>

            <form action="" method="post">

            
            </form>

        </div>
    </div>



</section>





<?php
require_once "inc/footer.inc.php";


?>