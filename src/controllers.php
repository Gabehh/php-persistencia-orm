<?php

use MiW\Results\Entity\User;
use MiW\Results\Utils;

function funcionHomePage()
{
    echo <<< ____MARCA_FIN
        <h1>hola</h1>
    ____MARCA_FIN;

}


function ListUsers(?string $json = null)
{
    $entityManager = Utils::getEntityManager();
    $users = $entityManager
        ->getRepository(User::class)
        ->findAll();
    echo ($json)
        ? json_encode($users, JSON_PRETTY_PRINT)
        : var_dump($users);
}