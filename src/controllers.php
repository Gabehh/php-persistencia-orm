<?php


function funcionHomePage()
{
    global $routes;

    $rutaListado = $routes->get('ruta_list')->getPath();

    $url = substr($rutaListado, 0, strrpos($rutaListado, '/'));
    echo <<< ____MARCA_FIN
    <ul>
        <li><a href="$url">Listado</a></li>
    </ul>
____MARCA_FIN;

}