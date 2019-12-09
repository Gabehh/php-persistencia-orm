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

if ($argc <7 || $argc>8) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <USERNAME> <EMAIL> <ENABLED> <PASSWORD> <TOKEN> <ISADMIN>

MARCA_FIN;
    exit(0);
}

$username  = (string) $argv[1];
$email  = (string) $argv[2];
$enabled = (boolean) $argv[3];
$password  = (string) $argv[4];
$token  = (string) $argv[5];
$isAdmin = (boolean) $argv[6];


$user = new User();
$user->setUsername($username);
$user->setEmail($email);
$user->setPassword($password);
$user->setEnabled($enabled);
$user->setToken($token);
$user->setLastLogin(new DateTime('now'));
$user->setIsAdmin($isAdmin);

try {
    $entityManager->persist($user);
    $entityManager->flush();
    if($argc===7){
        echo PHP_EOL . sprintf(
                '  %2s: %20s %30s %7s %7s %7s %25s' . PHP_EOL,
                'Id', 'Username:', 'Email:', 'Enabled:', 'Admin:', "Token:", "Last Login:"
            );
        echo sprintf(
            '- %2d: %20s %30s %7s %7s %7s %28s',
            $user->getId(),
            $user->getUsername(),
            $user->getEmail(),
            ($user->isEnabled()) ? 'true' : 'false',
            ($user->isAdmin()) ? 'true' : 'false',
            $user->getToken(),
            $user->getLastLogin()
        ),
        PHP_EOL;
    } else if (in_array('--json', $argv, true)) {
        echo PHP_EOL.'User Created'. PHP_EOL;
        echo json_encode($user, JSON_PRETTY_PRINT);
    }

} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}

