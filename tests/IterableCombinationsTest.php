<?php

declare(strict_types=1);

namespace Anarchitecture\combinatorics\Tests;

use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

use Anarchitecture\pipe as p;

use function Anarchitecture\combinatorics\iterable_combinations;

final class IterableCombinationsTest extends TestCase
{
    public function testItThrowsWhenNIsNegative() : void {

        $this->expectException(InvalidArgumentException::class);

        iterable_combinations(-1);
    }

    public function testNZeroYieldsOneEmptyCombination() : void {

        $result = ['a' => 1, 'b' => 2]
            |> iterable_combinations(0)
            |> p\collect(...);

        self::assertSame([[]], $result);
    }

    public function testEmptyInputNZeroYieldsOneEmptyCombination() : void {

        $result = []
            |> iterable_combinations(0)
            |> p\collect(...);

        self::assertSame([[]], $result);
    }

    public function testEmptyInputNPositiveYieldsNothing() : void {

        $result = []
            |> iterable_combinations(2)
            |> p\collect(...);

        self::assertSame([], $result);
    }

    public function testNGreaterThanCountYieldsNothing() : void {

        $result = ['a' => 1, 'b' => 2]
            |> iterable_combinations(3)
            |> p\collect(...);

        self::assertSame([], $result);
    }

    public function testTwoOfThreePreservesKeysAndExpectedOrder() : void {

        $items = ['a' => 1, 'b' => 2, 'c' => 3];

        $result = $items
            |> iterable_combinations(2)
            |> p\collect(...);

        self::assertContains(['b' => 2, 'c' => 3], $result);
        self::assertContains(['a' => 1, 'c' => 3], $result);
        self::assertContains(['a' => 1, 'b' => 2], $result);
    }

    public function testItWorksWithGeneratorInputAndPreservesKeys() : void {

        $items = static function (): Generator {
            yield 'x' => 10;
            yield 'y' => 20;
            yield 'z' => 30;
        };

        $result = $items()
            |> iterable_combinations(2)
            |> p\collect(...);

        self::assertContains(['y' => 20, 'z' => 30], $result);
        self::assertContains(['x' => 10, 'z' => 30], $result);
        self::assertContains(['x' => 10, 'y' => 20], $result);
    }

    public function testCountMatchesBinomialCoefficient() : void {

        $items = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5];

        $result = $items
            |> iterable_combinations(3)
            |> p\collect(...);

        self::assertCount(10, $result);
    }
}
