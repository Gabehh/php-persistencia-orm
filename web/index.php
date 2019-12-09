<?php
/**
 * PHP version 7.3
 * demoRouting - demoRouting/src/index.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\RouteCollection;

// Directorios que contienen la definición de las rutas
const DIRECTORIES = [__DIR__ . '/../config'];

// Nombre del fichero con las rutas
const ROUTES_FILE = 'rutas.yml';

// Empleando el componente symfony/config cargamos todas las rutas
$locator = new FileLocator(DIRECTORIES);
$loader  = new YamlFileLoader($locator);
/** @var RouteCollection $routes */
$routes  = $loader->load(ROUTES_FILE);

// obtenermos el contexto de la petición HTTP
$requestContext = new RequestContext(filter_input(INPUT_SERVER, 'REQUEST_URI'));

// Busca la información de la ruta para la petición
$matcher = new UrlMatcher($routes, $requestContext);

// Trabajo hecho. Ahora mostramos la información asociada a la petición
$path_info = filter_input(INPUT_SERVER, 'PATH_INFO') ?? '/';

try {
    $parameters = $matcher->match($path_info);
    $action = $parameters['_controller'];
    // $action();   # ejecutar la acción $action()?

    echo '<pre>';
    var_dump($parameters);
    echo '</pre>';
} catch (ResourceNotFoundException $e) {
    echo 'Caught exception: The resource could not be found' . PHP_EOL;
} catch (MethodNotAllowedException $e) {
    echo 'Caught exception: the resource was found but the request method is not allowed'. PHP_EOL;
}

// El componente también sirve para mostrar la información de una ruta a partir de su nombre
echo '<br>---' . PHP_EOL . '<pre>Inverso "ruta_admin": ';
var_dump($routes->get('ruta_admin')->getPath());
echo '</pre>';
