<?php
/**
 * PHP version 7.3
 * web/index.php
 *
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es ETS de Ingeniería de Sistemas Informáticos
 */

require_once '../vendor/autoload.php';
require_once './controllers.php';

use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use MiW\Results\Utils;

Utils::loadEnv(__DIR__ . '/../');
const DIRECTORIES = [ __DIR__ . '/../config' ];

$locator = new FileLocator(DIRECTORIES);
$loader  = new YamlFileLoader($locator);
$routes  = $loader->load($_ENV['ROUTES_FILE']);
$context = new RequestContext(filter_input(INPUT_SERVER, 'REQUEST_URI'));
$matcher = new UrlMatcher($routes, $context);
$path_info = filter_input(INPUT_SERVER, 'PATH_INFO') ?? '/';

try {
    $parameters = $matcher->match($path_info);
    $action = $parameters['_controller'];
    $param1 = $parameters['json'] ?? null;
    $action($param1);
} catch (ResourceNotFoundException $e) {
    echo 'Caught exception: The resource could not be found' . PHP_EOL;
} catch (MethodNotAllowedException $e) {
    echo 'Caught exception: the resource was found but the request method is not allowed'. PHP_EOL;
}

