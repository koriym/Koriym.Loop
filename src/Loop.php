<?php

declare(strict_types=1);

namespace Koriym\Loop;

/**
 * @psalm-immutable
 */
final class Loop
{
    /** @var bool */
    public $isFirst;

    /** @var bool */
    public $isLast;

    /** @var int  */
    private int $index;

    /** @var int */
    public $iteration;

    public function __construct(
        bool $isFirst,
        bool $isLast,
        int $index
    ) {
        $this->isFirst = $isFirst;
        $this->isLast = $isLast;
        $this->index = $index;
        $this->iteration = $index + 1;
    }
}
