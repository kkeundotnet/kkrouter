<?php

declare(strict_types=1);

namespace Kkeundotnet\Kkrouter;

class KkRouter
{
    private array $routing_cases = [];

    public function __construct(
        // Callable cannot be type hinted. https://stitcher.io/blog/typed-properties-in-php-74
        private /* callable */  $default_callback,
    ) {
    }

    public function add(
        array /* KkElement */ $configs,
        callable $callback,
    ): void {
        $this->routing_cases[] = ['configs' => $configs, 'callback' => $callback];
    }

    private static function match_routing_case(
        array /* string */ $request,
        array /* KkElement */ $configs,
        array /* string */ &$matched,
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

    public function run(?string $request=null): void
    {
        if (is_null($request)) {
            $request_uri = parse_url($_SERVER['REQUEST_URI']);
            $request = $request_uri['path'];
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
