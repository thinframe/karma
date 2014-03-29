<?php

namespace ThinFrame\Karma\Command\Server;

use ThinFrame\CommandLine\Command\AbstractCommand;
use ThinFrame\CommandLine\IO\InputDriverInterface;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Inotify\FileSystemWatcher;
use ThinFrame\Inotify\InotifyEvent;
use ThinFrame\Karma\Manager\ServerManager;
use ThinFrame\Pcntl\Helper\Exec;

/**
 * Class Monitor
 * @package ThinFrame\Karma\Command\Server
 * @since   0.3
 */
class Monitor extends AbstractCommand implements ListenerInterface
{
    /**
     * @var FileSystemWatcher
     */
    private $watcher;

    /**
     * @var ServerManager
     */
    private $serverManager;

    /**
     * @var array
     */
    private $paths = [];

    /**
     * @var OutputDriverInterface
     */
    private $outputDriver;

    /**
     * @var InputDriverInterface
     */
    private $inputDriver;

    /**
     * @var bool
     */
    private $watching = false;

    /**
     * @var int
     */
    private $lastRestart = 0;

    /**
     * Constructor
     *
     * @param FileSystemWatcher $watcher
     * @param ServerManager     $serverManager
     * @param array             $paths
     */
    public function __construct(FileSystemWatcher $watcher, ServerManager $serverManager, array $paths = [])
    {
        $this->watcher       = $watcher;
        $this->paths         = $paths;
        $this->serverManager = $serverManager;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [InotifyEvent::EVENT_ID => ['method' => 'onChanges']];
    }


    /**
     * Get command argument
     *
     * @return string
     */
    public function getArgument()
    {
        return 'monitor';
    }

    /**
     * Get command descriptions
     *
     * @return array
     */
    public function getDescriptions()
    {
        return ['server monitor' => 'Restarts the HTTP server when the source code is changed'];
    }

    /**
     * Code that will be executed when command is triggered
     *
     * @param InputDriverInterface  $inputDriver
     * @param OutputDriverInterface $outputDriver
     *
     * @return bool
     */
    public function execute(InputDriverInterface $inputDriver, OutputDriverInterface $outputDriver)
    {
        foreach ($this->paths as $path) {
            $this->watcher->watchPath($path);
        }
        $this->outputDriver = $outputDriver;
        $this->inputDriver  = $inputDriver;
        $this->watching     = true;
        $outputDriver->writeLine('[info]Project source files are monitored ...[/info]');
        while (true) {

        }
    }

    /**
     * Restarts the HTTP server when changes appears
     *
     * @param InotifyEvent $event
     */
    public function onChanges(InotifyEvent $event)
    {
        if (!$this->watching) {
            return;
        }
        if (time() - $this->lastRestart < 10) {
            return;
        }
        if (!$this->serverManager->isRunning()) {
            $this->outputDriver->writeLine('[error]The HTTP server isn\'t running[/error]');

            return;
        }
        $command = $this->serverManager->getStartCommand();
        $this->outputDriver->writeLine('[info]Attempting to stop the current server instance[/info]');
        $this->serverManager->stop();
        sleep(1.5);
        if ($this->serverManager->isRunning()) {
            $this->outputDriver->writeLine('[error]Failed to stop the current server instance[/error]');

            return;
        }
        Exec::viaPipe($command . ' >/dev/null 2>&1 &', KARMA_ROOT);
        sleep(1.5);
        if ($this->serverManager->isRunning()) {
            $this->outputDriver->writeLine('[success]Server restarted[/success]');
            $this->lastRestart = time();

            return;
        } else {
            $this->outputDriver->writeLine('[error]The server cannot be restarted[/error]');

            return;
        }
    }
}
