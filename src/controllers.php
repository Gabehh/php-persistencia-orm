<?php

use MiW\Results\Entity\User;
use MiW\Results\Utils;
use MiW\Results\Entity\Result;

function funcionHomePage()
{
    global $routes;
    $routListUser = $routes->get('ruta_user_list')->getPath();
    $url = substr($routListUser, 0, strrpos($routListUser, '/'));
    $rutaNewUser = $routes->get('ruta_new_user')->getPath();
    $rutaDeleteUser = $routes->get('ruta_delete_user')->getPath();
    $rutaEditeUser = $routes->get('ruta_edit_user')->getPath();
    $routListResult = $routes->get('ruta_result_list')->getPath();
    $rutaNewResult = $routes->get('ruta_new_result')->getPath();
    $rutaDeleteResult = $routes->get('ruta_delete_result')->getPath();
    $rutaEditResult = $routes->get('ruta_edit_result')->getPath();
    echo <<< ____MARCA_FIN
    <h1>Users</h1>
    <ul>
        <li><a href="$url">Listado Users</a></li>
        <li><a href='$rutaNewUser'>Crear Usuario</a></li>
        <li><a href='$rutaEditeUser'>Editar Usuario</a></li>
        <li><a href='$rutaDeleteUser'>Eliminar Usuario</a></li>
    </ul>
    <h1>Resuls</h1>
    <ul>
        <li><a href="$routListResult">Listado Results</a></li>
        <li><a href='$rutaNewResult'>Crear Result</a></li>
        <li><a href='$rutaEditResult'>Editar Result</a></li>
        <li><a href='$rutaDeleteResult'>Delete Result</a></li>
    </ul>
____MARCA_FIN;
}


function ListUsers()
{
    $entityManager = Utils::getEntityManager();
    $users = $entityManager
        ->getRepository(User::class)
        ->findAll();
        echo '<pre>'.json_encode($users, JSON_PRETTY_PRINT).'</pre>';
}

function ListResults()
{
    $entityManager = Utils::getEntityManager();
    $results = $entityManager
        ->getRepository(Result::class)
        ->findAll();
    echo '<pre>'.json_encode($results, JSON_PRETTY_PRINT).'</pre>';
}

function DeleteUser(){
    global $routes;
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $rutaDeleteUser = $routes->get('ruta_delete_user')->getPath();
        echo <<< ___MARCA_FIN
        <form method="POST" action="$rutaDeleteUser">
            ID USER: <input type="text" name="userId"><br>
            <input type="submit" value="Enviar"> 
        </form>
    ___MARCA_FIN;
    }
    else{
        try {
            $rout = $routes->get('ruta_raíz')->getPath();
            $id = $_POST['userId'];
            $entityManager = Utils::getEntityManager();
            $user = $entityManager
                ->getRepository(User::class)
                ->findOneBy(['id' => $id]);
            if (null === $user) {
                echo "User with ID #$id not found" . PHP_EOL;
                echo "<br/>";
                echo "<a href='$rout'>Home</a>";
                exit(0);
            }
            $entityManager->remove($user);
            $entityManager->flush();
            echo "User ". $user->getUsername() ." has been deleted" . PHP_EOL;
            echo "<br/>";
            echo "<a href='$rout'>Home</a>";
        }
        catch (Exception $exception){
            echo $exception->getMessage() . PHP_EOL;
        }
    }
}

function DeleteResult(){
    global $routes;
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $rutaDeleteResult = $routes->get('ruta_delete_result')->getPath();
        echo <<< ___MARCA_FIN
        <form method="POST" action="$rutaDeleteResult">
            ID RESULT: <input type="text" name="resultId"><br>
            <input type="submit" value="Enviar"> 
        </form>
    ___MARCA_FIN;
    }
    else{
        try {
            $rout = $routes->get('ruta_raíz')->getPath();
            $id = $_POST['resultId'];
            $entityManager = Utils::getEntityManager();
            $result = $entityManager
                ->getRepository(Result::class)
                ->findOneBy(['id' => $id]);
            if (null === $result) {
                echo "Result with ID #$id not found" . PHP_EOL;
                echo "<br/>";
                echo "<a href='$rout'>Home</a>";
                exit(0);
            }
            $entityManager->remove($result);
            $entityManager->flush();
            echo "Result with ID #". $id ." has been deleted" . PHP_EOL;
            echo "<br/>";
            echo "<a href='$rout'>Home</a>";
        }
        catch (Exception $exception){
            echo $exception->getMessage() . PHP_EOL;
        }
    }

}

