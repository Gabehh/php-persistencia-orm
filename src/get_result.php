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
use MiW\Results\Utils;

require __DIR__ . '/../vendor/autoload.php';

// Carga las variables de entorno
Utils::loadEnv(__DIR__ . '/../');

$entityManager = Utils::getEntityManager();

if ($argc <2 || $argc>3) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <RESULTID>
MARCA_FIN;
    exit(0);
}

$resultId = (int) $argv[1];
$result = $entityManager
    ->getRepository(Result::class)
    ->findOneBy(['id' => $resultId]);

if (null === $result) {
    echo "Result with ID #$resultId not found" . PHP_EOL;
    exit(0);
}

if($argc === 2){
    echo PHP_EOL . sprintf(
            '%3s - %3s - %22s - %s',
            'Id', 'res', 'username', 'time') . PHP_EOL;
    echo $result . PHP_EOL;
}
else if (in_array('--json', $argv, true)) {
    echo json_encode($result, JSON_PRETTY_PRINT);
}

