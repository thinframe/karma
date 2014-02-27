<?php

namespace ThinFrame\Karma\IO\Formatter;

use ThinFrame\CommandLine\IO\OutputFormatterInterface;

/**
 * Class QuietFormatter
 *
 * @package ThinFrame\Karma\IO\Formatter
 * @since   0.3
 */
class QuietFormatter implements OutputFormatterInterface
{
    /**
     * Format a message
     *
     * @param string $message
     *
     * @return string
     */
    public function format($message)
    {
        return "";
    }
}
