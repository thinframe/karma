<?php

namespace ThinFrame\Karma\Constants;

use ThinFrame\Foundation\DataTypes\AbstractEnum;

/**
 * Class Environment
 *
 * @package ThinFrame\Karma\Constants
 * @since   0.2
 */
class Environment extends AbstractEnum
{
    const PRODUCTION  = 'production';
    const DEVELOPMENT = 'development';
    const TEST        = 'test';
}
