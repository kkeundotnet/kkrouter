<?php
declare(strict_types=1);

use Kkeundotnet\Kkrouter\KkRouter;
use Kkeundotnet\Kkrouter\KkPattern;
use Kkeundotnet\Kkrouter\KkString;
use PHPUnit\Framework\TestCase;

final class Test extends TestCase
{
    private ?KkRouter $router = null;

    private function init_router(): void
    {
        if (!is_null($this->router)) {
            return;
        }

        $this->router = new KkRouter(function() : void {
                echo 'not found';
            });
        $this->router->add(
            [],
            function() : void {
                echo 'found: empty';
            }
        );
        $this->router->add(
            [new KkString('abc'), new KkString('def'), new KkString('ghi')],
            function() : void {
                echo 'found: strings';
            }
        );
        $this->router->add(
            [new KkString('pat'), new KkPattern('|^[a-z]+$|'), new KkPattern('|^[A-Z]+$|')],
            function($matched1, $matched2) : void {
                echo "found: patterns({$matched1}, {$matched2})";
            }
        );
    }

    public function testEmptyFound(): void
    {
        $this->init_router();
        $this->expectOutputString('found: empty');
        $this->router->run('');
    }

    public function testSlashFound(): void
    {
        $this->init_router();
        $this->expectOutputString('found: empty');
        $this->router->run('/');
    }

    public function testStringFound(): void
    {
        $this->init_router();
        $this->expectOutputString('found: strings');
        $this->router->run('abc/def/ghi');
    }

    public function testStringWithTrimmingSlashesFound(): void
    {
        $this->init_router();
        $this->expectOutputString('found: strings');
        $this->router->run('/abc/def/ghi/');
    }

    public function testStringNotFound(): void
    {
        $this->init_router();
        $this->expectOutputString('not found');
        $this->router->run('abc/def/ghi/jkl');
    }

    public function testPatternFound(): void
    {
        $this->init_router();
        $this->expectOutputString('found: patterns(abc, DEF)');
        $this->router->run('pat/abc/DEF');
    }

    public function testPatternNotFound(): void
    {
        $this->init_router();
        $this->expectOutputString('not found');
        $this->router->run('pat/aBc/DEF');
    }
}
