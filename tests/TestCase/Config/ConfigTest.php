<?php
declare(strict_types=1);

namespace Iserter\PostmanGenerator\Test\TestCase\Config;

use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testConfigFileReturnsArray(): void
    {
        $config = require dirname(__DIR__, 3) . '/config/postman.php';
        $this->assertIsArray($config);
        $this->assertArrayHasKey('output', $config);
        $this->assertStringContainsString('postman_collection.json', $config['output']);
    }
}
