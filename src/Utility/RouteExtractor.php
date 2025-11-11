<?php
declare(strict_types=1);

namespace Iserter\PostmanGenerator\Utility;

use Cake\Routing\Router;
use Cake\Routing\Route\RouteInterface; // kept for real usage but not enforced strictly in tests
use Cake\Routing\RouteCollection;

class RouteExtractor
{
    /**
     * Return an array of routes with useful metadata.
     *
     * @return array
     */
    public static function getAllRoutes(): array
    {
        $collection = Router::routes();
        return static::collectFromIterable($collection);
    }

    /**
     * Extract routes from a RouteCollection (for testing or custom use).
     *
     * @param \Cake\Routing\RouteCollection $collection
     * @return array
     */
    public static function getAllRoutesFromCollection(RouteCollection $collection): array
    {
        // RouteCollection may not be directly iterable in Cake 5; use getRoutes() when available.
        if (method_exists($collection, 'getRoutes')) {
            return static::collectFromIterable($collection->getRoutes());
        }
        return static::collectFromIterable($collection);
    }

    /**
     * Test helper to normalize any iterable of RouteInterface objects.
     *
     * @param iterable $iterable
     * @return array
     */
    public static function normalize(iterable $iterable): array
    {
        return static::collectFromIterable($iterable);
    }

    /**
     * Internal helper to normalize route metadata.
     *
     * @param iterable $iterable
     * @return array
     */
    private static function collectFromIterable($iterable): array
    {
        if ($iterable instanceof RouteCollection && method_exists($iterable, 'getRoutes')) {
            $iterable = $iterable->getRoutes();
        }
        if (!is_iterable($iterable)) {
            return [];
        }
        $routes = [];
        foreach ($iterable as $route) {
            // Accept any object that looks like a Route; don't strictly require the interface for test fakes.
            if (!is_object($route)) {
                continue;
            }
            // Support both Cake RouteInterface implementations and lightweight test doubles with public properties.
            $template = (property_exists($route, 'template') ? $route->template : (method_exists($route, 'getTemplate') ? $route->getTemplate() : null));
            $methods = (method_exists($route, 'getMethods') ? $route->getMethods() : (property_exists($route, 'methods') ? $route->methods : []));
            $defaults = (method_exists($route, 'getDefaults') ? $route->getDefaults() : (property_exists($route, 'defaults') ? $route->defaults : []));
            $routes[] = [
                'template' => $template,
                'methods' => (array)$methods,
                'defaults' => (array)$defaults,
                'name' => method_exists($route, 'getName') ? $route->getName() : null,
            ];
        }
        return $routes;
    }
}
