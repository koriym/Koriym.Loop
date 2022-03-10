<?php

declare(strict_types=1);

namespace Koriym\ResultSet;

use Countable;
use IteratorAggregate;

/**
 * @extends IteratorAggregate<array-key, object>
 */
interface EntityListInterface extends IteratorAggregate, Countable
{
}
