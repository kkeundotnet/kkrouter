<?php
declare(strict_types=1);

namespace Kkeundotnet\Kkrouter;

class KkString implements KkElement
{
    private string $s;

    public function __construct(string $s)
    {
        $this->s = $s;
    }

    public function match(string $request, array &$matched) : bool
    {
        return $request === $this->s;
    }
}
