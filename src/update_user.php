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

if ($argc!=6) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <USERID> <USERNAME> <EMAIL> <PASSWORD> [<ENABLED>]

MARCA_FIN;
    exit(0);
}

$userId = (string) $argv[1];
$username  = (string) $argv[2];
$email  = (string) $argv[3];
$password  = (string) $argv[4];
$enabled = (boolean)$argv[5];

$user = $entityManager
    ->getRepository(User::class)
    ->findOneBy(['id' => $userId]);

if (null === $user) {
    echo "User with ID #$userId not found" . PHP_EOL;
    exit(0);
}

try {
    $user->setUsername($username);
    $user->setEmail($email);
    $user->setPassword($password);
    $user->setEnabled($enabled);
    $entityManager->flush();
    echo PHP_EOL.'Updated User:'. PHP_EOL;
    echo PHP_EOL . sprintf(
            '  %2s: %20s %30s %7s' . PHP_EOL,
            'Id', 'Username:', 'Email:', 'Enabled:'
        );
    echo sprintf(
        '- %2d: %20s %30s %7s',
        $user->getId(),
        $user->getUsername(),
        $user->getEmail(),
        ($user->isEnabled()) ? 'true' : 'false'
    ),
    PHP_EOL;
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}

