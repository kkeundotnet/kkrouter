<?php

declare(strict_types=1);

namespace Kkeundotnet\Kkrouter;

interface KkElement
{
    public function match(string $request, array &$matched): bool;
}
