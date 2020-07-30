<?php

/**
 * GetImportsTest.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Tests\Unit\Entities;

use A\Fully\Qualified\Name;
use At\The\Bottom;

trait ThisIsATrait
{
}

/**
 * Class GetImportsTest
 * @package JWorman\AnnotationReader\Tests\Unit\Entities
 */
class GetImportsTest
{
    use ThisIsATrait;

    const CLASS_NAME = __CLASS__;
}