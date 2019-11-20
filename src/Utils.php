<?php
/**
 * PHP version 7.3
 * src\Utils.php
 *
 * @category Utils
 * @package  MiW\Results
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

namespace MiW\Results;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use MiW\Results\Entity\User;

/**
 * Trait Utils
 *
 * @package MiW\Results
 */
trait Utils
{

    /**
     * Genera el gestor de entidades
     *
     * @return EntityManagerInterface
     */
    public static function getEntityManager(): EntityManagerInterface
    {
        if (!isset(
            $_ENV['DATABASE_HOST'], $_ENV['DATABASE_PORT'], $_ENV['DATABASE_NAME'],
            $_ENV['DATABASE_USER'], $_ENV['DATABASE_PASSWD'], $_ENV['ENTITY_DIR']
        )) {
            fwrite(STDERR, 'Faltan por definir variables de entorno');
            exit(1);
        }

        // Cargar configuración de la conexión
        $dbParams = array(
            'host'      => $_ENV['DATABASE_HOST'],
            'port'      => $_ENV['DATABASE_PORT'],
            'dbname'    => $_ENV['DATABASE_NAME'],
            'user'      => $_ENV['DATABASE_USER'],
            'password'  => $_ENV['DATABASE_PASSWD'],
            'driver'    => $_ENV['DATABASE_DRIVER'],
            'charset'   => $_ENV['DATABASE_CHARSET']
        );

        $config = Setup::createAnnotationMetadataConfiguration(
            [ $_ENV['ENTITY_DIR'] ],       // paths to mapped entities
            $_ENV['DEBUG'],                // developper mode
            ini_get('sys_temp_dir'),       // Proxy dir
            null,                          // Cache implementation
            false                          // use Simple Annotation Reader
        );
        $config->setAutoGenerateProxyClasses(true);
        if ($_ENV['DEBUG']) {
            $config->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
        }

        /** @var EntityManager $eManager */
        $eManager = null;
        try {
            $eManager = EntityManager::create($dbParams, $config);
        } catch (ORMException $e) {
            fwrite(STDERR, 'ERROR: ' . $e->getMessage() . PHP_EOL);
            exit(1);
        }

        return $eManager;
    }

    /**
     * Load the environment/configuration variables
     * defined in .env file + (.env.docker || .env.local)
     *
     * @param string $dir   project directory
     */
    public static function loadEnv(string $dir): void
    {
        /** @noinspection PhpIncludeInspection */
        require_once $dir . '/vendor/autoload.php';

        if (!class_exists(\Dotenv\Dotenv::class)) {
            fwrite(STDERR, 'ERROR: No se ha cargado Dotenv'. PHP_EOL);
            exit(1);
        }

        // Load environment variables from .env file
        if (file_exists($dir . '/.env')) {
            $dotenv = \Dotenv\Dotenv::create($dir, '.env');
            $dotenv->load();
        } else {
            fwrite(STDERR, 'ERROR: no existe el fichero .env'. PHP_EOL);
            exit(1);
        }

        // Overload with .env.docker or .env.local
        if (filter_has_var(INPUT_ENV, 'DOCKER')) {
            $dotenv = \Dotenv\Dotenv::create($dir, '.env.docker');
            $dotenv->overload();
        } elseif (file_exists($dir . '/.env.local')) {
            $dotenv = \Dotenv\Dotenv::create($dir, '.env.local');
            $dotenv->overload();
        }
    }

    /**
     * Load user data fixtures
     *
     * @param string $username user name
     * @param string $email    user email
     * @param string $password user password
     * @param bool   $isAdmin  isAdmin
     *
     * @return void
     */
    public static function loadUserData(string $username, string $email,
        string $password, bool $isAdmin = false): void {
        $user = new User(
            $username,
            $email,
            $password,
            true,
            $isAdmin
        );
        try {
            $e_manager = self::getEntityManager();
            $e_manager->persist($user);
            $e_manager->flush();
        } catch (\Exception $e) {
            fwrite(STDERR, 'EXCEPCIÓN: ' . $e->getCode() . ' - ' . $e->getMessage());
            exit(1);
        }
    }

    /**
     * Drop & Update database schema
     *
     * @return void
     */
    public static function updateSchema(): void
    {
        try {
            $e_manager = self::getEntityManager();
            $metadata = $e_manager->getMetadataFactory()->getAllMetadata();
            $sch_tool = new SchemaTool($e_manager);
            $sch_tool->dropDatabase();
            $sch_tool->updateSchema($metadata, true);
        } catch (\Exception $e) {
            fwrite(STDERR, 'EXCEPCIÓN: ' . $e->getCode() . ' - ' . $e->getMessage());
            exit(1);
        }
    }
}
