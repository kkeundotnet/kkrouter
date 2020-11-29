<?php
declare(strict_types=1);

namespace Kkeundotnet\Kkrouter;

class KkPattern implements KkElement
{
    private string $pat;

    public function __construct(string $pat)
    {
        $this->pat = $pat;
    }

    public function match(string $request, array &$matched) : bool
    {
        $r = preg_match($this->pat, $request) === 1;
        if ($r) {
            $matched[] = $request;
        }
        return $r;
    }
}
