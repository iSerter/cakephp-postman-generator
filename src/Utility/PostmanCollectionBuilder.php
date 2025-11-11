<?php
declare(strict_types=1);

namespace Iserter\PostmanGenerator\Utility;

class PostmanCollectionBuilder
{
    public static function build(array $routes): array
    {
        $items = [];

        foreach ($routes as $route) {
            $controller = $route['defaults']['controller'] ?? 'Unknown';
            $action = $route['defaults']['action'] ?? 'index';
            $methods = $route['methods'] ?? [];
            $method = !empty($methods) ? strtoupper($methods[0]) : 'GET';
            $path = $route['template'] ?? '/';

            // Convert Cake route tokens like :id to :id (Postman will treat as path variables)
            $raw = '{{baseUrl}}' . $path;

            // Build path array for Postman (split and preserve tokens)
            $pathParts = array_values(array_filter(explode('/', trim($path, '/')), function($p){ return $p !== ''; }));

            $items[] = [
                'name' => $controller . '::' . $action,
                'request' => [
                    'method' => $method,
                    'header' => [
                        ['key' => 'Accept', 'value' => 'application/json']
                    ],
                    'url' => [
                        'raw' => $raw,
                        'host' => ['{{baseUrl}}'],
                        'path' => $pathParts,
                    ],
                    'description' => $route['name'] ?? ''
                ],
                'response' => []
            ];
        }

        return [
            'info' => [
                'name' => 'CakePHP Auto-Generated API Collection',
                'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json'
            ],
            'item' => $items
        ];
    }
}
