<?php

namespace Core;

class Enrutador
{
    private $rutas = [];

    public function agregar($metodo, $ruta, $controlador, $accion)
    {
        $this->rutas[] = [
            'metodo' => $metodo,
            'ruta' => $ruta,
            'controlador' => $controlador,
            'accion' => $accion
        ];
    }

    public function despachar($uri, $metodo)
    {
        $uri = strtok($uri, '?');
        $uri = rtrim($uri, '/');
        if (empty($uri)) $uri = '/';
        if ($uri !== '/' && $uri[0] !== '/') {
            $uri = '/' . $uri;
        }

        foreach ($this->rutas as $ruta) {
            if ($ruta['metodo'] !== $metodo) continue;

            $patron = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_-]+)', $ruta['ruta']);
            $patron = '#^' . $patron . '$#';

            if (preg_match($patron, $uri, $matches)) {
                array_shift($matches);
                
                $nombreControlador = 'Controllers\\' . $ruta['controlador'];
                if (class_exists($nombreControlador)) {
                    $controlador = new $nombreControlador();
                    return $controlador->{$ruta['accion']}(...$matches);
                }
            }
        }

        http_response_code(404);
        echo "404 - Página no encontrada";
    }
}