function EditUser(){
    global $routes, $context;
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $rutaEditUser = $routes->get('ruta_edit_user')->getPath();
        echo <<< ___MARCA_FIN
        <form method="POST" action="$rutaEditUser">
            ID USER: <input type="text" name="userId"><br>
            <input type="submit" value="Enviar"> 
        </form>
    ___MARCA_FIN;

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){
        try {
            $rout = $routes->get('ruta_user_edited')->getPath();
            $idUser = $_POST['userId'];
            $entityManager = Utils::getEntityManager();
            $user = $entityManager
                ->getRepository(User::class)
                ->findOneBy(['id' => $idUser]);
            if (null === $user) {
                echo "User with ID #$idUser not found" . PHP_EOL;
                echo "<br/>";
                echo "<a href='/'>Home</a>";
                exit(0);
            }
            $id = $user->getId();
            $name = $user->getUsername();
            $email = $user->getEmail();
            $token = $user->getToken();
            $enable = $user->isEnabled() ? "checked" : "";
            $admin  = $user->isAdmin() ? "checked" : "";
            echo <<< ___MARCA_FIN
            <form method="POST" action="$rout">
                <input type="text" name="id" value="$id" style="display: none"><br>
                UserName: <input type="text" name="username" value="$name"><br>
                Email: <input type="text" name="email" value="$email"><br>
                Token: <input type="text" name="token" value="$token"><br>
                Enable: <input type="checkbox" name="enable" $enable><br>
                Admin: <input type="checkbox" name="admin" $admin><br>
                <input type="submit" value="Enviar"> 
            </form>
        ___MARCA_FIN;
        }
        catch (Exception $exception)
        {
            echo $exception->getMessage() . PHP_EOL;
        }
    }

}


function EditResult(){
    global $routes, $context;
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $rutaEditResult = $routes->get('ruta_edit_result')->getPath();
        echo <<< ___MARCA_FIN
        <form method="POST" action="$rutaEditResult">
            ID RESULT: <input type="text" name="resultId"><br>
            <input type="submit" value="Enviar"> 
        </form>
    ___MARCA_FIN;

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){
        try {
            $idResult = $_POST['resultId'];
            $rout = $routes->get('ruta_result_edited')->getPath();
            $entityManager = Utils::getEntityManager();
            $result = $entityManager
                ->getRepository(Result::class)
                ->findOneBy(['id' => $idResult]);
            $users = $entityManager
                ->getRepository(User::class)
                ->findAll();
            if (null === $result) {
                echo "Result with ID #$idResult not found" . PHP_EOL;
                echo "<br/>";
                echo "<a href='/'>Home</a>";
                exit(0);
            }
            $id = $result->getId();
            $_result = $result->getResult();
            $userId = $result->getUser()->getId();
            $time = $result->getTimestamp()->format('Y-d-m');

            echo <<< ___MARCA_FIN
            <form method="POST" action="$rout">
                <input type="text" name="id" value="$id" style="display: none"><br>
                Result: <input type="text" name="result" value=$_result><br>
                User:             
                <select name="user">
            ___MARCA_FIN;
            foreach ($users as $clave => $valor) {
                $id =$valor->getId();
                $user = $valor->getUsername();
                if($userId==$id) {
                    echo "<option value=$id selected='selected' >$user</option>";
                }
                else{
                    echo "<option value=$id>$user</option>";
                }
            }

            echo <<< ___MARCA_FIN
                </select><br>
               
                Time: <input type="date" name="time" value="$time"><br>
                <input type="submit" value="Enviar"> 
            </form>
            ___MARCA_FIN;
        }
        catch (Exception $exception)
        {
            echo $exception->getMessage() . PHP_EOL;
        }
    }

}

function UserEdited(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        global $routes;
        try {
            $entityManager = Utils::getEntityManager();
            $id = $_POST['id'];
            $user = $entityManager
                ->getRepository(User::class)
                ->findOneBy(['id' => $id]);
            $username = $_POST['username'];
            $email = $_POST['email'];
            $isAdmin = $_POST['admin'] ?? 0;
            $token = $_POST['token'];
            $enabled = $_POST['enable'] ?? 0;
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setToken($token);
            $user->setEnabled($enabled);
            $user->setIsAdmin($isAdmin);
            $entityManager->flush();
            echo 'User Edited' . PHP_EOL;
            echo '<pre>' . json_encode($user, JSON_PRETTY_PRINT) . '</pre>';
            $routListUser = $routes->get('ruta_user_list')->getPath();
            $url = substr($routListUser, 0, strrpos($routListUser, '/'));
            echo "<a href='$url'>Listado Usuarios</a>";
        }
        catch (Exception $exception){
            echo $exception->getMessage() . PHP_EOL;
        }
    }
}

