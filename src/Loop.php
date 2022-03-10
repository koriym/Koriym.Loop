<?php

declare(strict_types=1);

namespace Koriym\ResultSet;

/**
 * @psalm-immutable
 */
final class Loop
{
    /** @var bool */
    public $isFirst;

    /** @var bool */
    public $isLast;

    /** @var int */
    public $iteration;

    public function __construct(
        bool $isFirst,
        bool $isLast,
        int $iteration
    ) {
        $this->isFirst = $isFirst;
        $this->isLast = $isLast;
        $this->iteration = $iteration;
    }
}
