<?php

namespace Koriym\ResultSet;

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

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->id . $this->name;
    }
}