function ResultEdited(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        global $routes;
        try {
            $entityManager = Utils::getEntityManager();
            $id = $_POST['id'];
            $editResult = $entityManager
                ->getRepository(Result::class)
                ->findOneBy(['id' => $id]);
            $result = $_POST['result'];
            $userId = $_POST['user'];
            $time = $_POST['time'];
            $user = $entityManager
                ->getRepository(User::class)
                ->findOneBy(['id' => $userId]);
            $editResult->setResult($result);
            $editResult->setTimestamp(new DateTime($time));
            $editResult->setUser($user);
            $entityManager->flush();
            echo 'Result Edited' . PHP_EOL;
            echo '<pre>' . json_encode($editResult, JSON_PRETTY_PRINT) . '</pre>';
            $routListResult = $routes->get('ruta_result_list')->getPath();
            echo "<a href='$routListResult'>Listado Result</a>";
        }
        catch (Exception $exception){
            echo $exception->getMessage() . PHP_EOL;
        }
    }
}


function NewUser()
{
    global $routes;
    if ($_SERVER['REQUEST_METHOD'] === 'GET') { // método GET => muestro formulario
        $rutaNewUser = $routes->get('ruta_new_user')->getPath();
        echo <<< ___MARCA_FIN
    <form method="POST" action="$rutaNewUser">
        UserName: <input type="text" name="username"><br>
        Email: <input type="text" name="email"><br>
        Password: <input type="password" name="password"><br>
        Token: <input type="text" name="token"><br>
        Enable: <input type="checkbox" name="enable"><br>
        Admin: <input type="checkbox" name="admin"><br>
        <input type="submit" value="Enviar"> 
    </form>
___MARCA_FIN;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {    // método POST => proceso formulario
        try {
            $entityManager = Utils::getEntityManager();
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $token = $_POST['token'];
            $enabled = $_POST['enable'] ?? false;
            $isAdmin = $_POST['admin'] ?? false;
            $user = new User();
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setEnabled($enabled);
            $user->setToken($token);
            $user->setLastLogin(new DateTime('now'));
            $user->setIsAdmin($isAdmin);
            $entityManager->persist($user);
            $entityManager->flush();
            echo 'User Created' . PHP_EOL;
            echo '<pre>' . json_encode($user, JSON_PRETTY_PRINT) . '</pre>';
            $routListUser = $routes->get('ruta_user_list')->getPath();
            $url = substr($routListUser, 0, strrpos($routListUser, '/'));
            echo "<a href='$url'>Listado Usuarios</a>";
        }
        catch (Exception $exception){
            echo $exception->getMessage() . PHP_EOL;
        }
    }
}


function NewResult()
{
    global $routes;
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $rutaNewResult = $routes->get('ruta_new_result')->getPath();
        $entityManager = Utils::getEntityManager();
        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();
        echo <<< ___MARCA_FIN
        <form method="POST" action="$rutaNewResult">
        Result : <input type="text" name="result"><br>
        User: 
        <select name="userId">
        ___MARCA_FIN;
        foreach ($users as $clave => $valor) {
            $id =$valor->getId();
            $user = $valor->getUsername();
            echo "<option value=$id>$user</option>";
        }
        echo <<< ___MARCA_FIN
        </select><br>
        Time: <input type="date" name="time"><br>
        <input type="submit" value="Enviar"> 
        </form>
        ___MARCA_FIN;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {    // método POST => proceso formulario
        try {

            $entityManager = Utils::getEntityManager();
            $result = $_POST['result'];
            $userId = $_POST['userId'];
            $time = $_POST['time'];
            $newResult = new Result();
            $user = $entityManager
                ->getRepository(User::class)
                ->findOneBy(['id' => $userId]);
            $newResult->setResult($result);
            $newResult->setTimestamp(new DateTime($time));
            $newResult->setUser($user);
            $entityManager->persist($newResult);
            $entityManager->flush();
            echo 'Result Created' . PHP_EOL;
            echo '<pre>' . json_encode($newResult, JSON_PRETTY_PRINT) . '</pre>';
            $routListResult = $routes->get('ruta_result_list')->getPath();
            echo "<a href='$routListResult'>Listado Result</a>";
        }
        catch (Exception $exception){
            echo $exception->getMessage() . PHP_EOL;
        }
    }
}