<?php
declare(strict_types=1);

namespace Iserter\PostmanGenerator;

use Cake\Core\PluginApplicationInterface;
use Cake\Core\Plugin as BasePlugin;
use Cake\Console\CommandCollection;

class Plugin extends BasePlugin
{
    public function bootstrap(PluginApplicationInterface $app): void
    {
        // No-op by default; users can load config if needed
    }

    public function console(CommandCollection $commands): CommandCollection
    {
        // Register the command under a short name: iserter_postman generate
        $commands->add('iserter_postman generate', Command\GeneratePostmanCollectionCommand::class);
        return $commands;
    }
}
