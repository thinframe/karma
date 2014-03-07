<?php

namespace ThinFrame\Karma\AnnotationHandler;

use ThinFrame\Annotations\AnnotationsHandlerInterface;

/**
 * Class RouteHandler
 *
 * @package ThinFrame\Karma\AnnotationHandler
 * @since   0.3
 */
class RouteHandler implements AnnotationsHandlerInterface
{
    /**
     * Handle class annotations
     *
     * @param mixed            $targetObj
     * @param \ReflectionClass $reflection
     * @param array            $annotations
     *
     * @return mixed
     */
    public function handleClassAnnotations(\ReflectionClass $reflection, array $annotations, $targetObj = null)
    {
        //noop
    }

    /**
     * Handle method annotations
     *
     * @param mixed             $targetObj
     * @param \ReflectionMethod $reflection
     * @param array             $annotations
     *
     * @return mixed
     */
    public function handleMethodAnnotations(\ReflectionMethod $reflection, array $annotations, $targetObj = null)
    {

    }

    /**
     * Handle property annotations
     *
     * @param mixed               $targetObj
     * @param \ReflectionProperty $reflection
     * @param array               $annotations
     *
     * @return mixed
     */
    public function handlePropertyAnnotations(\ReflectionProperty $reflection, array $annotations, $targetObj = null)
    {
        //noop
    }
}