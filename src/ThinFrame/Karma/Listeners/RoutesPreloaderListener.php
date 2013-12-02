<?php
namespace ThinFrame\Karma\Listeners;

use ThinFrame\Annotations\Processor;
use ThinFrame\Events\ListenerInterface;

/**
 * Class RoutesPreloaderListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.1
 */
class RoutesPreloaderListener implements ListenerInterface
{
    /**
     * @var Processor
     */
    private $processor;

    /**
     * Constructor
     *
     * @param Processor $processor
     */
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            'karma.server.pre_start' => [
                'method' => 'onServerPreStart'
            ]
        ];
    }

    /**
     * Handle server pre start event
     */
    public function onServerPreStart()
    {
        foreach (get_declared_classes() as $className) {
            if (in_array('ThinFrame\Karma\Controller\BaseController', class_parents($className))) {
                $this->processor->processClass($className);
            }
        }
    }
}
