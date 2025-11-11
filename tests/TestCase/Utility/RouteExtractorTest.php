<?php
declare(strict_types=1);

namespace Iserter\PostmanGenerator\Test\TestCase\Utility;

use PHPUnit\Framework\TestCase;
use Iserter\PostmanGenerator\Utility\RouteExtractor;

class RouteExtractorTest extends TestCase
{
    public function testNormalizeSingleRoute(): void
    {
    $fake = new class {
            public string $template = '/users';
            public array $defaults = ['controller' => 'Users', 'action' => 'index'];
            public array $methods = ['GET'];
            public function parse(string $path): false|array { return false; }
            public function match(array $url, array $context = []): false|string { return false; }
            public function getName(): ?string { return 'users:index'; }
        };

        $routes = RouteExtractor::normalize([$fake]);

        $this->assertCount(1, $routes);
        $first = $routes[0];
        $this->assertSame('/users', $first['template']);
        $this->assertSame('Users', $first['defaults']['controller']);
        $this->assertSame('index', $first['defaults']['action']);
        $this->assertSame(['GET'], $first['methods']);
        $this->assertSame('users:index', $first['name']);
    }

    public function testNormalizeMultipleRoutes(): void
    {
    $a = new class {
            public string $template = '/posts';
            public array $defaults = ['controller' => 'Posts', 'action' => 'index'];
            public array $methods = ['GET'];
            public function parse(string $path): false|array { return false; }
            public function match(array $url, array $context = []): false|string { return false; }
            public function getName(): ?string { return null; }
        };
    $b = new class {
            public string $template = '/posts/add';
            public array $defaults = ['controller' => 'Posts', 'action' => 'add'];
            public array $methods = ['POST'];
            public function parse(string $path): false|array { return false; }
            public function match(array $url, array $context = []): false|string { return false; }
            public function getName(): ?string { return null; }
        };

        $routes = RouteExtractor::normalize([$a, $b]);
        $this->assertCount(2, $routes);
        $templates = array_column($routes, 'template');
        $this->assertContains('/posts', $templates);
        $this->assertContains('/posts/add', $templates);
    }
}
