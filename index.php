<?php

require_once "inc/functions.inc.php";



















$title = "Accueil";
require_once "inc/header.inc.php";

?>

<main class="mainAccueil">
    <section class="generator">

        <div class="card container p-3">
            <h1 class="text-center fs-1 my-2">MON MENU</h1>
            <form action="" method="post">

                <div class="row">


                    <div class="col-md-6 px-3">
                        <div>
                            <label class="form-label fw-bold mt-3" for="start"> Date de début</label>
                            <input type="date" name="start" id="start" class="form-control">
                        </div>

                        <div>
                            <label class="form-label fw-bold mt-3" for="end"> Date de fin</label>
                            <input type="date" name="end" id="end" class="form-control">
                        </div>

                        <div class="people mt-3">
                            <label for="people" class="form-label fw-bold"> Nombre de personnes</label>
                            <input type="range" id="people" name="people" min="1" max="16" step="1">
                            <p class="text-center fs-4 w-75 d-flex justify-content-center align-items-center"><output id="value"></output><i class="bi bi-person-fill fs-4"></i></p>

                        </div>

                        <div class="time mt-3">
                            <label for="time" class="form-label fw-bold"> Temps de préparation

                                <div class="mt-2">
                                    <label for="time1" class="fs-4">
                                        <input type="checkbox" id="time1">
                                        <span class="label"><i class="bi bi-clock" data-value="1"></i></span>
                                    </label>
                                    <label for="time2" class="fs-4">
                                        <input type="checkbox" id="time2">
                                        <span class="label"><i class="bi bi-clock" data-value="2"></i></span>
                                    </label>
                                    <label for="time3" class="fs-4">
                                        <input type="checkbox" id="time3">
                                        <span class="label"><i class="bi bi-clock" data-value="3"></i></span>
                                    </label>
                                </div>


                            </label>
                        </div>

                        <div class="price mt-3">
                            <label for="price" class="form-label fw-bold text-center"> Prix

                                <div class="mt-2">
                                    <label for="price1" class="fs-3">
                                        <input type="checkbox" id="price1">
                                        <span class="label"><i class="bi bi-currency-euro" data-value="1"></i></span>
                                    </label>
                                    <label for="price2" class="fs-3">
                                        <input type="checkbox" id="price2">
                                        <span class="label"><i class="bi bi-currency-euro" data-value="2"></i></span>
                                    </label>
                                    <label for="price3" class="fs-3">
                                        <input type="checkbox" id="price3">
                                        <span class="label"><i class="bi bi-currency-euro" data-value="3"></i></span>
                                    </label>
                                </div>

                            </label>
                        </div>
                    </div>

                    <div class="col-md-6 d-flex flex-column justify-content-between px-3 repas">
                        <div class="d-flex flex-column mt-3">
                            <label for="" class=" fw-bold"> Repas
                            </label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="repas" id="dejeuner" value="dejeuner">
                                <label class="form-check-label" for="dejeuner">Déjeuner</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="repas" id="diner" value="diner">
                                <label class="form-check-label" for="diner">Dîner</label>
                            </div>

                        </div>

                        <div class="d-flex flex-column mt-3">
                            <label for="" class=" fw-bold"> Plats
                            </label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="plat" id="Entree" value="Entree">
                                <label class="form-check-label" for="Entree">Entrée</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="plat" id="plat" value="plat">
                                <label class="form-check-label" for="plat">Plat</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="plat" id="dessert" value="dessert">
                                <label class="form-check-label" for="dessert">Dessert</label>
                            </div>
                        </div>

                        <div>
                            <button class="btn bg-white btnOptions mb-5">Plus d'options <i class="bi bi-arrow-right-square"></i></button>

                        </div>
                    </div>
                </div>
                <div class="options">
                    <h2>Mes préférences</h2>
                    <div>
                        <label><input type="checkbox"><span class="label">Healthy</span></label>
                    </div>
                </div>

                <input type="submit" value="C'est parti !">
            </form>
        </div>

    </section>


    <section class="presentation container">
        <h2 class="text-center mb-5">Le concept : un générateur de menu pour trouver l'inspiration.</h2>
        <p>OUI CHEF vous suggère des menus personnalisés en fonction de vos goûts, envies, du temps que vous avez devant vous ou de votre budget.</p>
        <p>Une liste de course peut être réalisée à partir des ingrédients des recettes que vous avez choisies. Vous pouvez l'imprimer ou la retrouver sur votre espace personnel depuis votre smartphone</p>

        <h2 class="text-center my-5">Comment ça marche ? c'est simple, vous décidez :</h2>
        <ul>
            <li>Du nombre de menus.</li>
            <li>Des plats que vous souhaitez (entrée, plat, dessert).</li>
            <li>Du nombre de personnes.</li>
            <li>De vos préférences.</li>
        </ul>
        <p class="mb-5">Ensuite vous pourrez affiner les menus en modifiant un plat qui ne vous plairait pas. Vous pourrez également liker les plats qui vous ont plu ou écarter les plats qui ne vous plaisent pas. Les recettes likées seront conservées dans votre librairie.</p>
    </section>








</main>

<?php

require_once "inc/footer.inc.php";

?>