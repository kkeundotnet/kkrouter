<?php

declare(strict_types=1);

namespace Kkeundotnet\Kkrouter;

interface KkElement
{
    /** @param string[] $matched */
    public function match(string $request, array &$matched): bool;
}
