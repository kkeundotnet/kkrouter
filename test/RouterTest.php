<?php
declare(strict_types=1);

use Kkeundotnet\Kkrouter\KkRouter;
use Kkeundotnet\Kkrouter\KkPattern;
use Kkeundotnet\Kkrouter\KkString;
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    private ?KkRouter $router = null;
    private string $result = '';

    private function init_router(): void
    {
        $this->result = '';

        if (!is_null($this->router)) {
            return;
        }

        $this->router = new KkRouter(function() : void {
                $this->result .= 'not found';
            });
        $this->router->add(
            [],
            function() : void {
                $this->result .= 'found: empty';
            }
        );
        $this->router->add(
            [new KkString('abc'), new KkString('def'), new KkString('ghi')],
            function() : void {
                $this->result .= 'found: strings';
            }
        );
        $this->router->add(
            [new KkString('pat'), new KkPattern('|^[a-z]+$|'), new KkPattern('|^[A-Z]+$|')],
            function($matched1, $matched2) : void {
                $this->result .= "found: patterns({$matched1}, {$matched2})";
            }
        );
    }

    public function testEmptyFound(): void
    {
        $this->init_router();
        $this->router->run('');

        $this->assertEquals(
            $this->result,
            'found: empty'
        );
    }

    public function testSlashFound(): void
    {
        $this->init_router();
        $this->router->run('');

        $this->assertEquals(
            $this->result,
            'found: empty'
        );
    }

    public function testStringFound(): void
    {
        $this->init_router();
        $this->router->run('abc/def/ghi');

        $this->assertEquals(
            $this->result,
            'found: strings'
        );
    }

    public function testStringWithTrimmingSlashesFound(): void
    {
        $this->init_router();
        $this->router->run('/abc/def/ghi/');

        $this->assertEquals(
            $this->result,
            'found: strings'
        );
    }

    public function testStringNotFound(): void
    {
        $this->init_router();
        $this->router->run('abc/def/ghi/jkl');

        $this->assertEquals(
            $this->result,
            'not found'
        );
    }

    public function testPatternFound(): void
    {
        $this->init_router();
        $this->router->run('pat/abc/DEF');

        $this->assertEquals(
            $this->result,
            'found: patterns(abc, DEF)'
        );
    }

    public function testPatternNotFound(): void
    {
        $this->init_router();
        $this->router->run('pat/aBc/DEF');

        $this->assertEquals(
            $this->result,
            'not found'
        );
    }
}
