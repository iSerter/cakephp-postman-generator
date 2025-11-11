<?php
declare(strict_types=1);

namespace Iserter\PostmanGenerator\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Iserter\PostmanGenerator\Utility\RouteExtractor;
use Iserter\PostmanGenerator\Utility\PostmanCollectionBuilder;

class GeneratePostmanCollectionCommand extends Command
{
    protected function buildOptionParser(\Cake\Console\ConsoleOptionParser $parser)
    {
        $parser->addOption('output', [
            'short' => 'o',
            'help' => 'Output path for generated Postman JSON',
            'default' => ROOT . DS . 'postman_collection.json'
        ]);
        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
        $output = $args->getOption('output');

        $io->out('Scanning routes...');
        $routes = RouteExtractor::getAllRoutes();

        if (empty($routes)) {
            $io->err('No routes discovered. Make sure your app routes are loaded.');
            return static::CODE_ERROR;
        }

        $collection = PostmanCollectionBuilder::build($routes);

        $written = file_put_contents($output, json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        if ($written === false) {
            $io->err('Failed to write collection to ' . $output);
            return static::CODE_ERROR;
        }

        $io->out(sprintf('✅ Postman collection generated at: %s', $output));
        return static::CODE_SUCCESS;
    }
}
