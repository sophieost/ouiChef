<?php

require_once "../inc/functions.inc.php";

$metadescription = "Consultez et organisez vos recettes préférées et celles bloquées sur OuiChef. Accédez facilement à vos coups de cœur culinaires et gérez votre liste de recettes à éviter.";

$title = 'Vos Favoris et Blacklist - Gérez Vos Recettes sur OuiChef';

require_once "../inc/header.inc.php";




?>


<div class="d-flex flex-column m-5 table-responsive" id="users">
    <h2 class="text-center fw-bolder mb-5">Liste des utilisateurs</h2>
    

    <table class="table border-dark">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Pseudo</th>
                <th scope="col">Email</th>
                <th scope="col">Supprimer</th>
                <th scope="col">Rôle</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">


            <?php
            $users = allUsers();

            foreach ($users as $user) {


            ?>
                <tr>
                    <th scope="row"><?= $user['id'] ?></th>
                    <td><?= $user['pseudo'] ?></td>
                    <td><?= $user['email'] ?></td>

                    <td>
                        <a href="dashboard.php?users_php&action=delete&id=<?= $user['id'] ?>"><i class="bi bi-trash-fill"></i></a>
                    </td>

                    <td>
                        <a href="dashboard.php?users_php&action=update&id=<?= $user['id'] ?>" class="btn btnRole me-5">modifier</a>
                        <?= ($user['role']) == 'admin' ? 'Rôle admin' : 'Rôle user' ?>
                        
                    </td>
                </tr>


            <?php
            }
            ?>
        </tbody>
    </table>

</div>