<?php

declare(strict_types=1);

namespace Kkeundotnet\Kkrouter;

class KkPattern implements KkElement
{
    public function __construct(
        private readonly string $pat,
    ) {
    }

    /** @param string[] $matched */
    public function match(string $request, array &$matched): bool
    {
        $r = preg_match($this->pat, $request) === 1;
        if ($r) {
            $matched[] = $request;
        }
        return $r;
    }
}
