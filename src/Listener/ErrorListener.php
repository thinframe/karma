<?php

namespace ThinFrame\Karma\Listener;

use Psy\Shell;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use ThinFrame\Applications\DependencyInjection\ApplicationAwareTrait;
use ThinFrame\CommandLine\IO\InputDriverAwareTrait;
use ThinFrame\CommandLine\IO\OutputDriverAwareTrait;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Karma\Environment;
use ThinFrame\Karma\Exception\CatchableErrorException;

/**
 * Class ErrorListener
 *
 * @package ThinFrame\Karma\Listener
 * @since   0.3
 */
class ErrorListener implements ListenerInterface
{
    use InputDriverAwareTrait;
    use OutputDriverAwareTrait;
    use ContainerAwareTrait;
    use ApplicationAwareTrait;

    /**
     * Constructor
     */
    public function __construct()
    {
        set_error_handler([$this, 'convertCatchableErrorToException']);
        set_exception_handler([$this, 'handleException']);
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [];
    }

    /**
     * Convert catchable error to a exception
     *
     * @param int    $number
     * @param string $message
     * @param string $file
     * @param int    $line
     *
     * @throws CatchableErrorException
     */
    public function convertCatchableErrorToException($number, $message, $file, $line)
    {
        throw new CatchableErrorException($message, $number, $file, $line);
    }

    /**
     * Handle exception
     *
     * @param \Exception $exception
     */
    public function handleException(\Exception $exception)
    {
        $this->outputDriver->writeLine("[error]An exception occurred[/error]");

        $environment = $this->application->getMetadata()[$this->application->getName()]->get('environment')->getOrElse(
            new Environment('production')
        );
        /* @var $environment Environment */

        if (!$environment->equals(Environment::DEVELOPMENT)) {
            return;
        }

        $this->outputDriver->writeLine("[error]Type: " . get_class($exception) . "[/error]");
        $this->outputDriver->writeLine(
            "[error]Message: " . $exception->getMessage() . "[/error]"
        );
        $this->outputDriver->writeLine("[error]File: " . $exception->getFile() . "[/error]");
        $this->outputDriver->writeLine("[error]Line: " . $exception->getLine() . '[/error]');

        if ($this->inputDriver->readChoice('Do you want to debug this exception ?', ['y', 'n']) == 'y') {
            Shell::debug(
                ['exception' => $exception, 'container' => $this->container, 'application' => $this->application]
            );
        }
    }

}