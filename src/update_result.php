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

if ($argc < 5 || $argc > 6) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <ResultId> <Result> <UserId> <Timestamp>

MARCA_FIN;
    exit(0);
}

$resultId = (int) $argv[1];
$newResult = (int) $argv[2];
$userId  = (int) $argv[3];
$date = DateTime::createFromFormat('Y-m-d', (string)$argv[4]);
$newTimestamp = $date;

$result = $entityManager
    ->getRepository(Result::class)
    ->findOneBy(['id' => $resultId]);

if (null === $result) {
    echo "Result with ID #$resultId not found" . PHP_EOL;
    exit(0);
}

$user = $entityManager
    ->getRepository(User::class)
    ->findOneBy(['id' => $userId]);

if (null === $user) {
    echo "User with ID #$userId not found" . PHP_EOL;
    exit(0);
}

try {
    $result->setResult($newResult);
    $result->setUser($user);
    $result->setTimestamp($newTimestamp);
    $entityManager->flush();
    echo PHP_EOL.'Updated Result:'. PHP_EOL;
    if($argc === 5){
        echo PHP_EOL . sprintf(
                '%3s - %3s - %22s - %s',
                'Id', 'res', 'username', 'time') . PHP_EOL;
        echo $result . PHP_EOL;
    }
    else if (in_array('--json', $argv, true)) {
        echo json_encode($result, JSON_PRETTY_PRINT);
    }
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}