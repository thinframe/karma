<?php

namespace ThinFrame\Karma\IO\Formatter;

use ThinFrame\CommandLine\IO\Formatter\ShortCodesFormatter;
use ThinFrame\Foundation\Helper\ShortCodesProcessor;

/**
 * Class PlainTestShortCodeFormatter
 *
 * @package ThinFrame\Karma\IO\Formatter
 * @since   0.3
 */
class PlainTextShortCodeFormatter extends ShortCodesFormatter
{
    /**
     * {@inheritdoc}
     */
    public function parse($attributes, $content = null, $tag = null, ShortCodesProcessor $processor = null)
    {
        return $content;
    }
}