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

if ($argc <9 || $argc>10) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <USERID><USERNAME> <EMAIL> <ENABLED> <PASSWORD> <TOKEN> <ISADMIN> <LASTLOGIN>
MARCA_FIN;
    exit(0);
}

$userId  = (string) $argv[1];
$username  = (string) $argv[2];
$email  = (string) $argv[3];
$enabled = (boolean) $argv[4];
$password  = (string) $argv[5];
$token  = (string) $argv[6];
$isAdmin = (boolean) $argv[7];
$format = 'Y-m-d';
$date = DateTime::createFromFormat($format, (string)$argv[8]);

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
    $user->setToken($token);
    $user->setIsAdmin($isAdmin);
    $user->setLastLogin($date);
    $entityManager->flush();
    echo PHP_EOL.'User Updated'. PHP_EOL;
    if($argc===9){
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
        echo json_encode($user, JSON_PRETTY_PRINT);
    }
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}

