<?php


declare(strict_types=1);

namespace Anarchitecture\combinatorics\Tests;

use PHPUnit\Framework\TestCase;

use Anarchitecture\pipe as p;
use function Anarchitecture\combinatorics\iterable_powerset;


final class IterablePowersetTest extends TestCase
{
    public function test_empty_iterable_yields_empty_subset_only() : void {

        $result = []
            |> iterable_powerset()
            |> p\collect(...);

        self::assertSame([[]], $result);
    }

    public function test_single_item() : void {

        $stage = ['x' => 10];

        $result = $stage
            |> iterable_powerset()
            |> p\collect(...);

        self::assertSame([
            [],
            ['x' => 10],
        ], $result);
    }

    public function test_two_items_preserves_keys_and_order() : void {

        $stage = ['a' => 1, 'b' => 2];

        $result = $stage
            |> iterable_powerset()
            |> p\collect(...);

        self::assertSame([
            [],
            ['a' => 1],
            ['b' => 2],
            ['a' => 1, 'b' => 2],
        ], $result);
    }

    public function test_works_with_generators() : void {

        $stage = (function () {
            yield 'a' => 1;
            yield 'b' => 2;
        })();

        $result = $stage
                |> iterable_powerset()
                |> p\collect(...);

        self::assertSame([
            [],
            ['a' => 1],
            ['b' => 2],
            ['a' => 1, 'b' => 2],
        ], $result);
    }
}
