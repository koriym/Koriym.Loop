<?php

namespace Koriym\ResultSet;

use DateTime;
use DateTimeImmutable;

class FakeUser
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var ?DateTimeImmutable
     */
    public $date;

    public function __construct(int $id, string $name, ?DateTime $date = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->date = $date;
    }

    public function __toString()
    {
        return $this->id . $this->name;
    }
}
