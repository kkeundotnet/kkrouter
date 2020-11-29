<?php
declare(strict_types=1);

namespace Kkeundotnet\Kkrouter;

class KkRouter
{
    // Callable cannot be type hinted. https://stitcher.io/blog/typed-properties-in-php-74
    /* callable */ private $default_callback;
    private array $routing_cases = [];

    public function __construct(callable $default_callback)
    {
        $this->default_callback = $default_callback;
    }

    public function add(array $configs, callable $callback) : void
    {
        $this->routing_cases[] = ['configs' => $configs, 'callback' => $callback];
    }

    private static function match_routing_case(
        array $request,
        array $configs,
        array &$matched
    ) : bool {
        $len = count($request);
        if ($len !== count($configs)) {
            return false;
        }

        for ($i = 0; $i < $len; $i++) {
            if (!$configs[$i]->match($request[$i], $matched)) {
                return false;
            }
        }
        return true;
    }

    private static function explode(string $delim, string $s) : array
    {
        if (empty($s)) {
            return [];
        } else {
            return explode($delim, $s);
        }
    }

    public function run(string $request) : void
    {
        $request = self::explode('/', trim($request, '/'));
        foreach ($this->routing_cases as $routing_case) {
            $matched = [];
            if (self::match_routing_case($request, $routing_case['configs'], $matched)) {
                call_user_func_array($routing_case['callback'], $matched);
                return;
            }
        }
        ($this->default_callback)();
    }
}
