<?php

/**
 * src/Commands/Server/Monitor.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Commands\Server;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\DependencyInjection\OutputDriverAwareTrait;
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
            '[info]Starting file system monitor ... [/info]' . PHP_EOL
        );
        while (true) {

        }
        exit(0);
    }
}
