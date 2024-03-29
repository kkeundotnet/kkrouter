<?php

declare(strict_types=1);

namespace Kkeundotnet\Kkrouter;

class KkString implements KkElement
{
    public function __construct(
        private readonly string $s,
    ) {
    }

    /** @param string[] $matched */
    public function match(string $request, array &$matched): bool
    {
        return $request === $this->s;
    }
}
