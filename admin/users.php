<?php
$title = "Users";
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
                <th scope="col">Rôle</th>
                <th scope="col">Supprimer</th>
                <th scope="col">Modifier le rôle</th>
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
                    <td><?= $user['role'] ?></td>

                    <td class="text-center">
                        <a href="dashboard.php?users_php&action=delete&id=<?= $user['id'] ?>"><i class="bi bi-trash3-fill text-danger"></i></a>
                    </td>

                    <td class="text-center">
                        <a href="dashboard.php?users_php&action=update&id=<?= $user['id'] ?>" class="btn btn-danger"><?= ($user['role']) == 'admin' ? 'Rôle user' : 'Rôle admin' ?>
                    </td>
                </tr>


            <?php
            }
            ?>
        </tbody>
    </table>

</div>