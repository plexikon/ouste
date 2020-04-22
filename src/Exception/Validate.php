<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Exception;

use Assert\Assert;

class Validate extends Assert
{
    /**
     * @var string
     */
    protected static $assertionClass = Validation::class;
}
