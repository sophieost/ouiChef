<footer>

    <div class="container row justify-content-between mx-auto py-5">

        <div class="col-6 pt-3">
            <div class="infos">
                <h3>INFOS</h3>
                <ul class="list-unstyled">
                    <?php if (empty($_SESSION['user'])) { ?>
                        <li><a class="text-decoration-none" href="<?= RACINE_SITE ?>identification.php">Inscription</a></li>

                    <?php } else { ?>
                        <li><a class="text-decoration-none" href="<?= RACINE_SITE ?>profil.php">Mon compte</a></li>
                    <?php
                    }
                    ?>

                    <li><a class="text-decoration-none" href="">Les recettes</a></li>
                    <li><a class="text-decoration-none" href="">Conditions générales d'utilisation</a></li>
                </ul>
            </div>

            <div class="socials">
                <ul class="list-unstyled d-flex">
                    <li><a href=""><i class="bi bi-pinterest fs-3 pe-5"></i></a></li>
                    <li><a href=""><i class="bi bi-instagram fs-3 pe-5"></i></a></li>
                    <li><a href=""><i class="bi bi-facebook fs-3 pe-5"></i></a></li>
                </ul>

            </div>

        </div>

        <div class="logo2 col-6">
            <a class="" href="<?= RACINE_SITE ?>index.php"><img src="<?= RACINE_SITE ?>/assets/img/logo.png" alt="logo Oui Chef"></a>
        </div>

    </div>


</footer>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</script>
<script src="<?= RACINE_SITE ?>assets/js/index.js" defer></script>
</body>

</html>