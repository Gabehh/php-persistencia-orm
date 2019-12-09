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

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utils;

require __DIR__ . '/../vendor/autoload.php';

// Carga las variables de entorno
Utils::loadEnv(__DIR__ . '/../');

$entityManager = Utils::getEntityManager();

if ($argc < 3 || $argc > 4) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <Result> <UserId>

MARCA_FIN;
    exit(0);
}

$newResult    = (int) $argv[1];
$userId       = (int) $argv[2];
$newTimestamp = new DateTime('now');

/** @var User $user */
$user = $entityManager
    ->getRepository(User::class)
    ->findOneBy(['id' => $userId]);
if (null === $user) {
    echo "Usuario $userId no encontrado" . PHP_EOL;
    exit(0);
}

$result = new Result($newResult, $user, $newTimestamp);
try {
    $entityManager->persist($result);
    $entityManager->flush();
    echo PHP_EOL.'Result Created'.PHP_EOL;
    if($argc === 3){
        echo PHP_EOL . sprintf(
                '%3s - %3s - %22s - %s',
                'Id', 'res', 'username', 'time') . PHP_EOL;
        echo $result . PHP_EOL;
    }
    else if (in_array('--json', $argv, true)) {
        echo json_encode($result, JSON_PRETTY_PRINT);
    }
} catch (Exception $exception) {
    echo $exception->getMessage();
}



