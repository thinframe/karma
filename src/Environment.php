<?php

namespace ThinFrame\Karma;

use ThinFrame\Foundation\DataTypes\AbstractEnum;

/**
 * Class Environment
 *
 * @package ThinFrame\Karma
 * @since   0.3
 */
class Environment extends AbstractEnum
{
    const PRODUCTION  = 'production';
    const DEVELOPMENT = 'development';
    const TEST        = 'test';
}
