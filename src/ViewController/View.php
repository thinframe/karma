<?php

/**
 * src/ViewController/View.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\ViewController;

/**
 * Class AbstractView
 *
 * @package ThinFrame\Karma\ViewController
 * @since   0.2
 */
class View
{
    /**
     * @var mixed
     */
    private $content;

    /**
     * @param mixed $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    public function __toString()
    {
        return (string)$this->content;
    }
}
