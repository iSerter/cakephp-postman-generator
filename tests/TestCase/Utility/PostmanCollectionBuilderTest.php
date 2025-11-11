<?php
declare(strict_types=1);

namespace Iserter\PostmanGenerator\Test\TestCase\Utility;

use PHPUnit\Framework\TestCase;
use Iserter\PostmanGenerator\Utility\PostmanCollectionBuilder;

class PostmanCollectionBuilderTest extends TestCase
{
    public function testBuildSingleRoute(): void
    {
        $routes = [[
            'template' => '/users/:id',
            'methods' => ['get'],
            'defaults' => ['controller' => 'Users', 'action' => 'view'],
            'name' => 'users:view'
        ]];

        $collection = PostmanCollectionBuilder::build($routes);

        $this->assertArrayHasKey('info', $collection);
        $this->assertArrayHasKey('item', $collection);
        $this->assertCount(1, $collection['item']);
        $item = $collection['item'][0];
        $this->assertSame('Users::view', $item['name']);
        $this->assertSame(['users', ':id'], $item['request']['url']['path']);
        $this->assertSame('GET', $item['request']['method']);
        $this->assertStringContainsString('{{baseUrl}}/users/:id', $item['request']['url']['raw']);
    }

    public function testDefaultValuesApplied(): void
    {
        $routes = [[
            'template' => '/',
            'methods' => [],
            'defaults' => [],
            'name' => null
        ]];

        $collection = PostmanCollectionBuilder::build($routes);
        $item = $collection['item'][0];
        $this->assertSame('Unknown::index', $item['name']);
        $this->assertSame('GET', $item['request']['method']);
        $this->assertSame([], $item['request']['url']['path']);
    }

    public function testEmptyRoutes(): void
    {
        $collection = PostmanCollectionBuilder::build([]);
        $this->assertIsArray($collection['item']);
        $this->assertCount(0, $collection['item']);
    }
}
