<?php
namespace App\Models;

class Router
{
    private array $routes;

    public function __construct()
    {
        $this->routes = [
            'login/'    => __DIR__ . '/../view/js/RenderCompontentLogin.js',
            'register/' => __DIR__ . '/../view/js/RenderCompontentRegister.js',
            'index/'    => __DIR__ . '/../view/index.php',
        ];
    }

    /**
     * Devuelve la ruta del archivo a incluir o servir.
     */
    public function resolve(string $route): string
    {
        $route = rtrim($route, '/') . '/';

        if (array_key_exists($route, $this->routes) && file_exists($this->routes[$route])) {
            return $this->routes[$route];
        }

        // Si la ruta no existe, devolvemos por defecto index.php
        return __DIR__ . '/../view/index.php';
    }

    /**
     * Procesa la ruta:
     * - Si es index/, carga index.php normalmente.
     * - Si es otra ruta conocida, devuelve el JS con el header correcto.
     * - Si no existe, devuelve index.php.
     */
    public function handle(string $route): void
    {
        $route = rtrim($route, '/') . '/';
        $path = $this->resolve($route);

        if ($route === 'index/') {
            require_once $path;
        } elseif (str_ends_with($path, '.js') && file_exists($path)) {
            header('Content-Type: application/javascript; charset=UTF-8');
            readfile($path);
        } else {
            // fallback: mostrar index.php
            require_once __DIR__ . '/../view/index.php';
        }
    }
}
?>

