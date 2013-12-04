<?php

/**
 * /src/ThinFrame/Karma/Whoops/PrettyPageHandler.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Whoops;

use Whoops\Handler\PrettyPageHandler as WhoopsPrettyPageHandler;

/**
 * Class PrettyPageHandler
 *
 * @package ThinFrame\Karma\Whoops
 * @since   0.1
 */
class PrettyPageHandler extends WhoopsPrettyPageHandler
{
    /**
     * {@inheritdoc}
     * @return int|null|void
     */
    public function handle()
    {
        #related to this issue https://github.com/filp/whoops/issues/115
        $_ENV['whoops-test'] = true;
        parent::handle();
    }
}
