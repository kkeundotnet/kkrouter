<?php

declare(strict_types=1);

namespace Kkeundotnet\Kkrouter;

class KkRouter
{
    private array $routing_cases = [];

    /** @param callable(): void $default_callback */
    public function __construct(
        // Callable cannot be type hinted. https://stitcher.io/blog/typed-properties-in-php-74
        private /* callable */  $default_callback,
    ) {
    }

    /**
     * @param KkElement[] $configs
     * @param callable(string...): void $callback
     */
    public function add(
        array $configs,
        callable $callback,
    ): void {
        $this->routing_cases[] = ['configs' => $configs, 'callback' => $callback];
    }

    /**
     * @param string[] $request
     * @param KkElement[] $configs
     * @param string[] $matched
     */
    private static function match_routing_case(
        array $request,
        array $configs,
        array &$matched,
    ): bool {
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

    public function run(?string $request = null): void
    {
        if (is_null($request)) {
            $request_uri = $_SERVER['REQUEST_URI'] ?? '';
            $request = explode('?', $request_uri, 2)[0];
        }
        $request = $request === '' || $request === '/' ? [] : explode('/', trim($request, '/'));
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
