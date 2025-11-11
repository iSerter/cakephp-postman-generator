<?php
declare(strict_types=1);

namespace Iserter\PostmanGenerator\Test\TestCase\Command;

use PHPUnit\Framework\TestCase;
use Cake\Routing\Router;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Iserter\PostmanGenerator\Command\GeneratePostmanCollectionCommand;

class GeneratePostmanCollectionCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Router::reload();
        $builder = Router::createRouteBuilder('/');
        $builder->connect('/users', ['controller' => 'Users', 'action' => 'index', '_name' => 'users:index']);
    }

    private function makeIo(): ConsoleIo
    {
        return new ConsoleIo();
    }

    public function testGeneratesCollectionFile(): void
    {
        $tmpFile = sys_get_temp_dir() . '/postman_test_' . uniqid() . '.json';
        $io = $this->makeIo();
        $command = new GeneratePostmanCollectionCommand();
        $args = new Arguments([], ['output' => $tmpFile], []);
        ob_start();
        $code = $command->execute($args, $io);
        $outputTxt = ob_get_clean();
        $this->assertSame(0, $code);
        $this->assertFileExists($tmpFile);
        $data = json_decode(file_get_contents($tmpFile), true);
        $this->assertIsArray($data['item']);
        $first = $data['item'][0];
        $this->assertSame('Users::index', $first['name']);
        $this->assertStringContainsString('Scanning routes', $outputTxt);
        @unlink($tmpFile);
    }

    public function testNoRoutesProducesError(): void
    {
        Router::reload(); // clear routes
        $tmpFile = sys_get_temp_dir() . '/postman_test_' . uniqid() . '.json';
    $io = $this->makeIo();
        $command = new GeneratePostmanCollectionCommand();
        $args = new Arguments([], ['output' => $tmpFile], []);
    ob_start();
    $code = $command->execute($args, $io);
    $errTxt = ob_get_clean();
    $this->assertSame(1, $code);
    $this->assertStringContainsString('No routes discovered', $errTxt);
    }
}
