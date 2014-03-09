<?php

namespace ThinFrame\Karma\Manager;

use ThinFrame\Events\DispatcherAwareTrait;
use ThinFrame\Events\SimpleEvent;
use ThinFrame\Karma\Events;
use ThinFrame\Pcntl\Constants\Signal;
use ThinFrame\Pcntl\Process;
use ThinFrame\Server\Server;

/**
 * Class ServerManager
 *
 * @package ThinFrame\Karma\Manager
 * @since   0.3
 */
class ServerManager
{
    use DispatcherAwareTrait;

    /**
     * @var Server
     */
    private $server;

    /**
     * @var string
     */
    private $PIDFile = 'app/pid/server.pid';

    /**
     * Constructor
     *
     * @param Server $server
     */
    public function __construct(Server $server)
    {
        $this->server  = $server;
        $this->PIDFile = KARMA_ROOT . $this->PIDFile;
    }

    /**
     * Start the server
     */
    public function start()
    {
        $this->savePID();
        $this->dispatcher->trigger(new SimpleEvent(Events::PRE_SERVER_START));
        $this->server->start();
    }

    /**
     * Get server start command
     *
     * @return string
     */
    public function getStartCommand()
    {
        $process = new Process($this->getPID());

        return $process->getStartCommand();
    }

    /**
     * Stop the http server
     */
    public function stop()
    {
        $process = new Process($this->getPID());

        if ($process->sendSignal(new Signal(Signal::KILL))) {
            return true;
        } elsE {
            return false;
        }
    }

    /**
     * Prepare the server
     *
     * @param string $host
     * @param int    $port
     */
    public function prepare($host = null, $port = null)
    {
        if ($host) {
            $this->server->setHost($host);
        }
        if ($port) {
            $this->server->setPort($port);
        }
    }

    /**
     * Get server host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->server->getHost();
    }

    /**
     * Get server port
     *
     * @return int
     */
    public function getPort()
    {
        return $this->server->getPort();
    }

    /**
     * Check if the server is running
     *
     * @return bool
     */
    public function isRunning()
    {
        $process = new Process($this->getPID());

        return $process->isAlive();
    }

    /**
     * Save process PID into a file
     */
    public function savePID()
    {
        if (!file_exists($this->PIDFile)) {
            mkdir(dirname($this->PIDFile), 0755, true);
        }
        file_put_contents($this->PIDFile, getmypid());
    }

    /**
     * Get server PID
     *
     * @return int
     */
    public function getPID()
    {
        if (!file_exists($this->PIDFile)) {
            mkdir(dirname($this->PIDFile), 0755, true);
        }
        if ($pid = @file_get_contents($this->PIDFile)) {
            return intval($pid);
        }

        return 0;
    }
}