<?php
/**
 * PHP version 7.3
 * src\create_result.php
 *
 * @category Utils
 * @package  MiW\Results
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

use MiW\Results\Entity\User;
use MiW\Results\Utils;

require __DIR__ . '/../vendor/autoload.php';

// Carga las variables de entorno
Utils::loadEnv(__DIR__ . '/../');

$entityManager = Utils::getEntityManager();

if ($argc !=2) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <USERID>
MARCA_FIN;
    exit(0);
}

$userId = (int) $argv[1];
$user = $entityManager
    ->getRepository(User::class)
    ->findOneBy(['id' => $userId]);

if (null === $user) {
    echo "User with ID #$userId not found" . PHP_EOL;
    exit(0);
}

try {
    $entityManager->remove($user);
    $entityManager->flush();
    echo "User ". $user->getUsername() ." has been deleted" . PHP_EOL;
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}



