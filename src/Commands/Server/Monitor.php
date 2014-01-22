<?php

namespace ThinFrame\Karma\Commands\Server;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\DependencyInjection\OutputDriverAwareTrait;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Inotify\FileSystemWatcher;

/**
 * Class Restart
 *
 * @package ThinFrame\Karma\Commands\Server
 * @since   0.2
 */
class Monitor extends AbstractCommand
{
    use OutputDriverAwareTrait;

    /**
     * @var FileSystemWatcher
     */
    private $watcher;

    /**
     * Constructor
     *
     * @param FileSystemWatcher $watcher
     */
    public function __construct(FileSystemWatcher $watcher)
    {
        $this->watcher = $watcher;
    }

    /**
     * Get the argument the will trigger this command
     *
     * @return string
     */
    public function getArgument()
    {
        return 'monitor';
    }

    /**
     * Get the descriptions for this command
     *
     * @return string[]
     */
    public function getDescriptions()
    {
        return [
            'server monitor' => 'Restart the HTTP server when source files are changed'
        ];
    }

    /**
     * This method will be called if this command is triggered
     *
     * @param ArgumentsContainer $arguments
     *
     * @return mixed
     */
    public function execute(ArgumentsContainer $arguments)
    {
        $this->watcher->addPath(KARMA_ROOT . DIRECTORY_SEPARATOR . 'vendor');
        $this->watcher->addPath(KARMA_ROOT . DIRECTORY_SEPARATOR . 'src');
        $this->watcher->addPath(KARMA_ROOT . DIRECTORY_SEPARATOR . 'app/config');
        $this->outputDriver->send(
            '[format foreground="green" background="black" effects="bold"]' .
            ' Starting file system monitor ... ' .
            '[/format]' . PHP_EOL
        );
        while (true) {

        }
    }
}
