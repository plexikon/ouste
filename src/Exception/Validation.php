<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Exception;

use Assert\Assertion;

class Validation extends Assertion
{
    /**
     * @var string
     */
    protected static $exceptionClass = InvalidAssertion::class;
}
